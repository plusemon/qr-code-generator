<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;

class LicenseService
{
    protected $encryptionKey;

    protected $cipher = 'AES-256-CBC'; // Standard cipher
    protected $ivLength; // Initialization Vector length

    protected $licenseFilePath; // Path to the stored license file

    public function __construct()
    {
        // Get the key from .env or use a hardcoded fallback for demonstration.
        // In production, ensure this is a secure, obfuscated, and consistent key.
        $this->encryptionKey = env('LICENSE_DECRYPTION_KEY', 'your_32_char_secret_license_key_here');

        // Ensure the key is exactly 32 bytes (256 bits) for AES-256-CBC
        if (mb_strlen($this->encryptionKey, '8bit') !== 32) {
            throw new \RuntimeException('License encryption key must be exactly 32 characters for AES-256-CBC.');
        }

        $this->ivLength = openssl_cipher_iv_length($this->cipher);
        $this->licenseFilePath = storage_path('app/license.key');
    }

    /**
     * Generates an encrypted license key string from an array of data.
     * This method is conceptually for your support team's tool.
     *
     * @param array $data The license data to encrypt.
     * @return string The base64 encoded encrypted license key.
     * @throws \Exception If encryption fails.
     */
    public function generateLicenseKey(int $days = 365, string $start_at = null): string
    {

        if (!$start_at) {
            $start_at = now()->format('d-m-Y');
        }

        $expireAt = now()->make($start_at)->addDays($days)->format('d-m-Y');

        $data = [
            'client_id' => Str::of(Str::random(32))->prepend('QCM-')->slug()->upper()->__tostring(),
            'start_at' => $start_at,
            'days' => $days,
            'expire_at' => $expireAt,
            'provider' => 'plusemon'
        ];

        $json = json_encode($data);
        if ($json === false) {
            throw new \Exception('Failed to encode license data to JSON.');
        }

        // Generate a random Initialization Vector (IV)
        $iv = openssl_random_pseudo_bytes($this->ivLength);
        if ($iv === false) {
            throw new \Exception('Failed to generate IV.');
        }

        // Encrypt the JSON data
        $encrypted = openssl_encrypt($json, $this->cipher, $this->encryptionKey, OPENSSL_RAW_DATA, $iv);
        if ($encrypted === false) {
            throw new \Exception('Failed to encrypt license data.');
        }

        // Prepend the IV to the encrypted data, then base64 encode the whole thing
        // This makes it easy to retrieve the IV during decryption
        return base64_encode($iv . $encrypted);
    }

    /**
     * Decrypts a license key string into an array of data.
     * This method is used when the client submits a new license key.
     *
     * @param string $licenseKey The base64 encoded encrypted license key.
     * @return array|null The decrypted license data array, or null if decryption/validation fails.
     */
    protected function decryptLicenseKey(string $licenseKey): ?array
    {
        try {
            $decoded = base64_decode($licenseKey);
            if ($decoded === false || mb_strlen($decoded, '8bit') < $this->ivLength) {
                Log::error('License key decoding failed or too short.');
                return null;
            }

            // Extract the IV from the beginning of the decoded string
            $iv = mb_substr($decoded, 0, $this->ivLength, '8bit');
            $encrypted = mb_substr($decoded, $this->ivLength, null, '8bit');

            // Decrypt the data
            $decrypted = openssl_decrypt($encrypted, $this->cipher, $this->encryptionKey, OPENSSL_RAW_DATA, $iv);
            if ($decrypted === false) {
                Log::error('License key decryption failed. Check key consistency.');
                return null;
            }

            // Decode the JSON string back to an array
            $data = json_decode($decrypted, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::error('Failed to decode decrypted license data JSON: ' . json_last_error_msg());
                return null;
            }

            return $data;
        } catch (\Exception $e) {
            Log::error('Error during license key decryption: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Activates the application with a new license key provided by the client.
     *
     * @param string $licenseKey The encrypted license key string.
     * @return bool True if activation is successful, false otherwise.
     */
    public function activate(string $licenseKey): bool
    {
        $incomingLicenseData = $this->decryptLicenseKey($licenseKey);

        if (!$incomingLicenseData) {
            Log::warning('Activation failed: Invalid license key format or decryption error.');
            return false;
        }

        // Validate the structure and content of the incoming license data
        if (!isset($incomingLicenseData['client_id'], $incomingLicenseData['start_at'], $incomingLicenseData['days'], $incomingLicenseData['expire_at'], $incomingLicenseData['provider'])) {
            Log::warning('Activation failed: Missing required fields in license data.');
            return false;
        }

        if ($incomingLicenseData['provider'] !== 'plusemon') {
            Log::warning('Activation failed: Invalid provider.');
            return false;
        }

        try {
            $expireAt = Carbon::createFromFormat('d-m-Y', $incomingLicenseData['expire_at'])->endOfDay();
        } catch (\Exception $e) {
            Log::warning('Activation failed: Invalid date format in license data. ' . $e->getMessage());
            return false;
        }

        // Check if the incoming license key itself is still valid for activation
        if (Carbon::now()->greaterThan($expireAt)) {
            Log::warning('Activation failed: Incoming license key is expired.');
            return false;
        }

        try {
            File::put($this->licenseFilePath, json_encode($incomingLicenseData));
            Log::info('Application activated successfully for client: ' . $incomingLicenseData['client_id']);
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to write license file: ' . $e->getMessage());
            return false;
        }
    }

    public function isInstalled(): bool
    {
        return File::exists($this->licenseFilePath);
    }

    /**
     * Checks if the application is currently licensed and active.
     *
     * @return bool True if the license file exists and is valid, false otherwise.
     */
    public function isActive(): bool
    {
        if (!File::exists($this->licenseFilePath)) {
            return false; // No license file found
        }

        try {
            $fileContent = File::get($this->licenseFilePath);
            $licenseData = json_decode($fileContent, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::error('Failed to decode stored license data JSON: ' . json_last_error_msg());
                return false;
            }

            // Validate the structure and content of the stored license data
            if (!isset($licenseData['client_id'], $licenseData['start_at'], $licenseData['days'], $licenseData['expire_at'], $licenseData['provider'])) {
                Log::warning('Stored license file is missing required fields.');
                return false;
            }

            if ($licenseData['provider'] !== 'plusemon') {
                Log::warning('Stored license file has invalid provider.');
                return false;
            }

            $activeAt = Carbon::createFromFormat('d-m-Y', $licenseData['start_at'])->startOfDay();
            $expireAt = Carbon::createFromFormat('d-m-Y', $licenseData['expire_at'])->endOfDay();

            // Check if current date is within the active period
            return Carbon::now()->between($activeAt, $expireAt);

        } catch (\Exception $e) {
            Log::error('Error reading or validating license file: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Retrieves the current license information from the stored file.
     *
     * @return array|null The license data array, or null if no valid license is found.
     */
    public function getLicenseInfo(): ?array
    {
        if (!$this->isActive()) {
            return null;
        }

        try {
            $fileContent = File::get($this->licenseFilePath);
            return json_decode($fileContent, true);
        } catch (\Exception $e) {
            Log::error('Error getting license info: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Calculates remaining days on the license.
     *
     * @return int|null Remaining days, or null if not active.
     */
    public function getRemainingDays(): ?int
    {
        $licenseInfo = $this->getLicenseInfo();
        if (!$licenseInfo) {
            return null;
        }

        try {
            $expireAt = Carbon::createFromFormat('d-m-Y', $licenseInfo['expire_at'])->endOfDay();
            $now = Carbon::now();

            if ($now->greaterThan($expireAt)) {
                return 0; // Already expired
            }

            return $now->diffInDays($expireAt) + 1; // +1 to count the current day
        } catch (\Exception $e) {
            Log::error('Error calculating remaining days: ' . $e->getMessage());
            return null;
        }
    }
}
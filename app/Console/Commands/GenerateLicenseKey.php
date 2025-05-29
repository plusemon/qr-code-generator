<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use phpseclib3\Crypt\RSA;
use Illuminate\Console\Command;
use Illuminate\Encryption\Encrypter;

class GenerateLicenseKey extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'license:generate {client_id : The unique identifier for the client} {--days=30 : Number of days the license will be valid}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates a new encrypted license key for a client.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $rawKey = openssl_random_pseudo_bytes(32); // Generates 32 random bytes
        $cipher = 'AES-256-CBC'; // Or 'AES-256-GCM' if you prefer

        // Create an Encrypter instance (mimics Laravel's Crypt facade)
        $encrypter = new Encrypter($rawKey, $cipher);

        // Generate license data
        $clientId = 'CLIENT-XYZ-12345'; // Replace with actual client ID
        $issuedAt = Carbon::now();
        $expiresAt = $issuedAt->copy()->addDays(30);

        $licenseData = [
            "client_id" => $clientId,
            "issued_at" => $issuedAt->toDateTimeString(),
            "expires_at" => $expiresAt->toDateTimeString(),
        ];

        // Encrypt the JSON string using the same method Laravel uses
        $encryptedPayload = $encrypter->encrypt(json_encode($licenseData));

        // The actual license key to give to the client
        $licenseKeyForClient = base64_encode($encryptedPayload); // Base64 encode the *entire* payload

        echo "Generated License Key for $clientId: \n";
        echo $licenseKeyForClient . "\n";

        // --- Verification (Optional, for testing generation) ---
        try {
            $decryptedPayload = $encrypter->decrypt(base64_decode($licenseKeyForClient));
            $verifiedData = json_decode($decryptedPayload, true);
            echo "Verification successful:\n";
            print_r($verifiedData);
        } catch (\Exception $e) {
            echo "Verification failed: " . $e->getMessage() . "\n";
        }
    }
}
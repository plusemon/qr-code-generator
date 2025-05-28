<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Crypt;

class GeneratePrivateKey extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:private-key';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate private key';

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
        $privateKey = 'plusemon!@#123';
        $licenseFile = storage_path('app/private-key.dat');

        $signature = hash_hmac('sha256', 'password', $privateKey);
        $data['signature'] = $signature;
        file_put_contents(
            $licenseFile,
            Crypt::encrypt(json_encode($data))
        );

        $this->info('Private key generated successfully');
        return 0;
    }
}

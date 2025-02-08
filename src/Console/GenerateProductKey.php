<?php

namespace AceLords\Core\Console;

use Illuminate\Console\Command;
use Keygen\Keygen;

class GenerateProductKey extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'acelords:generate-product-key';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a unique system product key for the system.';

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
     * @return mixed
     */
    public function handle()
    {
        $path = base_path('.env');
        $key = Keygen::token(config('acelords_core.system.product_key_length'))->generate();
    
        $this->line('Generating a new Product Key... > Length: ' . config('acelords_core.system.product_key_length'));
    
        if (file_exists($path)) {
            file_put_contents($path, str_replace(
                'APP_PRODUCT_KEY=' . config('acelords_core.system.product_key'), 'APP_PRODUCT_KEY=' . $key, file_get_contents($path)
            ));
        }
    
        $this->info('Product key generated! Key: ' . $key);
    }
}

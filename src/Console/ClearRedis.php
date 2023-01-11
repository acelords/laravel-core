<?php

namespace AceLords\Core\Console;

use Illuminate\Console\Command;

class ClearRedis extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'acelords:clear-redis';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'AceLords command to clear redis';

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
        Cache::flush();

        $this->info("$key cleared! Refresh the page");
    }
}

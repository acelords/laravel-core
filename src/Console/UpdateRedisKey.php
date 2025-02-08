<?php

namespace AceLords\Core\Console;

use Illuminate\Console\Command;

class UpdateRedisKey extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'acelords:update-redis-key';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update a redis key in an AceLords system';

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
        $key = $this->ask('What is the redis key?', 'sidebar');

        if($this->confirm('Do you wish to clear the key?', true))
        {
            redis()->del($key);
            $this->info("$key cleared! Refresh the page");
            
            if($key == "sidebar") {
                $this->call('view:clear');
                $this->info("Views cleared! Refresh the page");
            }
        }

        $this->line("Looks like we're done for now.");
        
    }
}

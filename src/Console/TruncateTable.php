<?php

namespace AceLords\Core\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class TruncateTable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'acelords:truncate-table';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Truncate table in the system';

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
        $table = $this->ask('What\'s the table?');

        try {
            DB::table($table)->truncate();
            $this->info("Table truncated!");
        } catch(\Exception $e) {
            $this->error("An error occurred truncating the table!");
    
            if($this->confirm('Try while disabling foreign keys?'))
            {
                DB::statement("SET foreign_key_checks=0");
                
                DB::table($table)->truncate();
    
                DB::statement("SET foreign_key_checks=1");
                
                $this->info("Table truncated!");
                
            }
        }
        
    }
}

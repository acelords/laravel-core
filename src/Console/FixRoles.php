<?php

namespace AceLords\Core\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FixRoles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'acelords:fix-roles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix roles after migrating to or changing entrust config';

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
        $newUser = config('laratrust.user_models.users');
        $table = config('laratrust.tables.role_user');

        $this->info("adding user_type of {$newUser} to {$table} table");

        if($this->ask('Proceed?'))
        {
            DB::table($table)->update([
                'user_type' => $newUser,
            ]);

            $this->info("Well, we're done here!");
        } else {
            $this->info("Aborted!");
        }
    }
}

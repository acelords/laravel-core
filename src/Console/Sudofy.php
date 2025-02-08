<?php

namespace AceLords\Core\Console;

use Illuminate\Console\Command;

class Sudofy extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'acelords:sudofy';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sudofy a user in the system';

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
        $this->info('Okay! So you know what you are doing...apparently!');

        $username = $this->ask('What\'s the username?');

        if ($username)
        {
            $userModel = config('laratrust.user_models.users');
            $user = (new $userModel)->where('name', $username)->first();

            if($user)
            {
                $roleModel = config('laratrust.models.role');

                $role = (new $roleModel)->where('name', 'sudo')->first();

                if($role) {
                    $user->roles()->syncWithoutDetaching($role->id);
                    $this->info('Done! User sudofied');
                } else {
                    $this->info('Sudo role not found!');
                }

            } else {
                $this->info('A user with that username was not found!');
            }

        } else {
            $this->info('Did you really provide the username?');
        }
    }
}

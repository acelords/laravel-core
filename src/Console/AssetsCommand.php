<?php

namespace AceLords\Core\Console;

use Illuminate\Console\Command;

class AssetsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'acelords:publish-assets {project}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Re-publish the AceLords Project assets';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $project = $this->argument('project');
        $tag = "acelords-{$project}-assets";

        $this->line("Publishing assets >>> {$project} --tag={$tag} --force=true");

        $this->call('vendor:publish', [
            '--tag' => $tag,
            '--force' => true,
        ]);
    }
}

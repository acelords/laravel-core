<?php

namespace AceLords\Core\Console;

use Illuminate\Console\Command;

class TestQueue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'acelords:test-queue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test if queue is working by sending an email';

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
        SendTestQueueMail::dispatchNow("Sending was sent immediately! Time: " . now()->toDateTimeString());
        
        SendTestQueueMail::dispatch("Sending was delayed for 2 min. Time: " . now()->toDateTimeString())
            ->delay(now()->addMinutes(2));
    }
}

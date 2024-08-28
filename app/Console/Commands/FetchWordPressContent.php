<?php

namespace App\Console\Commands;


use Illuminate\Console\Command;
use App\Events\FetchWordPressContentEvent;

class FetchWordPressContent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:wordpress-content';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch WordPress content and store it in the database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Dispatch the event
        FetchWordPressContentEvent::dispatch();

        $this->info('WordPress content fetched successfully.');
    }
}

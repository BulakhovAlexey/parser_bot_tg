<?php

namespace App\Console\Commands;

use App\ParserClient\TelegramClient;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class FetchTelegramHistory extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fetch-telegram-history';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $client = new TelegramClient();
        $client->writeHistoryToJSON();
    }
}

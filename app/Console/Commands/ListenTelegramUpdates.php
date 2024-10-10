<?php

namespace App\Console\Commands;

use App\ParserClient\TelegramClient;
use App\ParserClient\TelegramEventHandler;
use Illuminate\Console\Command;

class ListenTelegramUpdates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:listen-updates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $client = new TelegramClient();
        TelegramEventHandler::startAndLoop('bot.madeline', $client->getSettings());
    }
}

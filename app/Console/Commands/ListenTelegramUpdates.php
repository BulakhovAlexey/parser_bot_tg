<?php

namespace App\Console\Commands;

use App\ParserClient\TelegramClient;
use Illuminate\Console\Command;

class SendMessagesToUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-messages-to-users';

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
        $tgClient = new TelegramClient();
        $tgClient->sendMessagesToUsers();
    }
}

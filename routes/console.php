<?php

use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


Artisan::command('setWebHook', function () {
    $http = \Illuminate\Support\Facades\Http::post(
        'https://api.telegram.org/bot' . env('TELEGRAM_BOT_TOKEN') . '/setWebHook',
        [
            'url' => env('APP_URL') . '/api/webhook'
        ]
    )->json();
    dd($http);
});

// команды для теста
// история чатов
Artisan::command('writeToJson', function () {
    $client = new \App\ParserClient\TelegramClient();
    $client->writeHistoryToJSON();
});

// слушаем чаты
Artisan::command('listen', function () {
    // Запуск бота
    $client = new \App\ParserClient\TelegramClient();
    App\ParserClient\TelegramEventHandler::startAndLoop('bot.madeline', $client->getSettings());
});

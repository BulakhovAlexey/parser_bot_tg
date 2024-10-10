<?php

namespace App\ParserClient;

use AllowDynamicProperties;
use App\Telegram\Webhook\Webhook;
use danog\MadelineProto\API;
use danog\MadelineProto\Logger;
use danog\MadelineProto\Settings;
use Illuminate\Support\Facades\Log;

#[AllowDynamicProperties] class TelegramClient
{
    private Settings $settings;
    private Settings\AppInfo $appInfo;
    private Settings\Logger $logger;
    private API $tgClient;

    public function __construct()
    {
        $this->settings = new Settings();
        $this->appInfo = new Settings\AppInfo();
        $this->logger = new Settings\Logger();
        $this->setAppSettings();
        $this->tgClient = new API('session.madeline', $this->settings);
    }

    protected function setAppSettings(): void
    {
        $this->settings
            ->setAppInfo(
                $this->appInfo
                    ->setApiId(env('TELEGRAM_CLIENT_API_ID'))
                    ->setApiHash(env('TELEGRAM_CLIENT_API_HASH'))
            )
            ->setLogger(
                $this->logger
                    ->setType(Logger::FILE_LOGGER) // Логирование в файл
                    ->setExtra('madeline.log') // Имя файла
                    ->setMaxSize(50 * 1024 * 1024) // Максимальный размер логов
                    ->setLevel(Logger::ERROR) // Логировать только ошибки
            );
    }

    public function writeHistoryToJSON(): void
    {
        try {
            $this->tgClient->start();
            $result = [];

            foreach (Webhook::CHATS as $chat) {
                $updates = $this->tgClient->messages->getHistory(['peer' => '@' . $chat, 'limit' => 500]);

                foreach ($updates['messages'] as $message) {
                    $result[] = $message['message'];
                }
            }

            $jsonResult = json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

            // Записываем JSON в файл
            file_put_contents(public_path('chat_histories.json'), $jsonResult);
            Log::info('success', [
                'success' => true,
                'newMessages' => count($result)
            ]);
        } catch (\Exception $e) {
            Log::error('Error writing chat history: ' . $e->getMessage());
        }
    }

    /**
     * @return Settings
     */
    public function getSettings(): Settings
    {
        return $this->settings;
    }

}

<?php

declare(strict_types=1);

namespace App\ParserClient;

use App\Facades\Telegram;
use App\Models\Chat;
use danog\MadelineProto\EventHandler\Attributes\Handler;
use danog\MadelineProto\EventHandler\Message;
use danog\MadelineProto\EventHandler\Plugin\RestartPlugin;
use danog\MadelineProto\EventHandler\SimpleFilter\Incoming;
use danog\MadelineProto\SimpleEventHandler;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class TelegramEventHandler extends SimpleEventHandler
{
    private bool $needLog = false;

    public const ADMIN = '@AlexBulakhov';

    public function getReportPeers()
    {
        return [self::ADMIN];
    }

    public static function getPlugins(): array
    {
        return [
            RestartPlugin::class,
        ];
    }

    public function onStart(): void
    {
        $this->logger("The bot was started at " . Carbon::now()->toDateTimeString());
        Telegram::message(env('TELEGRAM_ERROR_CHAT_ID'), '‼️ Слушатель чата запущен! ‼️')->send();
    }


    #[Handler]
    public function handleMessage(Incoming & Message $message): void
    {
        $this->listenerLogger('New message', [
                '--------------------------------------------------' => '--------------------------------------------------',
            ]
        );
        $chatID = $message->chatId;
        $this->listenerLogger('incoming message', [
                'mess' => $message->message,
                'from' => $chatID
            ]
        );

        if ($this->isStopListeningCommand($message->message, $chatID)) {
            Telegram::message(env('TELEGRAM_ERROR_CHAT_ID'), '‼️ Слушатель чата остановлен! ‼️')->send();
            $this->stop();
        }

        $info = $this->getFullInfo($chatID);

        $this->listenerLogger('chat_info', [
            'info' => $info
        ]);

//        if (isset($info['Chat']['username'])) {
        if (isset($info['User']['username']) || isset($info['Chat']['username'])) {
//            $chatName = '@' . $info['Chat']['username'];
            $chatName = isset($info['User']['username'])
                ? '@' . $info['User']['username']
                : '@' . $info['Chat']['username'];
            $this->listenerLogger('UserName', ['username' => $chatName]);

            if (isset($message->message)) {
                $chats = $this->getChats($chatName);
                if ($chats) {
                    $this->doAction($chats, $message);
                }
            }
        }
    }

    private function isStopListeningCommand($message, $chatID): bool
    {
        return $message == env('TELEGRAM_LISTENER_STOP_MESSAGE') && $chatID == env('TELEGRAM_ADMIN_CHAT_ID');
    }

    private function doAction($chats, $message): void
    {
        foreach ($chats as $chat) {
            if (str_contains($message->message, $chat->street)) {
                $id = Telegram::message(
                    $chat->recipient,
                    $this->getMessage($chat->street, $message->message)
                )->send();
                if ($id) {
                    $this->listenerLogger('send to user', ['user' => $chat->recipient, 'message' => $message->message]);
                } else {
                    $this->listenerLogger('send error', [
                            'message' => $message->message,
                            'user' => $chat->recipient,
                            'chat' => $chat->chat_to_parse,
                            'street' => $chat->street,
                        ]
                    );
                }
            }
        }
    }

    private function getChats($chatName)
    {
        return Chat::where('chat_to_parse', $chatName)
            ->where('confirmed', 1)
            ->get();
    }

    private function getMessage($street, $message): string
    {
        return '‼️ 😱 ‼️' . str_replace($street, '👉<b>' . $street . '</b>👈', $message);
    }

    private function listenerLogger(string $string, array $params): void
    {
        if ($this->needLog) {
            Log::info($string, $params);
        }
    }


}

<?php

namespace App\Telegram\Helpers;

use App\Telegram\Webhook\Webhook;

class InlineButton
{
    private static $button_number = 1;
    public static $buttons = [
        'inline_keyboard' => [

        ]
    ];

    public static function add(mixed $text, string $action, array $data, int $row = 1)
    {
        $data['action'] = $action;
        $data['button_number'] = self::$button_number;
        self::$button_number++;
        self::$buttons['inline_keyboard'][$row - 1][] = [
            'text' => $text,
            'callback_data' => json_encode($data)
        ];
    }

    public static function link(mixed $text, string $url, int $row = 1)
    {
        self::$buttons['inline_keyboard'][$row - 1][] = [
            'text' => $text,
            'url' => $url
        ];
    }

    public static function reset(): void
    {
        self::$buttons = ['inline_keyboard' => []];
        self::$button_number = 1;
    }

    public static function getDefault(): void
    {
        self::reset();
        self::add('Посмотреть список чатов', 'ChatsShow', ['data' => 1], 1);
        self::add('Выбрать чат', 'ChatSelect', ['data' => 1], 2);
        self::add('Выбрать улицу', 'StreetSelect', ['data' => 1], 2);
        self::add('Подписаться', 'Subscribe', ['data' => 1], 3);
        self::add('Отписаться', 'Unsubscribe', ['data' => 1], 3);
        self::add('Информация о подписке', 'Info', ['data' => 1], 4);
    }

    public static function getBackButton($reset = true, $row = 1): void
    {
        if ($reset) {
            self::reset();
        }
        self::add('Главное меню', 'Back', ['data' => 1], $row);
    }

    public static function getChatSelectButtons(): void
    {
        self::reset();
        foreach (Webhook::CHATS as $key => $chat) {
            self::add($chat, 'AddChat', ['chat_id' => $key], $key + 1);
        }
    }

    public static function getUnSubscribeButtons(): void
    {
        self::reset();
        self::add('Да 😔', 'UnsubscribeAction', ['unsubscribe' => 1], 1);
        self::add('Нет 👏', 'UnsubscribeAction', ['unsubscribe' => 0], 1);
    }
}

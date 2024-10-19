<?php

namespace App\Telegram\Webhook\Actions;

use App\Facades\Telegram;
use App\Models\Chat;
use App\Telegram\Helpers\InlineButton;
use App\Telegram\Webhook\Webhook;

class UnsubscribeAction extends Webhook
{
    protected int $answer;

    public function run(): void
    {
        $message = $this->getUnsubscribeMessage();

        InlineButton::getDefault();
        Telegram::editButtons(
            $this->chat_id,
            $message,
            InlineButton::$buttons,
            $this->message_id
        )->send();
    }

    protected function getUnsubscribeMessage(): string
    {
        $chat = Chat::where('recipient', $this->chat_id)->first();
        $this->getAnswer();
        if ($chat) {
            if ($this->answer === 1) {
                $chat->confirmed = 0;
                $chat->save();
                return $this->getMessageBlade('telegram.unsubscribe.confirm', []);
            } else {
                $chat->confirmed = 1;
                $chat->save();
                return $this->getMessageBlade('telegram.unsubscribe.cancel', []);
            }
        } else {
            return $this->getMessageBlade('telegram.unsubscribe.error', []);
        }
    }

    protected function getAnswer(): int
    {
        $data = json_decode($this->request->input('callback_query')['data'], true);
        return $this->answer = $data['unsubscribe'];
    }

}

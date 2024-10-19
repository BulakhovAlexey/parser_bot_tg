<?php

namespace App\Telegram\Webhook\Actions;

use App\Facades\Telegram;
use App\Models\Chat;
use App\Telegram\Helpers\InlineButton;
use App\Telegram\Webhook\Webhook;
use Illuminate\Support\Facades\Cache;

class AddStreet extends Webhook
{

    protected string $street;
    protected int $counter;

    public function run()
    {
        $this->getFormatStreet();

        if (!$this->checkLength()) {
            return Telegram::message($this->chat_id, $this->getMessageBlade('telegram.street.errorLength', []))->send();
        }

        $this->getStreetCounter();

        if ($this->counter < 2) {
            return Telegram::message(
                $this->chat_id,
                $this->getMessageBlade(
                    'telegram.street.errorExist',
                    ['counter' => $this->counter, 'street' => $this->street]
                )
            )->send();
        }

        Chat::updateOrCreate(['recipient' => $this->chat_id], [
            'recipient' => $this->chat_id,
            'street' => $this->street,
        ]);

        InlineButton::getBackButton();

        Telegram::buttons(
            $this->chat_id,
            $this->getMessageBlade('telegram.street.success', [
                'street' => $this->street,
                'counter' => $this->counter,
            ]),
            InlineButton::$buttons
        )->send();

        Cache::forget(env('STREET_SELECT_CACHE_KEY') . $this->chat_id);
    }

    protected function checkLength(): bool
    {
        return strlen($this->street) > 3;
    }

    protected function getStreetCounter(): int
    {
        $data = json_decode(file_get_contents(public_path('chat_histories.json')), true);
        $this->counter = 0;
        foreach ($data as $message) {
            if (str_contains($message, ' ' . $this->street . ' ') || str_contains($message, $this->street . ',')) {
                $this->counter++;
            }
        }
        return $this->counter;
    }

    protected function getFormatStreet(): string
    {
        $street = strtolower($this->request->input('message')['text']);
        if (str_contains($street, ' ')) {
            $streetArray = explode(' ', $street);
            foreach ($streetArray as &$item) {
                $item = ucfirst($item);
            }
            $this->street = implode(' ', $streetArray);
        } else {
            $this->street = ucfirst($street);
        }
        return $this->street;
    }
}

<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class IsStreetFromChats implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $chatHistory = json_decode(file_get_contents(public_path('chat_histories.json')), true);

        $founded = false;
        foreach ($chatHistory as $message) {
            if (str_contains($message, $value)) {
                $founded = true;
                break;
            }
        }

        if (!$founded) {
            $fail('This street was not found in last messages');
        }
    }
}

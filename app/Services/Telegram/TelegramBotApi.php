<?php

namespace App\Services\Telegram;

use App\Exceptions\TelegramBotApiException;
use Exception;
use Illuminate\Support\Facades\Http;

class TelegramBotApi
{
    public const BASE_URL = 'https://api.telegram.org/bot';

    public static function sendMessage(string $token, int $chatId, string $text): bool
    {
        try {
            $response = Http::get(self::BASE_URL . $token . '/sendMessage', [
                'chat_id' => $chatId,
                'text' => $text,
            ])->throw();

            return $response->successful();
        } catch (Exception $exception) {
            report(new TelegramBotApiException($exception));

            return false;
        }
    }
}

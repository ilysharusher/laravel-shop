<?php

namespace App\Services\Telegram;

use App\Exceptions\TelegramBotApiException;
use Exception;
use Illuminate\Support\Facades\Http;

class TelegramBotApi
{
    public const BASE_URL = 'https://api.telegram.org/bot';

    /**
     * Sends a message to a specified Telegram chat.
     *
     * @param string $token The bot token to authenticate the request.
     * @param int $chatId The ID of the chat where the message will be sent.
     * @param string $text The text of the message to be sent.
     * @return bool Returns true if the message was sent successfully, false otherwise.
     */
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

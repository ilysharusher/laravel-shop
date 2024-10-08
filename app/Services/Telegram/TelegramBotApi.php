<?php

namespace App\Services\Telegram;

use Illuminate\Support\Facades\Http;

class TelegramBotApi
{
    public const BASE_URL = 'https://api.telegram.org/bot';

    /**
     * Sends a message to a specified Telegram chat using the Telegram Bot API.
     *
     * @param string $token The bot token provided by the BotFather.
     * @param int $chatId The unique identifier for the target chat or username of the target channel.
     * @param string $text The text of the message to be sent.
     *
     * @return void
     */
    public static function sendMessage(string $token, int $chatId, string $text): void
    {
        Http::get(self::BASE_URL . $token . '/sendMessage', [
            'chat_id' => $chatId,
            'text' => $text,
        ]);
    }
}

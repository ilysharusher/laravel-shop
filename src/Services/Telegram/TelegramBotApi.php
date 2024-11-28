<?php

namespace Services\Telegram;

use Illuminate\Support\Facades\Http;
use Services\Telegram\Exceptions\TelegramBotApiException;
use Throwable;

class TelegramBotApi implements TelegramBotApiContract
{
    public const BASE_URL = 'https://api.telegram.org/bot';

    public static function fake(): TelegramBotApiFake
    {
        return app()->instance(
            TelegramBotApiContract::class,
            new TelegramBotApiFake()
        );
    }

    public static function sendMessage(string $token, int $chatId, string $text): bool
    {
        try {
            $response = Http::get(self::BASE_URL . $token . '/sendMessage', [
                'chat_id' => $chatId,
                'text' => $text,
            ])->throw();

            return $response->successful() && $response['ok'];
        } catch (Throwable $exception) {
            report(new TelegramBotApiException($exception));

            return false;
        }
    }
}

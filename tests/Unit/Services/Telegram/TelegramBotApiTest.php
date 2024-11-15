<?php

namespace Tests\Unit\Services\Telegram;

use Illuminate\Support\Facades\Http;
use Services\Telegram\TelegramBotApi;
use Tests\TestCase;

class TelegramBotApiTest extends TestCase
{
    public function test_send_message_success(): void
    {
        Http::fake([
            TelegramBotApi::BASE_URL . '*' => Http::response(['ok' => true]),
        ]);

        $result = TelegramBotApi::sendMessage('', 123, 'text');

        $this->assertTrue($result);
    }
}

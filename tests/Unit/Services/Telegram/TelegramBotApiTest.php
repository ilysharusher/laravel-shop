<?php

namespace Tests\Unit\Services\Telegram;

use Illuminate\Support\Facades\Http;
use Services\Telegram\TelegramBotApi;
use Services\Telegram\TelegramBotApiContract;
use Tests\TestCase;

class TelegramBotApiTest extends TestCase
{
    public function test_send_message_success(): void
    {
        Http::fake([
            TelegramBotApi::BASE_URL . '*' => Http::response(['ok' => true]),
        ]);

        $result = TelegramBotApi::sendMessage('token', 123, 'text');

        $this->assertTrue($result);
    }

    public function test_send_message_success_by_fake_instance(): void
    {
        TelegramBotApi::fake()
            ->returnTrue();

        $result = app(TelegramBotApiContract::class)::sendMessage('token', 1, 'test');

        $this->assertTrue($result);
    }

    public function test_send_message_fail_by_fake_instance(): void
    {
        TelegramBotApi::fake()
            ->returnFalse();

        $result = app(TelegramBotApiContract::class)::sendMessage('token', 1, 'test');

        $this->assertFalse($result);
    }
}

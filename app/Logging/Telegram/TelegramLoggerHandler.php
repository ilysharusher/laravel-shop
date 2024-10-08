<?php

namespace App\Logging\Telegram;

use App\Services\Telegram\TelegramBotApi;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;
use Monolog\LogRecord;

class TelegramLoggerHandler extends AbstractProcessingHandler
{
    public function __construct(array $config)
    {
        parent::__construct(Logger::toMonologLevel($config['level']));
    }

    protected function write(LogRecord $record): void
    {
        TelegramBotApi::sendMessage(
            config('logging.channels.telegram.token'),
            config('logging.channels.telegram.chat_id'),
            $record->formatted
        );
    }
}

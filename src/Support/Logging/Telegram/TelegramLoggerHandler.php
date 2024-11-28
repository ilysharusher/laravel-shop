<?php

namespace Support\Logging\Telegram;

use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;
use Monolog\LogRecord;
use Services\Telegram\TelegramBotApiContract;

class TelegramLoggerHandler extends AbstractProcessingHandler
{
    public function __construct(array $config)
    {
        parent::__construct(Logger::toMonologLevel($config['level']));
    }

    protected function write(LogRecord $record): void
    {
        app(TelegramBotApiContract::class)::sendMessage(
            config('logging.channels.telegram.token'),
            (int)config('logging.channels.telegram.chat_id'),
            "{$record['message']}
            \n{$record['datetime']->format('Y-m-d H:i:s')}"
        );
    }
}

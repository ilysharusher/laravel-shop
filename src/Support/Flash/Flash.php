<?php

namespace Support\Flash;

use Illuminate\Contracts\Session\Session;

class Flash
{
    public const MESSAGE_KEY = 'flash_message';
    public const MESSAGE_CLASS_KEY = 'flash_message_class';

    public function __construct(
        protected Session $session
    ) {
    }

    public function getMessage(): ?FlashMessage
    {
        if (!$this->session->has(self::MESSAGE_KEY)) {
            return null;
        }

        return new FlashMessage(
            $this->session->get(self::MESSAGE_KEY),
            $this->session->get(self::MESSAGE_CLASS_KEY, '')
        );
    }

    private function makeFlash(string $type, string $message): void
    {
        $this->session->flash(self::MESSAGE_KEY, $message);
        $this->session->flash(self::MESSAGE_CLASS_KEY, config("flash.$type", ''));
    }

    public function info(string $message): void
    {
        $this->makeFlash('info', $message);
    }

    public function alert(string $message): void
    {
        $this->makeFlash('alert', $message);
    }
}

<?php

namespace App\Menu;

use Support\Traits\Makeable;

class MenuItem
{
    use Makeable;

    public function __construct(
        protected string $title,
        protected string $url,
    ) {
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function isActive(): bool
    {
        return parse_url(
            request()->url(),
            PHP_URL_PATH
        ) === parse_url($this->url, PHP_URL_PATH);
    }
}

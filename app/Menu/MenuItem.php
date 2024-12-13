<?php

namespace App\Menu;

class MenuItem
{
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
        return request()->url() === $this->url;
    }
}

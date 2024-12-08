<?php

namespace Domain\Catalog\ViewModels;

use Domain\Catalog\Models\Category;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Support\Traits\Makeable;

class CategoryViewModel
{
    use Makeable;

    public function homePage(): Collection
    {
        return Cache::rememberForever('categories.homePage', static function () {
            return Category::query()->homePage()->get();
        });
    }
}
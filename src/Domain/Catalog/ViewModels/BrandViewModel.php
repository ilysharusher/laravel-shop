<?php

namespace Domain\Catalog\ViewModels;

use Domain\Catalog\Models\Brand;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Support\Traits\Makeable;

class BrandViewModel
{
    use Makeable;

    public function homePage(): Collection
    {
        return Cache::rememberForever('brands.homePage', static function () {
            return Brand::query()->homePage()->get();
        });
    }
}

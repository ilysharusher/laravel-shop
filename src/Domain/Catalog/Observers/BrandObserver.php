<?php

namespace Domain\Catalog\Observers;

use Domain\Catalog\Models\Brand;
use Illuminate\Support\Facades\Cache;

class BrandObserver
{
    /**
     * Handle the Brand "created" event.
     */
    public function created(Brand $brand): void
    {
        //
    }

    /**
     * Handle the Brand "updated" event.
     */
    public function updated(Brand $brand): void
    {
        Cache::forget('brands.homePage');
        Cache::forget('brands.catalogPage');
    }

    /**
     * Handle the Brand "deleted" event.
     */
    public function deleted(Brand $brand): void
    {
        Cache::forget('brands.homePage');
        Cache::forget('brands.catalogPage');
    }

    /**
     * Handle the Brand "restored" event.
     */
    public function restored(Brand $brand): void
    {
        //
    }

    /**
     * Handle the Brand "force deleted" event.
     */
    public function forceDeleted(Brand $brand): void
    {
        //
    }
}

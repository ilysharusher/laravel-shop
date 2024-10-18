<?php

namespace App\Traits\Models;

use Illuminate\Database\Eloquent\Model;

trait HasSlug
{
    public static function slugFrom(): string
    {
        return 'title';
    }

    private static function addIndexToSlug(string $slug): string
    {
        $count = self::query()->where('slug', 'like', "{$slug}%")->count();

        return $count ? "-{$count}" : '';
    }

    protected static function bootHasSlug(): void
    {
        static::creating(static function (Model $instance) {
            $baseSlug = str($instance->{static::slugFrom()})->slug();

            $instance->slug ??= $baseSlug . self::addIndexToSlug($baseSlug);
        });
    }
}

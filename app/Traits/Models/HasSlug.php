<?php

namespace App\Traits\Models;

use Illuminate\Database\Eloquent\Model;

trait HasSlug
{
    public static function slugFrom(): string
    {
        return 'title';
    }

    protected static function bootHasSlug(): void
    {
        static::creating(static function (Model $instance) {
            $instance->slug ??= str($instance->{self::slugFrom()})->append(time())->slug();
        });
    }
}

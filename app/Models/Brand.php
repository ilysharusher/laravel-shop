<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Brand extends Model
{
    /** @use HasFactory<\Database\Factories\BrandFactory> */
    use HasFactory;

    protected $fillable = [
        'slug',
        'title',
        'thumbnail'
    ];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    protected static function boot(): void
    {
        parent::boot();

        static::creating(static function (Brand $brand) {
            $brand->slug ??= Str::slug($brand->title);
        });
    }
}

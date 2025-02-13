<?php

namespace Domain\Catalog\Models;

use Domain\Catalog\Collections\CategoryCollection;
use Domain\Catalog\Observers\CategoryObserver;
use Domain\Catalog\QueryBuilders\CategoryQueryBuilder;
use Domain\Product\Models\Product;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Support\Traits\Models\HasSlug;

/**
 * @method static Category|CategoryQueryBuilder query()
 */
#[ObservedBy(CategoryObserver::class)]
class Category extends Model
{
    /** @use HasFactory<\Database\Factories\CategoryFactory> */
    use HasFactory;
    use HasSlug;

    protected $fillable = [
        'slug',
        'title',
        'on_home_page',
        'sorting',
    ];

    public function newEloquentBuilder($query): CategoryQueryBuilder
    {
        return new CategoryQueryBuilder($query);
    }

    public function newCollection(array $models = []): CategoryCollection
    {
        return new CategoryCollection($models);
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class);
    }
}

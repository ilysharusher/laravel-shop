<?php

namespace Domain\Product\Models;

use App\Jobs\ProductJsonProperties;
use Domain\Catalog\Models\Brand;
use Domain\Catalog\Models\Category;
use Domain\Product\QueryBuilders\ProductQueryBuilder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Laravel\Scout\Attributes\SearchUsingFullText;
use Laravel\Scout\Searchable;
use Support\Casts\PriceCast;
use Support\Traits\Models\HasSlug;
use Support\Traits\Models\HasThumbnail;

/**
 * @method static Product|ProductQueryBuilder query()
 */
class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory;
    use HasSlug;
    use HasThumbnail;
    use Searchable;

    protected $fillable = [
        'slug',
        'json_properties',
        'title',
        'description',
        'thumbnail',
        'price',
        'brand_id',
        'on_home_page',
        'sorting',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::created(static function (Product $product) {
            dispatch(new ProductJsonProperties($product))
                ->delay(now()->addSeconds(10));
        });

        static::updated(static function (Product $product) {
            dispatch(new ProductJsonProperties($product))
                ->delay(now()->addSeconds(10));
        });
    }

    protected function casts(): array
    {
        return [
            'price' => PriceCast::class
        ];
    }

    protected function jsonProperties(): Attribute
    {
        return Attribute::get(function ($value) {
            if ($value) {
                return json_decode($value, true, 512, JSON_THROW_ON_ERROR);
            }

            $this->loadMissing('properties');

            return $this->properties->keyValues() ?? [];
        });
    }

    protected function thumbnailDir(): string
    {
        return 'products';
    }

    #[SearchUsingFullText(['title', 'description'])]
    public function toSearchableArray(): array
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
        ];
    }

    public function newEloquentBuilder($query): ProductQueryBuilder
    {
        return new ProductQueryBuilder($query);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    public function properties(): BelongsToMany
    {
        return $this->belongsToMany(Property::class)
            ->withPivot('value');
    }

    public function optionValues(): BelongsToMany
    {
        return $this->belongsToMany(OptionValue::class);
    }
}

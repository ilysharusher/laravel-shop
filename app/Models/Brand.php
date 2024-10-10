<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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

    protected function setSlugAttribute($value): void
    {
        $this->attributes['slug'] = $value ?: Str::slug($this->attributes['title']);  // TODO - Check if this is correct (in 3rd lesson will be reviewed)
    }
}

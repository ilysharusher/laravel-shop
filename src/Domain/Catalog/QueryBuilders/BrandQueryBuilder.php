<?php

namespace Domain\Catalog\QueryBuilders;

use Illuminate\Database\Eloquent\Builder;

class BrandQueryBuilder extends Builder
{
    public function homePage(): static
    {
        return $this->select('id', 'slug', 'title', 'thumbnail')
            ->where('on_home_page', true)
            ->orderBy('sorting')
            ->limit(6);
    }

    public function catalogPage(): static
    {
        return $this->select('id', 'title')
            ->has('products');
    }
}

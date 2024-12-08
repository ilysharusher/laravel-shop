<?php

namespace Domain\Catalog\QueryBuilders;

use Illuminate\Database\Eloquent\Builder;

class CategoryQueryBuilder extends Builder
{
    public function homePage(): static
    {
        return $this->select('id', 'slug', 'title')
            ->where('on_home_page', true)
            ->orderBy('sorting')
            ->limit(6);
    }
}

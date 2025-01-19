<?php

namespace App\View\ViewModels;

use Domain\Catalog\Models\Category;
use Domain\Catalog\ViewModels\CategoryViewModel;
use Domain\Product\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Spatie\ViewModels\ViewModel;

class CatalogViewModel extends ViewModel
{
    public function __construct(
        public Category $category
    ) {
        //
    }

    public function categories(): Collection
    {
        return CategoryViewModel::make()->catalogPage();
    }

    public function products(): LengthAwarePaginator
    {
        return Product::search(request('search') ?: '')
            ->query(function (Builder $query) {
                $query->select('id', 'slug', 'title', 'thumbnail', 'price', 'json_properties')
                    ->withCategory($this->category)
                    ->filtered()
                    ->sorted();
            })
            ->paginate(6);
    }
}

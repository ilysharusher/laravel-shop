<?php

namespace App\Http\Controllers;

use Domain\Catalog\Models\Category;
use Domain\Catalog\ViewModels\CategoryViewModel;
use Domain\Product\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;

class CatalogController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(?Category $category): View
    {
        $categories = CategoryViewModel::make()->catalogPage();
        $products = Product::search(request('search') ?: '')
            ->query(function (Builder $query) use ($category) {
                $query->select('id', 'slug', 'title', 'thumbnail', 'price', 'json_properties')
                    ->withCategory($category)
                    ->filtered()
                    ->sorted();
            })
            ->paginate(6);

        return view('catalog.index', compact('categories', 'products', 'category'));
    }
}

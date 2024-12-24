<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Domain\Catalog\Models\Category;
use Domain\Catalog\ViewModels\BrandViewModel;
use Domain\Catalog\ViewModels\CategoryViewModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;

class CatalogController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(?Category $category): View
    {
        $brands = BrandViewModel::make()->catalogPage();
        $categories = CategoryViewModel::make()->catalogPage();
        $products = Product::search(request('search') ?: '')
            ->query(function (Builder $query) use ($category) {
                $query->select('id', 'slug', 'title', 'thumbnail', 'price')
                    ->when($category?->exists, function (Builder $query) use ($category) {
                        $query->whereRelation('categories', 'categories.id', $category->id);
                    })
                    ->filtered()
                    ->sorted();
            })
            ->paginate(6);

        return view('catalog.index', compact('brands', 'categories', 'products', 'category'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Domain\Catalog\Models\Brand;
use Domain\Catalog\Models\Category;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;

class CatalogController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(?Category $category): View
    {
        // TODO: Implement cache for brands and categories
        $brands = Brand::query()->select('id', 'title')
            ->has('products')->get();
        $categories = Category::query()->select('id', 'title', 'slug')
            ->has('products')->get();
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

<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\ViewedProductService;
use Illuminate\Contracts\View\View;

class ProductController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Product $product): View
    {
        $product->load('optionValues.option');

        ViewedProductService::add($product->id);

        $viewedProducts = Product::query()
            ->whereIn('id', ViewedProductService::get())
            ->where('id', '!=', $product->id)
            ->get();

        $options = $product->optionValues->mapToGroups(fn ($optionValue) => [
            $optionValue->option->title => $optionValue,
        ]);

        return view('product.show', [
            'product' => $product,
            'options' => $options,
            'viewedProducts' => $viewedProducts,
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Services\ViewedProductService;
use Domain\Product\Models\Product;
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

        return view('product.show', [
            'product' => $product,
            'options' => $product->optionValues->keyValues(),
            'viewedProducts' => $viewedProducts,
        ]);
    }
}

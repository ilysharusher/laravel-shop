<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Domain\Catalog\Models\Brand;
use Domain\Catalog\ViewModels\CategoryViewModel;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        $categories = CategoryViewModel::make()->homePage(); // TODO: Add observer for reset cache
        $products = Product::query()->homePage()->get();
        $brands = Brand::query()->homePage()->get();  // TODO: Implement view model for brands with observer for reset cache

        return view('index', compact('categories', 'products', 'brands'));
    }
}

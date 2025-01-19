<?php

namespace App\Http\Controllers;

use Domain\Catalog\ViewModels\BrandViewModel;
use Domain\Catalog\ViewModels\CategoryViewModel;
use Domain\Product\Models\Product;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        // TODO: Implement ViewModel for Home Page

        $categories = CategoryViewModel::make()->homePage();
        $products = Product::query()->homePage()->get();
        $brands = BrandViewModel::make()->homePage();

        return view('index', compact('categories', 'products', 'brands'));
    }
}

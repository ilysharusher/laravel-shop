<?php

namespace App\Http\Controllers;

use App\View\ViewModels\HomeViewModel;

class HomeController extends Controller
{
    public function __invoke(): HomeViewModel
    {
        return new HomeViewModel()
            ->view('index');
    }
}

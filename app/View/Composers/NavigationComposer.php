<?php

namespace App\View\Composers;

use App\Menu\Menu;
use App\Menu\MenuItem;
use Illuminate\View\View;

class NavigationComposer
{
    public function compose(View $view): void
    {
        $menu = Menu::make()
            ->add(MenuItem::make('Home', route('home')))
            ->add(MenuItem::make('Catalog', route('catalog')));

        $view->with('menu', $menu);
    }
}

<?php

namespace App\Services;

class ViewedProductService
{
    public static function get(): array
    {
        return session()?->get('viewedProduct', []);
    }

    public static function add(int $productId, int $limit = 10): void
    {
        $viewedProducts = self::get();
        if (!in_array($productId, $viewedProducts, true)) {
            $viewedProducts[] = $productId;
            if (count($viewedProducts) > $limit) {
                $viewedProducts = array_slice($viewedProducts, -$limit);
            }
            session()?->put('viewedProduct', $viewedProducts);
        }
    }
}

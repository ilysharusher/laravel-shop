<?php

use Domain\Catalog\Filters\FilterManager;
use Domain\Catalog\Models\Category;
use Domain\Catalog\Sorters\Sorter;
use Support\Flash\Flash;

if (!function_exists('flash')) {
    /**
     * Retrieve the Flash instance from the service container.
     *
     * @return \Support\Flash\Flash The Flash instance.
     */
    function flash(): Flash
    {
        return app(Flash::class);
    }
}

if (!function_exists('filters')) {
    /**
     * Retrieve filters from the FilterManager.
     *
     * @return array The array of filters.
     */
    function filters(): array
    {
        return app(FilterManager::class)->filters();
    }
}

if (!function_exists('sorter')) {
    /**
     * Retrieve the Sorter instance.
     *
     * @return \Domain\Catalog\Sorters\Sorter The Sorter instance.
     */
    function sorter(): Sorter
    {
        return app(Sorter::class);
    }
}

if (!function_exists('is_catalog_view')) {
    /**
     * Check if the current catalog view matches the specified view.
     *
     * @param string $view The view to compare against the current catalog view.
     * @param string $default The default view if no view is stored in the session. Default is 'grid'.
     * @return bool True if the current catalog view matches the specified view, false otherwise.
     */
    function is_catalog_view(string $view, string $default = 'grid'): bool
    {
        return session('view', $default) === $view;
    }
}

if (!function_exists('catalog_url')) {
    /**
     * Generate the catalog URL with the specified category and parameters.
     *
     * @param \Domain\Catalog\Models\Category|null $category The category to include in the URL.
     * @param array $params Additional parameters to include in the URL.
     * @return string The generated catalog URL.
     */
    function catalog_url(?Category $category, array $params = []): string
    {
        return route('catalog', [
            'category' => $category,
            ...request()->only('sort', 'filter'),
            ...$params,
        ]);
    }
}

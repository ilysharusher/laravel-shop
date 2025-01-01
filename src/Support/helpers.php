<?php

use Domain\Catalog\Filters\FilterManager;
use Domain\Catalog\Sorters\Sorter;
use Support\Flash\Flash;

if (!function_exists('flash')) {

    /**
     * Retrieve the Flash instance from the service container.
     *
     * This function provides a convenient way to access the Flash instance,
     * which is used to handle flash messages in the application.
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
     * Retrieve the filters from the FilterManager.
     *
     * This function provides a convenient way to access the filters
     * managed by the FilterManager instance from the service container.
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
     * Retrieve the Sorter instance from the service container.
     *
     * This function provides a convenient way to access the Sorter instance,
     * which is used to handle sorting operations in the application.
     *
     * @return \Domain\Catalog\Sorters\Sorter The Sorter instance.
     */
    function sorter(): Sorter
    {
        return app(Sorter::class);
    }
}

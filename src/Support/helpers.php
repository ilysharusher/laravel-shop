<?php

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

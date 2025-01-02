<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CatalogViewMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $view = $request->query('view', $request->cookie('view_mode', 'grid'));

        if ($request->query('view') && $view !== $request->cookie('view_mode')) {
            cookie()->queue('view_mode', $view, 43200);
        }

        view()->share('viewMode', $view);

        return $next($request);
    }
}

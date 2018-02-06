<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RenderMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string
     * @return mixed
     */
    public function handle($request, Closure $next, $model = null)
    {
        $model = $request->route()->parameter($model);
        if (empty($model)) {
            return;
        }
        view()->share([
            'title'       => $model->seoTitle(),
            'keywords'    => $model->keywords ?? config('site.keywords'),
            'description' => $model->description ?? config('site.description'),
            'breadcrumb'  => $model->breadcrumb()
        ]);

        return $next($request);
    }
}

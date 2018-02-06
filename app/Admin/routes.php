<?php

use Illuminate\Routing\Router;

Admin::registerAuthRoutes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {
    $router->get('/', 'HomeController@index');

    $router->resource('categories', CategoryController::class);
    $router->resource('posts', PostController::class);
    $router->resource('tags', TagController::class);
    $router->resource('auth/logs', LogController::class);

    $router->post('posts/publish', 'PostController@publish');
    $router->post('posts/restore', 'PostController@restore');
    $router->post('posts/emptyTrash', 'PostController@emptyTrash');

    $router->post('auth/logs/empty', 'LogController@empty');
});

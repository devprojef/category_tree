<?php
declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
*/

/** @var Laravel\Lumen\Routing\Router $router */

use Illuminate\Support\Facades\File;

$router->get('/', function () use ($router)
{
    return File::get(app()->basePath() . '/public/index.html');
});

$router->group(['prefix' => 'api'], function () use ($router)
{
    $router->post('categories', ['uses' => 'CategoryController@add']);

    $router->get('categories', ['uses' => 'CategoryController@retrieve']);
});

<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['prefix' => 'v1', 'middleware' => ['ApiConfig']], function () use ($router) {
    $router->get('/test', 'ExampleController@get');
    $router->post('/post', 'ExampleController@post');

    # 添加物品接口
    $router->post('/addCard', 'GoodsController@post');

});

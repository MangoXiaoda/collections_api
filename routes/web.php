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

    # 获取卡片列表接口
    $router->post('/cardlist', 'GoodsController@post');

    # 添加卡片信息接口
    $router->post('/addcard', 'GoodsController@post');

    # 修改卡片信息接口
    $router->post('/editcard', 'GoodsController@post');

    # 删除卡片信息接口
    $router->post('/delcard', 'GoodsController@post');

    # 物品列表接口
    $router->post('/goodslist', 'GoodsController@post');

    # 添加物品接口
    $router->post('/addgoods', 'GoodsController@post');

    # 修改物品接口
    $router->post('/editgoods', 'GoodsController@post');

    # 删除物品接口
    $router->post('/delgoods', 'GoodsController@post');


});

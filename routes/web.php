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

    # 获取标签列表接口
    $router->post('/taglist', 'TagController@post');

    # 添加标签接口
    $router->post('/addtag', 'TagController@post');

    # 修改标签接口
    $router->post('/edittag', 'TagController@post');

    # 删除标签接口
    $router->post('/deltag', 'TagController@post');

    # 图片上传接口
    $router->post('/uploadimage', 'GoodsController@post');

    # 获取图片列表接口
    $router->get('/imagelist', 'ImagesController@post');


});

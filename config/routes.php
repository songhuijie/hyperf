<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf-cloud/hyperf/blob/master/LICENSE
 */

use Hyperf\HttpServer\Router\Router;

Router::get('/get', 'App\Controller\IndexController@gets');

Router::get('/home', 'App\Controller\Home\HomeController@index');
Router::get('/admin', 'App\Controller\Home\HomeController@admin');
Router::get('/redis', 'App\Controller\Home\HomeController@redis');
Router::get('/test', 'App\Controller\Home\HomeController@test');

Router::get('/view', 'App\Controller\Home\ViewController@index');
Router::post('/simple-register', 'App\Controller\Home\ViewController@SimpleRegister');
Router::get('/wss', 'App\Controller\Home\ViewController@Ws');

//todo api 登录
Router::post('/user/login', 'App\Controller\Auth\LoginController@login');
Router::post('/user/register', 'App\Controller\Auth\RegisterController@register');

//todo 后台接口
Router::addGroup('/admin',function(){
    Router::addRoute(['GET', 'POST', 'HEAD'], '/', [App\Controller\IndexController::class,'index']);

// 此处代码示例为每个示例都提供了三种不同的绑定定义方式，实际配置时仅可采用一种且仅定义一次相同的路由

// 设置一个 GET 请求的路由，绑定访问地址 '/get' 到 App\Controller\IndexController 的 get 方法
//Router::get('/get', 'App\Controller\IndexController::get');
    //todo 后台登录
    Router::post('/login', [App\Controller\Admin\Auth\LoginController::class,'login']);


    Router::addGroup('', function () {

        Router::get('/info', [App\Controller\Admin\Auth\LoginController::class,'info']);
        //todo 管理员列表
        Router::addGroup('', function () {
            Router::get('index','App\Controller\Admin\AdminController@index');
            Router::post('add','App\Controller\Admin\AdminController@store');
            Router::post('update/{id}','App\Controller\Admin\AdminController@update');
            Router::delete('delete/{id}','App\Controller\Admin\AdminController@delete');
        });

        //todo 类型
        Router::addGroup('/category/', function () {
            Router::get('index','App\Controller\Admin\CategoryController@index');
            Router::post('store','App\Controller\Admin\CategoryController@store');
            Router::patch('update/{id}','App\Controller\Admin\CategoryController@update');
            Router::delete('delete/{id}','App\Controller\Admin\CategoryController@delete');
        });

        //todo  文章
        Router::addGroup('/article/', function () {
            Router::get('index','App\Controller\Admin\ArticleController@index');
            Router::post('store','App\Controller\Admin\ArticleController@store');
            Router::patch('update/{id}','App\Controller\Admin\ArticleController@update');
            Router::delete('delete/{id}','App\Controller\Admin\ArticleController@delete');
        });

//    //todo 角色
//    Router::addGroup('role/',function(){
//        Router::get('index','App\Controller\Admin\Permission\PermissionController@Role');
//        Router::post('add','App\Controller\Admin\Permission\PermissionController@addRole');
//        Router::patch('edit/{id}','App\Controller\Admin\Permission\PermissionController@editRole');
//        Router::delete('del/{id}','App\Controller\Admin\Permission\PermissionController@delRole');
//    });
//
//    //todo 权限
//    Router::addGroup('roles/',function(){
//        Router::get('index','App\Controller\Admin\Permission\PermissionController@AdminRole');
//        Router::post('add','App\Controller\Admin\Permission\PermissionController@addAdminRole');
//        Router::patch('edit/{id}','App\Controller\Admin\Permission\PermissionController@editAdminRole');
//        Router::delete('del/{id}','App\Controller\Admin\Permission\PermissionController@delAdminRole');
//    });
//
//
//    //todo  权限
//    Router::addGroup('permission/',function(){
//        Router::get('index','App\Controller\Admin\Permission\PermissionController@Jurisdiction');
//        Router::post('add','App\Controller\Admin\Permission\PermissionController@addJurisdiction');
//        Router::patch('edit/{id}','App\Controller\Admin\Permission\PermissionController@editJurisdiction');
//    });
//
//    //todo  路由
//    Router::addGroup('rule/',function(){
//        Router::get('index','App\Controller\Admin\Permission\PermissionController@Rule');
//        Router::post('add','App\Controller\Admin\Permission\PermissionController@addRule');
//        Router::patch('edit/{id}','App\Controller\Admin\Permission\PermissionController@editRule');
//        Router::delete('del/{id}','App\Controller\Admin\Permission\PermissionController@delRule');
//    });

    }, [
        'middleware' => [App\Middleware\JwtAdminMiddleware::class]
    ]);

//个人资料
    Router::addGroup('/', function () {

        Router::addGroup('user/', function () {

            Router::get('info','App\Controller\UserController@info');
            Router::post('logout', 'App\Controller\UserController@logout');
            Router::get('elasticsearch', 'App\Controller\UserController@elasticsearch');
        });


        Router::addGroup('test/', function () {
            Router::get('index','App\Controller\Home\TestController@index');
            Router::post('store','App\Controller\Home\TestController@store');
            Router::patch('update/{id}','App\Controller\Home\TestController@update');
            Router::delete('delete/{id}','App\Controller\Home\TestController@delete');
        });

    }, [
        'middleware' => [App\Middleware\JwtAuthMiddleware::class]
    ]);
});



Router::addServer('ws', function () {
    Router::get('/ws', 'App\Controller\WebSocketController');
});

//Router::get('/get', [\App\Controller\IndexController::class, 'get']);

// 设置一个 POST 请求的路由，绑定访问地址 '/post' 到 App\Controller\IndexController 的 post 方法
//Router::post('/post', 'App\Controller\IndexController::post');
//Router::post('/post', 'App\Controller\IndexController@post');
//Router::post('/post', [\App\Controller\IndexController::class, 'post']);
//
//// 设置一个允许 GET、POST 和 HEAD 请求的路由，绑定访问地址 '/multi' 到 App\Controller\IndexController 的 multi 方法
//Router::addRoute(['GET', 'POST', 'HEAD'], '/multi', 'App\Controller\IndexController::multi');
//Router::addRoute(['GET', 'POST', 'HEAD'], '/multi', 'App\Controller\IndexController@multi');
//Router::addRoute(['GET', 'POST', 'HEAD'], '/multi', [\App\Controller\IndexController::class, 'multi']);

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
$router->group(['prefix' => 'auth'] , function() use ($router){
    $router->post('/register', ['uses' => 'AuthController@register']);
    $router->post('/login', ['uses' => 'AuthController@login']);
    $router->post('/logout', ['uses' => 'AuthController@logout']);
});
$router->group(['prefix' => 'profiles'], function() use ($router){
    $router->get('/{user_id}', ['uses' => 'ProfileController@profileView']);
    $router->put('/{user_id}', ['uses' => 'ProfileController@updateProfile']);
});


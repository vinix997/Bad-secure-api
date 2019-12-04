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
//Auth
$router->group(['prefix' => 'auth'] , function() use ($router){
    $router->post('/register', ['uses' => 'AuthController@register']);
    $router->post('/login', ['uses' => 'AuthController@login']);
    $router->post('/logout', ['uses' => 'AuthController@logout']);
});
//Profiles
$router->group(['prefix' => 'profiles'], function() use ($router){
    $router->get('/{user_id}', ['uses' => 'ProfileController@profileView']);
    $router->put('/{user_id}', ['uses' => 'ProfileController@updateProfile']);
});
//Ticket
$router->group(['prefix' => 'tickets'], function() use ($router)
{
   $router->get('/',['uses' => 'TicketController@tickets']);
   $router->get('/{ticket_id}', ['uses' => 'TicketController@ticketDetail']);
});

$router->group(['prefix' => 'transaction'], function() use ($router)
{
    $router->post('/book', ['uses' => 'TransactionController@book']);
    $router->put('/pay', ['uses' => 'TransactionController@pay']);
    $router->put('/cancel', ['uses' => 'TransactionController@cancel']);
    $router->get('/{transaction_id}', ['uses' => 'TransactionController@detail']);
});
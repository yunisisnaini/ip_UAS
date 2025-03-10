<?php

/** @var \Laravel\Lumen\Routing\Router $router */

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

$router->post('/api/iuran', 'IuranController@store');
$router->get('/api/iuran', 'IuranController@index');
$router->get('/api/iuran/{id}', 'IuranController@show');
$router->put('/api/iuran/{id}', 'IuranController@update');
$router->delete('/api/iuran/{id}', 'IuranController@destroy');
$router->get('/api/iuran/tunggakan/{tahun}', 'IuranController@tunggakan');


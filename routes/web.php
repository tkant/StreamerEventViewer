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

$router->group(['prefix' => 'api'], function ($router) {
    $router->get('/auth', 'LoginController@getAuthURL');
    $router->get('/streamer/{streamerName}/{accessToken}', 'TwitchController@pubsubSubscribeToStreamer');
    $router->get('/callback', 'PubSubController@eventCallback');
    $router->post('/callback', 'PubSubController@eventCallback');
});

$router->get('/{route:.*}/', function ()  {
    return view('app');
});
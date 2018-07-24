<?php

/*
  |--------------------------------------------------------------------------
  | Application Api Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register all of the routes for an application api.
  | It is a breeze. Simply tell Lumen the URIs it should respond to
  | and give it the Closure to call when that URI is requested.
  |
 */
Route::group(['middleware' => 'auth'], function($router) {
    $router->get('/', "ApiController@index");

    $router->get('/recipe', "RecipeController@read");
    $router->post('/recipe', "RecipeController@create");
    $router->put('/recipe', "RecipeController@update");
    $router->delete('/recipe', "RecipeController@delete");
});
//user
$router->post('/user', "UserController@create");
$router->post('/auth', "SecurityController@login");

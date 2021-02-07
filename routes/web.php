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

use App\Http\Controllers\NoteController;
use Illuminate\Support\Facades\Route;

Route::get('/info', function () use ($router) {
    return $router->app->version();
});
Route::get('/version', function () use ($router) {
    return $router->app->version();
});

Route::group(["prefix" => "/api"], function () use ($router) {

    Route::get('/note/routes', ['uses'=>'NoteController@fetchRoutes']);

    Route::get('/note/{slug}/tab/{tabid}', ['middleware' => 'auth:read', 'uses'=>'NoteController@fetchTab']);
    Route::get('/note/{slug}/tabs', ['middleware' => 'auth:read', 'uses'=>'NoteController@fetchTabs']);
    Route::get('/note/{slug}', ['middleware' => 'auth:read', 'uses'=>'NoteController@fetch']);

    Route::post('/note', ['uses' => 'NoteController@addNote']);
    Route::post('/note/{slug}/auth', ['uses' => 'NoteController@auth']);
    Route::post('/note/{slug}/tab', ['middleware' => 'auth:write', 'uses'=>'NoteController@addTab']);
    Route::post('/note/{slug}/tabs', ['middleware' => 'auth:write', 'uses'=>'NoteController@addTabs']);

    Route::patch('/note/{slug}/tab/{tabid}', ['middleware' => 'auth:write', 'uses'=>'NoteController@updateTab']);
    Route::patch('/note/{slug}/tab', ['middleware' => 'auth:write', 'uses'=>'NoteController@updateTabs']);
    Route::patch('/note/{slug}', ['middleware' => 'auth:write', 'uses'=>'NoteController@updateNote']);

    Route::delete('/note/{slug}/tab/{tabid}', ['middleware' => 'auth:write', 'uses'=>'NoteController@deleteTab']);
    Route::delete('/note/{slug}', ['middleware' => 'auth:write', 'uses'=>'NoteController@deleteNote']);
});

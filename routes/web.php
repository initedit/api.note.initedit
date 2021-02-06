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

$router->get('/info', function () use ($router) {
    return $router->app->version();
});
$router->get('/version', function () use ($router) {
    return $router->app->version();
});
$router->get('/api/note/routes', "NoteController@fetchRoutes");

$router->get('/api/note/{slug}/tab/{tabid}', "NoteController@fetchTab");
$router->get('/api/note/{slug}/tabs', "NoteController@fetchTabs");
$router->get('/api/note/{slug}', "NoteController@fetch");

$router->post('/api/note/{slug}/auth', "NoteController@auth");
$router->post('/api/note', "NoteController@addNote");
$router->post('/api/note/{slug}/tab', "NoteController@addTab");
$router->post('/api/note/{slug}/tabs', "NoteController@addTabs");

$router->patch('/api/note/{slug}/tab/{tabid}', "NoteController@updateTab");
$router->patch('/api/note/{slug}/tab', "NoteController@updateTabs");
$router->patch('/api/note/{slug}', "NoteController@updateNote");

$router->delete('/api/note/{slug}/tab/{tabid}', "NoteController@deleteTab");
// $router->delete('/api/note/{slug}/tabs', "NoteController@deleteTabs");
$router->delete('/api/note/{slug}', "NoteController@deleteNote");


<?php
/*
* GET routes
*/
Route::get('/', 'PageController@index');
Route::get('/login', 'PageController@login');

Route::get('/t/{tag}', ['middleware' => ['privacy', 'nsfw'], 'uses' => 'PageController@tag']);
Route::get('/t/{tag}/{hash}/{slug}', ['middleware' => ['privacy', 'nsfw'], 'uses' => 'PageController@thread']);
Route::get('/u/{username}', 'PageController@profile');

Route::group(['middleware' => 'auth'], function()
{
	Route::get('/submit', 'PageController@submit');
	Route::get('/logout', 'UserController@logout');
	Route::get('/inbox', 'PageController@inbox');

	Route::get('/t/{tag}/settings', 'PageController@tagSettings');

	Route::post('/t/{tag}/subscribe', 'TagController@subscribe');
	Route::post('/t/{tag}/settings', 'TagController@settings');
});

/*
* POST routes
*/
Route::post('/register', 'UserController@register');
Route::post('/login', 'UserController@login');

Route::group(['middleware' => 'auth'], function()
{
	Route::post('/submit', 'ThreadController@submit');
	Route::post('/comment', 'CommentController@submit');
});
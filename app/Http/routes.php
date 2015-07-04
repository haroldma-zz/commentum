<?php
/*
* GET routes
*/

Route::group(['middleware' => 'auth'], function()
{
	Route::get('/submit', 'PageController@submit');
	Route::get('/logout', 'UserController@logout');
	Route::get('/inbox', 'PageController@inbox');
	Route::get('/inbox/new', 'PageController@inboxNew');
	Route::get('/preferences', 'PageController@preferences');
	Route::get('/u/{username}/saved', 'PageController@saves');
	Route::get('/t/{tag}/settings', 'PageController@tagSettings');

	Route::get('/t/{tag}/{hash}/{slug}/edit', 'PageController@editThread');

	Route::post('/t/{tag}/subscribe', 'TagController@subscribe');
	Route::post('/t/{tag}/settings', 'TagController@settings');
	Route::post('/edit/thread', 'ThreadController@submit');

	// User related AJAX requests
	Route::get('/me/notifications', 'UserController@checkNotifications');

	Route::post('/me/unread/{hashid}', 'UserController@unreadMessage');
	Route::post('/me/save/thread', 'UserController@saveThread');
	Route::post('/me/save/comment', 'UserController@saveComment');
	Route::post('/me/edit/comment', 'CommentController@edit');
	Route::post('/me/pm', 'UserController@pm');
});


Route::get('/', 'PageController@index');
Route::get('/login', 'PageController@login');

Route::get('/t/{tag}', ['middleware' => ['privacy', 'nsfw'], 'uses' => 'PageController@tag']);
Route::get('/t/{tag}/{hash}/{slug}', ['middleware' => ['privacy', 'nsfw'], 'uses' => 'PageController@thread']);
Route::get('/t/{tag}/{hash}/{slug}/{chash}', ['middleware' => ['privacy', 'nsfw'], 'uses' => 'PageController@threadComment']);
Route::get('/u/{username}', 'PageController@profile');

Route::get('/more/{tag}', 'ThreadController@moreSubmissions');

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
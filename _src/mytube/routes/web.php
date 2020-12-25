<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'HomepageController@index');

Auth::routes();

Route::get('/home', 'HomeController@index');

Route::get('/videos/{video}', 'VideoController@show');

Route::get('/search', 'SearchController@index');

Route::get('/channel/{channel}', 'ChannelController@show');

Route::post('/webhook', 'WebhookController@index');

Route::get('/subscriptions/{channel}', 'SubscriptionController@show');

Route::get('/videos/{video}/votes', 'VoteController@index');

Route::get('/videos/{video}/comments', 'CommentController@index');


Route::group(['middleware' => ['auth']], function(){
	Route::get('/account', 'AccountController@show');
	Route::post('/account', 'AccountController@update');

	Route::get('/channel/{channel}/settings', 'ChannelController@edit');
	Route::post('/channel/{channel}/settings', 'ChannelController@update');

	Route::get('/videos', 'VideoController@index');
	Route::post('/videos', 'VideoController@store');

	Route::get('/videos/{video}/edit', 'VideoController@edit');    
	Route::put('/videos/{video}', 'VideoController@update');
	Route::delete('/videos/{video}','VideoController@delete');

	Route::get('/upload', 'UploadController@index');
	Route::post('/upload', 'UploadController@store');

	Route::post('/subscriptions/{channel}', 'SubscriptionController@store');
	Route::delete('/subscriptions/{channel}', 'SubscriptionController@destroy');

	Route::post('/videos/{video}/votes', 'VoteController@store');
	Route::delete('/videos/{video}/votes', 'VoteController@destroy');

	Route::post('/videos/{video}/comments', 'CommentController@store');
	Route::delete('/videos/{video}/comments/{comment}', 'CommentController@destroy');
});
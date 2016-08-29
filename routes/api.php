<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

Route::post('/oauth/access_token', function() {
	return Response::json(Authorizer::issueAccessToken());
});

Route::post('/cadastro', 'UserController@store');

Route::group(['middleware' => 'oauth'], function() {
	Route::resource('post', 'PostController', ['except' => [
		'create', 'edit'
	]]);

	Route::get('lookup', 'UserController@lookup');

	Route::group(['middleware' => 'check.admin'], function() {
		Route::get('user', 'UserController@index');
		Route::get('user/all', 'UserController@indexAll');
		Route::get('user/{user}', 'UserController@show');
		Route::put('user/{user}', 'UserController@update');
		Route::delete('user/{user}', 'UserController@destroy');

		Route::put('user/suspend/{user}', 'UserController@suspend');
	});
});

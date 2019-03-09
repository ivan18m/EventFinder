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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::resource('place', 'PlaceController');
Route::post('place/radius', 'PlaceController@radius');

Route::resource('event', 'EventController');
Route::post('event/radius', 'EventController@radius');
Route::post('place/{place}/event', 'EventController@store')->where('place', '[0-9]+');

Route::resource('comment', 'CommentController');
Route::post('event/{event}/comment', 'CommentController@store')->where('event', '[0-9]+');;

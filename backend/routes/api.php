<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) { return $request->user(); });

Route::post('/v1/register', 'Api\V1\AuthController@register')->name('register');
Route::post('/v1/login', 'Api\V1\AuthController@login')->name('login');
Route::middleware('auth:sanctum')->post('/v1/pusher', function (Request $request) {
    event(new \App\Events\TestEvent($request['text']));
});
Route::middleware('auth:sanctum')->any('/v1/logout', 'Api\V1\AuthController@logout')->name('logout');
Route::middleware('auth:sanctum')->any('/v1/check-login', function (Request $request) {
    return response()->json(['user'=>$request->user()], 200);
});

Route::get('/t', function () { return 't'; });
Route::get('/v1/t', function () { return 't-v1'; });

Route::get('/test-event', function () {
    event(new \App\Events\TestEvent('test-event'));
});


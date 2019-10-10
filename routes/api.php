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
//Route::group([
//    'prefix' => 'auth'
//], function () {
//    Route::post('login', 'Auth\LoginController@login');
//});

//Route::apiResources(['user'=> 'API\UserApiController']);
//Route::post('login', 'Auth\LoginController@login');
//Route::post('login', 'API\UserApiController@login');


//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    Route::resource('user', 'UserApiController@index');
//});


//


//
Route::middleware('auth:api')->group(function () {


    Route::resource('user', 'API\UserApiController');
    Route::get('profile','API\ProfileApiController@show');
    Route::get('findUser','API\UserApiController@search');
    Route::put('profile','API\ProfileApiController@update');

});
//



//Route::group([
//    'prefix' => 'auth:api'
//], function () {
//    Route::apiResources(['user'=> 'API\UserApiController']);
//});


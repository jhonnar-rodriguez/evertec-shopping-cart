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

Route::group([ 'namespace' => 'API' ], function () {

    /**
     * Access Routes
     */
    Route::group([ 'namespace' => 'Access', 'as' => 'access.' ], function ()
    {
        Route::group([ 'namespace' => 'User', 'as' => 'user.' ], function ()
        {
            Route::post('/login', 'UserController@login')->name('login' );
        });
    });
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

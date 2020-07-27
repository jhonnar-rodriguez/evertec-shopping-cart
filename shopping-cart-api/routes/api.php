<?php

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

Route::group([ 'namespace' => 'API' ], function ()
{
    /**
     * Access Routes
     */
    Route::group([ 'namespace' => 'Access', 'as' => 'access.' ], function ()
    {
        Route::group([ 'namespace' => 'User', 'as' => 'user.' ], function ()
        {
            Route::post('/login', 'UserController@login')->name('login' );

            Route::group( [ 'middleware' => [ 'auth:api' ] ], function ()
            {
                Route::post('/logout', 'UserController@logout' )->name('logout' );
            });
        });
    });

    /**
     * Business Routes - Protected
     */
    Route::group([
        'namespace'     => 'Business',
        'as'            => 'business.',
        'middleware'    => [ 'auth:api' ],
        'prefix'        => 'business',
    ], function ()
    {
        # Product Routes
        Route::group([ 'namespace' => 'Product', 'as' => 'products.', 'prefix' => 'products', ], function ()
        {
            Route::get('/', 'ProductController@getAll')->name('index' );
            Route::get('/{product}', 'ProductController@getProduct')->name('get' );
            Route::get('/{slug}/get', 'ProductController@getProductsBySlug')->name('find-by-slug' );
        });

        # Cart Routes
        Route::group([ 'namespace' => 'Cart', 'as' => 'cart.', 'prefix' => 'cart', ], function ()
        {
            Route::get('/', 'CartController@getContent')->name('content' );
            Route::get('/get-total', 'CartController@getTotal')->name('total' );
            Route::post('/{product}/add', 'CartController@addProductToCart')->name('add' );
            Route::delete('/{product}', 'CartController@removeProductFromCart')->name('remove' );
            Route::post('/clear-content', 'CartController@clearCartContent')
                ->name('clear-content' );
        });

        # Orders Routes
        Route::group([ 'namespace' => 'Order', 'as' => 'orders.', 'prefix' => 'orders', ], function ()
        {
            Route::get( '/', 'OrderController@getAll' )->name('get-all' );
            Route::get( '/{order}', 'OrderController@get' )->name('get' );
            Route::post( '/{cart}', 'OrderController@create' )->name('create' );
        });

    });
});

<?php

use App\Models\Access\User\User;
use App\Models\Business\Cart\Cart;
use App\Models\Business\CartItem\CartItem;
use App\Models\Business\Product\Product;

return [

    'access' => [
        'users' => [
            'model' => User::class,
            'table' => 'users',
        ],
    ],

    'core' => [
        'products' => [
            'model' => Product::class,
            'table' => 'products',
        ],
        'carts' => [
            'model' => Cart::class,
            'table' => 'carts',
        ],
        'cart_items' => [
            'model' => CartItem::class,
            'table' => 'cart_items',
        ],
    ],

    'http_responses' => [

        'success' => [
            'text'  => 'Success',
            'code'  => 200,
        ],

        'created' => [
            'text'  => 'Created',
            'code'  => 201,
        ],

        'unauthorized' => [
            'text'  => 'Unauthorized',
            'code'  => 401,
        ],

        'server_error' => [
            'text'  => 'Server_Error',
            'code'  => 500,
        ],

        'not_found' => [
            'text'  => 'NOT_FOUND',
            'code'  => 404,
        ],

        'unprocessable_entity' => [
            'text'  => 'UNPROCESSABLE_ENTITY',
            'code'  => 422,
        ],

    ],

];

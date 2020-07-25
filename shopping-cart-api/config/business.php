<?php

use App\Models\Access\User\User;

return [

    'access' => [
        'users' => [
            'model' => User::class,
            'table' => 'users',
        ],
    ],

    'http_responses' => [

        'success' => [
            'text'  => 'Success',
            'code'  => 200
        ],

        'unauthorized' => [
            'text'  => 'Unauthorized',
            'code'  => 401
        ],

        'server_error' => [
            'text'  => 'Server_Error',
            'code'  => 500
        ],

    ],

];

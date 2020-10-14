<?php

namespace YOOtheme;

return [

    'aliases' => [
        Encrypter::class => 'encrypter',
    ],

    'services' => [

        Encrypter::class => [
            'class' => Encryption\Encrypter::class,
            'arguments' => [
                '$salt' => $app->wrap(Config::class, ['session.token']),
                '$password' => $app->wrap(Config::class, ['app.secret']),
            ],
        ],

    ],

];

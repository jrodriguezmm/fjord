<?php

namespace YOOtheme;

return [

    'routes' => [
        ['get', '/theme/image', ImageController::class . '@get', ['allowed' => true, 'save' => true]],
    ],

    'aliases' => [
        ImageProvider::class => 'image',
    ],

    'services' => [

        ImageProvider::class => [
            'arguments' => [
                '$cache' => $app->wrap(Config::class, ['image.cacheDir']),
                '$config' => function () use ($app) {
                    return ['route' => 'theme/image', 'secret' => $app->config->get('app.secret')];
                },
            ],
        ],

    ],

];

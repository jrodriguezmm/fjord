<?php

namespace YOOtheme\Theme;

return [

    'theme' => [
        'defaults' => ['lazyload' => true],
    ],

    'routes' => [
        ['get', '/cache', [CacheController::class, 'index']],
        ['post', '/cache/clear', [CacheController::class, 'clear']],
        ['get', '/systemcheck', [SystemCheckController::class, 'index']],
    ],

    'events' => [

        'theme.head' => [
            SettingsListener::class => 'initHead',
        ],

    ],

];

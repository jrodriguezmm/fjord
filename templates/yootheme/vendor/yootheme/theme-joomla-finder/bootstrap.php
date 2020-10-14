<?php

namespace YOOtheme\Theme\Joomla;

return [

    'routes' => [
        ['get', '/finder', [FinderController::class, 'index']],
        ['post', '/finder/rename', [FinderController::class, 'rename']],
    ],

    'events' => [

        'customizer.init' => [
            FinderListener::class => 'initCustomizer',
        ],

    ],

];

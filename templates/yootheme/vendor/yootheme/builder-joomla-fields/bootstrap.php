<?php

namespace YOOtheme\Builder\Joomla\Fields;

return [

    'events' => [

        'source.init' => [
            SourceListener::class => ['initSource', -10],
        ],

    ],

];

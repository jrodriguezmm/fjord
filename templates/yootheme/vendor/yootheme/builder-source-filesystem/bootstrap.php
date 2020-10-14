<?php

namespace YOOtheme\Builder\Source\Filesystem;

return [

    'events' => [

        'source.init' => [
            SourceListener::class => ['initSource', -5], // -5 to show the 'External' Group after the 'Custom' Group
        ],

    ],

];

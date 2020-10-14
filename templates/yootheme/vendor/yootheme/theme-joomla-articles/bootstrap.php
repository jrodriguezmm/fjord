<?php

namespace YOOtheme\Theme\Joomla;

return [

    'actions' => [

        'onContentBeforeSave' => [
            ArticlesListener::class => 'beforeSave',
        ],

        'onContentPrepareData' => [
            ArticlesListener::class => 'prepareData',
        ],

    ],

];

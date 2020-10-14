<?php

namespace YOOtheme\Builder\Joomla\Source;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use YOOtheme\Builder;
use YOOtheme\Builder\Source\SourceTransform;
use YOOtheme\Path;

return [

    'routes' => [
        ['get', '/joomla/articles', [SourceController::class, 'articles']],
    ],

    'events' => [

        'source.init' => [
            SourceListener::class => 'initSource',
        ],

        'customizer.init' => [
            SourceListener::class => ['initCustomizer', 10],
        ],

        'builder.template' => [
            TemplateListener::class => 'matchTemplate',
        ],

    ],

    'actions' => [

        'onLoadTemplate' => [
            TemplateListener::class => 'loadTemplate',
        ],

        'onAfterDispatch' => [
            TemplateListener::class => 'afterDispatch',
        ],

    ],

    'extend' => [

        Builder::class => function (Builder $builder) {
            $builder->addTypePath(Path::get('./elements/*/element.json'));
        },

        SourceTransform::class => function (SourceTransform $transform, $app) {

            $transform->addFilter('date', function ($value, $format) {
                return HTMLHelper::_('date', $value, $format ?: Text::_('DATE_FORMAT_LC3'));
            });

        },

    ],

];

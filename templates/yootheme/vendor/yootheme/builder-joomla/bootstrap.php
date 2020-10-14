<?php

namespace YOOtheme;

use YOOtheme\Builder\Joomla\BuilderController;
use YOOtheme\Builder\Joomla\ContentListener;
use YOOtheme\Builder\UpdateTransform;

return [

    'routes' => [

        ['post', '/page', ContentListener::class . '@savePage'],
        ['post', '/builder/image', [BuilderController::class, 'loadImage']],

    ],

    'actions' => [

        'onAfterRoute' => [
            ContentListener::class => '@afterRoute',
        ],

        'onAfterDispatch' => [
            ContentListener::class => '@afterDispatch',
        ],

        'onContentPrepare' => [
            ContentListener::class => '@prepareContent',
        ],

    ],

    'extend' => [

        View::class => function (View $view) {

            $view->addLoader(function ($name, $parameters, callable $next) {

                $content = $next($name, $parameters);

                return empty($parameters['prefix']) || $parameters['prefix'] !== 'page' ? \JHtmlContent::prepare($content) : $content;

            }, '*/builder/elements/layout/templates/template.php');

        },

        Builder::class => function (Builder $builder, $app) {

            $builder->addTypePath(Path::get('./elements/*/element.json'));

            if ($childDir = $app->config->get('theme.childDir')) {
                $builder->addTypePath("{$childDir}/builder/*/element.json");
            }

        },

        UpdateTransform::class => function (UpdateTransform $update) {
            $update->addGlobals(require __DIR__ . '/updates.php');
        },

    ],

    'services' => [

        ContentListener::class => '',

    ],

];

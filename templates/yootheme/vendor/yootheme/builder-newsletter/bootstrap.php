<?php

namespace YOOtheme;

use YOOtheme\Builder\Newsletter\CampaignMonitorProvider;
use YOOtheme\Builder\Newsletter\MailChimpProvider;
use YOOtheme\Builder\Newsletter\NewsletterController;

return [

    'theme' => [

        'newsletterProvider' => [
            'mailchimp' => MailChimpProvider::class,
            'cmonitor' => CampaignMonitorProvider::class,
        ],

    ],

    'routes' => [
        ['post', '/theme/newsletter/list', NewsletterController::class . '@lists'],
        ['post', '/theme/newsletter/subscribe', NewsletterController::class . '@subscribe', ['csrf' => false, 'allowed' => true]],
    ],

    'extend' => [

        Builder::class => function (Builder $builder) {
            $builder->addTypePath(Path::get('./elements/*/element.json'));
        },

    ],

    'services' => [

        MailChimpProvider::class => [
            'arguments' => ['$apiKey' => $app->wrap(Config::class, ['~theme.mailchimp_api'])],
        ],

        CampaignMonitorProvider::class => [
            'arguments' => ['$apiKey' => $app->wrap(Config::class, ['~theme.cmonitor_api'])],
        ],

    ],

];

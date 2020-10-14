<?php

namespace YOOtheme;

use Joomla\CMS\Application\CMSApplication;
use Joomla\CMS\Application\SiteApplication;
use Joomla\CMS\Document\Document;
use Joomla\CMS\Document\HtmlDocument;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Language;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\CMS\Session\Session;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\User\User;
use Joomla\Input\Input;
use YOOtheme\Joomla\ActionLoader;
use YOOtheme\Joomla\Platform;
use YOOtheme\Joomla\Router;

Url::setBase(Uri::root(true));
Path::setAlias('~', JPATH_ROOT);

return [

    'config' => function () {

        $registry = Factory::getConfig();
        $language = Factory::getLanguage();
        $application = Factory::getApplication();

        // get apikey from plugin
        if ($plugin = PluginHelper::getPlugin('installer', 'yootheme')) {
            $params = json_decode($plugin->params);
        }

        return [

            'app' => [
                'platform' => 'joomla',
                'version' => JVERSION,
                'secret' => $registry->get('secret'),
                'debug' => (bool) $registry->get('debug'),
                'rootDir' => strtr(JPATH_ROOT, '\\', '/'),
                'tempDir' => strtr($registry->get('tmp_path', JPATH_ROOT . '/tmp'), '\\', '/'),
                'cacheDir' => strtr($registry->get('cache_path', JPATH_ROOT . '/cache'), '\\', '/'),
                'adminDir' => strtr(JPATH_ADMINISTRATOR, '\\', '/'),
                'isSite' => $application->isClient('site'),
                'isAdmin' => $application->isClient('administrator'),
                'apikey' => isset($params->apikey) ? $params->apikey : '',
            ],

            'req' => [
                'baseUrl' => Uri::base(true),
                'rootUrl' => Uri::root(true),
                'siteUrl' => rtrim(Uri::root(), '/'),
            ],

            'locale' => [
                'rtl' => (bool) $language->get('rtl'),
                'code' => strtr($language->get('tag'), '-', '_'),
            ],

            'session' => [
                'token' => Session::getFormToken(),
            ],

            'joomla' => [
                'config' => $registry,
            ],

            'user' => Factory::getUser(),

        ];

    },

    'events' => [

        'url.route' => [
            Router::class => 'generate',
        ],

        'app.error' => [
            Platform::class => 'handleError',
        ],

    ],

    'actions' => [

        'onAfterRoute' => [
            Platform::class => ['handleRoute', -50],
        ],

        'onBeforeCompileHead' => [
            Platform::class => ['registerAssets', -50],
        ],

    ],

    'loaders' => [

        'actions' => new ActionLoader(),

    ],

    'aliases' => [

        Document::class => HtmlDocument::class,
        CMSApplication::class => SiteApplication::class,

    ],

    'services' => [

        Database::class => [
            'class' => Joomla\Database::class,
            'arguments' => ['$db' => Factory::getDBO()],
        ],

        CsrfMiddleware::class => [
            'arguments' => ['$token' => $app->wrap(Config::class, ['session.token'])],
        ],

        Storage::class => Joomla\Storage::class,

        HttpClientInterface::class => Joomla\HttpClient::class,

        CMSApplication::class => [
            'factory' => [Factory::class, 'getApplication'],
        ],

        Document::class => [
            'factory' => [Factory::class, 'getDocument'],
        ],

        Language::class => [
            'factory' => [Factory::class, 'getLanguage'],
        ],

        Session::class => [
            'factory' => [Factory::class, 'getSession'],
        ],

        User::class => [
            'factory' => [Factory::class, 'getUser'],
        ],

        Input::class => function (CMSApplication $application) {
            return $application->input;
        },

    ],

];

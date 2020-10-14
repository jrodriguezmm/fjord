<?php

namespace YOOtheme\Builder\Joomla\Source\Type;

use Joomla\CMS\Factory;
use Joomla\CMS\Plugin\PluginHelper;

class ArticleEventType
{
    /**
     * @return array
     */
    public static function config()
    {
        return [

            'fields' => [

                'afterDisplayTitle' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'After Display Title',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::resolve',
                    ],
                ],

                'beforeDisplayContent' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Before Display Content',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::resolve',
                    ],
                ],

                'afterDisplayContent' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'After Display Content',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::resolve',
                    ],
                ],

            ],

            'metadata' => [
                'label' => 'Events',
            ],

        ];
    }

    public static function resolve($article, $args, $context, $info)
    {
        $key = $info->fieldName;

        if (isset($article->event->$key)) {
            return $article->event->$key;
        }

        $marker = "<!-- article_{$article->id}_{$key} -->";

        \JEventDispatcher::getInstance()->register('onBeforeRender', function () use ($article, $key, $marker) {

            if (!isset($article->event->$key)) {
                static::applyContentPlugins($article);
            }

            $document = Factory::getDocument();
            $document->setBuffer(str_replace($marker, $article->event->$key, $document->getBuffer('component')), 'component');
        });

        return $marker;
    }

    protected static function applyContentPlugins($article)
    {
        $dispatcher = \JEventDispatcher::getInstance();

        // Process the content plugins.
        PluginHelper::importPlugin('content');

        $article->event = new \stdClass();

        $results = $dispatcher->trigger('onContentAfterTitle', ['com_content.article', &$article, &$article->params]);
        $article->event->afterDisplayTitle = trim(implode("\n", $results));

        $results = $dispatcher->trigger('onContentBeforeDisplay', ['com_content.article', &$article, &$article->params]);
        $article->event->beforeDisplayContent = trim(implode("\n", $results));

        $results = $dispatcher->trigger('onContentAfterDisplay', ['com_content.article', &$article, &$article->params]);
        $article->event->afterDisplayContent = trim(implode("\n", $results));
    }
}

<?php

namespace YOOtheme\Builder\Joomla\Source\Type;

use Joomla\Component\Tags\Site\Helper\RouteHelper;

class TagType
{
    /**
     * @return array
     */
    public static function config()
    {
        return [

            'fields' => [

                'title' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Title',
                        'filters' => ['limit'],
                    ],
                ],

                'description' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Description',
                        'filters' => ['limit'],
                    ],
                ],

                'hits' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Hits',
                    ],
                ],

                'link' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Link',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::link',
                    ],
                ],

            ],

            'metadata' => [
                'type' => true,
                'label' => 'Tag',
            ],

        ];
    }

    public static function link($tag)
    {
        return RouteHelper::getTagRoute("{$tag->tag_id}:{$tag->alias}");
    }
}

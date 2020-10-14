<?php

namespace YOOtheme\Builder\Joomla\Source\Type;

class ArticleQueryType
{
    /**
     * @return array
     */
    public static function config()
    {
        return [

            'fields' => [

                'article' => [
                    'type' => 'Article',
                    'metadata' => [
                        'label' => 'Article',
                        'view' => ['com_content.article'],
                        'group' => 'Page',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::resolve',
                    ],
                ],

            ],

        ];
    }

    public static function resolve($root)
    {
        if (isset($root['article'])) {
            return $root['article'];
        }

        if (isset($root['item'])) {
            return $root['item'];
        }
    }
}

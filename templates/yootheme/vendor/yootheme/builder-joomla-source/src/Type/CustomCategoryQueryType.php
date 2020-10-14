<?php

namespace YOOtheme\Builder\Joomla\Source\Type;

use Joomla\CMS\Categories\Categories;

class CustomCategoryQueryType
{
    /**
     * @return array
     */
    public static function config()
    {
        return [

            'fields' => [

                'customCategory' => [

                    'type' => 'Category',

                    'args' => [
                        'id' => [
                            'type' => 'String',
                        ],
                    ],

                    'metadata' => [
                        'label' => 'Custom Category',
                        'group' => 'Custom',
                        'fields' => [
                            'id' => [
                                'label' => 'Category',
                                'type' => 'select-category',
                            ],
                        ],
                    ],

                    'extensions' => [
                        'call' => __CLASS__ . '::resolve',
                    ],

                ],

            ],

        ];
    }

    public static function resolve($root, array $args)
    {
        return Categories::getInstance('content', ['countItems' => true])->get($args['id']);
    }
}

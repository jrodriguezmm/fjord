<?php

namespace YOOtheme\Builder\Joomla\Source\Type;

class CategoryQueryType
{
    /**
     * @return array
     */
    public static function config()
    {
        return [

            'fields' => [

                'category' => [
                    'type' => 'Category',
                    'metadata' => [
                        'label' => 'Category',
                        'view' => ['com_content.category'],
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
        if (isset($root['category'])) {
            return $root['category'];
        }
    }
}

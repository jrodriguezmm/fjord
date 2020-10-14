<?php

namespace YOOtheme\Builder\Joomla\Source\Type;

class CategoryParamsType
{
    /**
     * @return array
     */
    public static function config()
    {
        return [

            'fields' => [

                'image' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Image',
                    ],
                ],

                'image_alt' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Image Alt',
                        'filters' => ['limit'],
                    ],
                ],

            ],

        ];
    }
}

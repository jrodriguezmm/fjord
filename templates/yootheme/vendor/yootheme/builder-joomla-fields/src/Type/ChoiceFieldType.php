<?php

namespace YOOtheme\Builder\Joomla\Fields\Type;

class ChoiceFieldType
{
    /**
     * @return array
     */
    public static function config()
    {
        return [

            'fields' => [

                'name' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Name',
                    ],
                ],

                'value' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Value',
                    ],
                ],

            ],

        ];
    }
}

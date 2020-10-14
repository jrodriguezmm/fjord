<?php

namespace YOOtheme\Builder\Joomla\Fields\Type;

class SqlFieldType
{
    /**
     * @return array
     */
    public static function config()
    {
        return [

            'fields' => [

                'text' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Text',
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

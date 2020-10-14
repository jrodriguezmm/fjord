<?php

namespace YOOtheme\Builder\Joomla\Fields\Type;

class ValueFieldType
{
    /**
     * @return array
     */
    public static function config()
    {
        return [

            'fields' => [

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

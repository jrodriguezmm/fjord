<?php

namespace YOOtheme\Builder\Joomla\Source\Type;

class ArticleImagesType
{
    /**
     * @return array
     */
    public static function config()
    {
        return [

            'fields' => [

                'image_intro' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Intro Image',
                    ],
                ],

                'image_intro_alt' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Intro Image Alt',
                        'filters' => ['limit'],
                    ],
                ],

                'image_intro_caption' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Intro Image Caption',
                        'filters' => ['limit'],
                    ],
                ],

                'image_fulltext' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Full Article Image',
                    ],
                ],

                'image_fulltext_alt' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Full Article Image Alt',
                        'filters' => ['limit'],
                    ],
                ],

                'image_fulltext_caption' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Full Article Image Caption',
                        'filters' => ['limit'],
                    ],
                ],

            ],

        ];
    }
}

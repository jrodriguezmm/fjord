<?php

namespace YOOtheme\Builder\Joomla\Source\Type;

use YOOtheme\Builder\Joomla\Source\ArticleHelper;

class CustomArticleQueryType
{
    /**
     * @return array
     */
    public static function config()
    {
        return [

            'fields' => [

                'customArticle' => [

                    'type' => 'Article',

                    'args' => [
                        'id' => [
                            'type' => 'String',
                        ],
                        'catid' => [
                            'type' => [
                                'listOf' => 'String',
                            ],
                        ],
                        'tags' => [
                            'type' => [
                                'listOf' => 'String',
                            ],
                        ],
                        'featured' => [
                            'type' => 'Boolean',
                        ],
                        'offset' => [
                            'type' => 'Int',
                        ],
                        'order' => [
                            'type' => 'String',
                        ],
                        'order_direction' => [
                            'type' => 'String',
                        ],
                        'order_alphanum' => [
                            'type' => 'Boolean',
                        ],
                    ],

                    'metadata' => [
                        'label' => 'Custom Article',
                        'group' => 'Custom',
                        'fields' => [
                            'id' => [
                                'label' => 'Select Manually',
                                'description' => 'Pick an article manually or use filter options to specify which article should be loaded dynamically.',
                                'type' => 'select-item',
                                'labels' => ['type' => 'Article'],
                            ],
                            'catid' => [
                                'label' => 'Limit by Categories',
                                'description' => 'The article is only loaded from the selected categories. Articles from child categories are not included. Use the <kbd>shift</kbd> or <kbd>ctrl/cmd</kbd> key to select multiple categories.',
                                'type' => 'select-category',
                                'default' => [],
                                'attrs' => [
                                    'multiple' => true,
                                    'class' => 'uk-height-small uk-resize-vertical',
                                ],
                                'enable' => '!id',
                            ],
                            'tags' => [
                                'label' => 'Limit by Tags',
                                'description' => 'The article is only loaded from the selected tags. Use the <kbd>shift</kbd> or <kbd>ctrl/cmd</kbd> key to select multiple tags.',
                                'type' => 'select-tag',
                                'default' => [],
                                'attrs' => [
                                    'multiple' => true,
                                    'class' => 'uk-height-small uk-resize-vertical',
                                ],
                                'enable' => '!id',
                            ],
                            'featured' => [
                                'label' => 'Limit by Featured Articles',
                                'type' => 'checkbox',
                                'text' => 'Load featured articles only',
                                'enable' => '!id',
                            ],
                            'offset' => [
                                'label' => 'Offset',
                                'description' => 'Set the offset to specify which article is loaded.',
                                'type' => 'number',
                                'default' => 0,
                                'modifier' => 1,
                                'attrs' => [
                                    'min' => 1,
                                    'required' => true,
                                ],
                                'enable' => '!id',
                            ],
                            '_order' => [
                                'type' => 'grid',
                                'width' => '1-2',
                                'fields' => [
                                    'order' => [
                                        'label' => 'Order',
                                        'type' => 'select',
                                        'default' => 'publish_up',
                                        'options' => [
                                            'Published' => 'publish_up',
                                            'Created' => 'created',
                                            'Modified' => 'modified',
                                            'Alphabetical' => 'title',
                                            'Hits' => 'hits',
                                            'Article Order' => 'ordering',
                                            'Featured Articles Order' => 'front',
                                            'Random' => 'rand',
                                        ],
                                        'enable' => '!id',
                                    ],
                                    'order_direction' => [
                                        'label' => 'Direction',
                                        'type' => 'select',
                                        'default' => 'DESC',
                                        'options' => [
                                            'Ascending' => 'ASC',
                                            'Descending' => 'DESC',
                                        ],
                                        'enable' => '!id',
                                    ],
                                ],
                            ],
                            'order_alphanum' => [
                                'text' => 'Alphanumeric Ordering',
                                'type' => 'checkbox',
                                'enable' => '!id',
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
        $args += ['id' => 0, 'limit' => 1];

        if (!empty($args['id'])) {
            $articles = ArticleHelper::get($args['id']);
        } else {
            $articles = ArticleHelper::query($args);
        }

        return array_shift($articles);
    }
}

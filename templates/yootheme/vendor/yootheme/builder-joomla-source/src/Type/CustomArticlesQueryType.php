<?php

namespace YOOtheme\Builder\Joomla\Source\Type;

use YOOtheme\Builder\Joomla\Source\ArticleHelper;

class CustomArticlesQueryType
{
    /**
     * @return array
     */
    public static function config()
    {
        return [

            'fields' => [

                'customArticles' => [

                    'type' => [
                        'listOf' => 'Article',
                    ],

                    'args' => [
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
                        'limit' => [
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
                        'label' => 'Custom Articles',
                        'group' => 'Custom',
                        'fields' => [
                            'catid' => [
                                'label' => 'Limit by Categories',
                                'description' => 'Articles are only loaded from the selected categories. Articles from child categories are not included. Use the <kbd>shift</kbd> or <kbd>ctrl/cmd</kbd> key to select multiple categories.',
                                'type' => 'select-category',
                                'default' => [],
                                'attrs' => [
                                    'multiple' => true,
                                    'class' => 'uk-height-small uk-resize-vertical',
                                ],
                            ],
                            'tags' => [
                                'label' => 'Limit by Tags',
                                'description' => 'Articles are only loaded from the selected tags. Use the <kbd>shift</kbd> or <kbd>ctrl/cmd</kbd> key to select multiple tags.',
                                'type' => 'select-tag',
                                'default' => [],
                                'attrs' => [
                                    'multiple' => true,
                                    'class' => 'uk-height-small uk-resize-vertical',
                                ],
                            ],
                            'featured' => [
                                'label' => 'Limit by Featured Articles',
                                'type' => 'checkbox',
                                'text' => 'Load featured articles only',
                            ],
                            '_offset' => [
                                'description' => 'Set the starting point and limit the number of articles.',
                                'type' => 'grid',
                                'width' => '1-2',
                                'fields' => [
                                    'offset' => [
                                        'label' => 'Start',
                                        'type' => 'number',
                                        'default' => 0,
                                        'modifier' => 1,
                                        'attrs' => [
                                            'min' => 1,
                                            'required' => true,
                                        ],
                                    ],
                                    'limit' => [
                                        'label' => 'Quantity',
                                        'type' => 'limit',
                                        'default' => 10,
                                        'attrs' => [
                                            'min' => 1,
                                        ],
                                    ],
                                ],
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
                                    ],
                                    'order_direction' => [
                                        'label' => 'Direction',
                                        'type' => 'select',
                                        'default' => 'DESC',
                                        'options' => [
                                            'Ascending' => 'ASC',
                                            'Descending' => 'DESC',
                                        ],
                                    ],
                                ],
                            ],
                            'order_alphanum' => [
                                'text' => 'Alphanumeric Ordering',
                                'type' => 'checkbox',
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
        return ArticleHelper::query($args);
    }
}

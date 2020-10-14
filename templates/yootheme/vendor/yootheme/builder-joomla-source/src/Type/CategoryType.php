<?php

namespace YOOtheme\Builder\Joomla\Source\Type;

use Joomla\CMS\Categories\CategoryNode;
use Joomla\CMS\Helper\TagsHelper;
use Joomla\Component\Content\Site\Helper\RouteHelper;
use function YOOtheme\app;
use YOOtheme\Path;
use YOOtheme\View;

class CategoryType
{
    /**
     * @return array
     */
    public static function config()
    {
        return [

            'fields' => [

                'title' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Title',
                        'filters' => ['limit'],
                    ],
                ],

                'description' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Description',
                        'filters' => ['limit'],
                    ],
                ],

                'numitems' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Items Count',
                    ],
                ],

                'params' => [
                    'type' => 'CategoryParams',
                    'metadata' => [
                        'label' => '',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::params',
                    ],
                ],

                'link' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Link',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::link',
                    ],
                ],

                'tagString' => [
                    'type' => 'String',
                    'args' => [
                        'separator' => [
                            'type' => 'String',
                        ],
                        'show_link' => [
                            'type' => 'Boolean',
                        ],
                        'link_style' => [
                            'type' => 'String',
                        ],
                    ],
                    'metadata' => [
                        'label' => 'Tags',
                        'arguments' => [

                            'separator' => [
                                'label' => 'Separator',
                                'description' => 'Set the separator between tags.',
                                'default' => ', ',
                            ],
                            'show_link' => [
                                'label' => 'Link',
                                'type' => 'checkbox',
                                'default' => true,
                                'text' => 'Show link',
                            ],
                            'link_style' => [
                                'label' => 'Link Style',
                                'description' => 'Set the link style.',
                                'type' => 'select',
                                'default' => '',
                                'options' => [
                                    'Default' => '',
                                    'Muted' => 'link-muted',
                                    'Text' => 'link-text',
                                    'Heading' => 'link-heading',
                                    'Reset' => 'link-reset',
                                ],
                                'enable' => 'arguments.show_link',
                            ],

                        ],
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::tagString',
                    ],
                ],

                'parent' => [
                    'type' => 'Category',
                    'metadata' => [
                        'label' => 'Parent Category',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::parent',
                    ],
                ],

                'categories' => [
                    'type' => [
                        'listOf' => 'Category',
                    ],
                    'metadata' => [
                        'label' => 'Child Categories',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::categories',
                    ],
                ],

                'tags' => [
                    'type' => [
                        'listOf' => 'Tag',
                    ],
                    'metadata' => [
                        'label' => 'Tags',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::tags',
                    ],
                ],

            ],

            'metadata' => [
                'type' => true,
                'label' => 'Category',
            ],

        ];
    }

    public static function params($category)
    {
        return is_string($category->params) ? json_decode($category->params) : $category->params;
    }

    public static function link($category)
    {
        return RouteHelper::getCategoryRoute($category->id, $category->language);
    }

    /**
     * @param CategoryNode $category
     *
     * @return CategoryNode
     */
    public static function parent($category)
    {
        return $category->getParent();
    }

    /**
     * @param CategoryNode $category
     *
     * @return CategoryNode[]
     */
    public static function categories($category)
    {
        return $category->getChildren();
    }

    public static function tags($category)
    {
        if (!isset($category->tags)) {
            return (new TagsHelper())->getItemTags('com_content.category', $category->id);
        }

        return $category->tags->itemTags;
    }

    public static function tagString($category, array $args)
    {
        $tags = static::tags($category);
        $args += [
            'separator' => ', ',
            'show_link' => true,
            'link_style' => '',
        ];

        return app(View::class)->render(Path::get('../../templates/tags'), compact('category', 'tags', 'args'));
    }
}

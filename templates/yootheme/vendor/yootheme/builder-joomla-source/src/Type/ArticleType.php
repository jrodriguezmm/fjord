<?php

namespace YOOtheme\Builder\Joomla\Source\Type;

use Joomla\CMS\Categories\Categories;
use Joomla\CMS\Factory;
use Joomla\CMS\Helper\TagsHelper;
use Joomla\Component\Content\Site\Helper\RouteHelper;
use function YOOtheme\app;
use YOOtheme\Path;
use YOOtheme\View;

class ArticleType
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

                'content' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Content',
                        'filters' => ['limit'],
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::content',
                    ],
                ],

                'teaser' => [
                    'type' => 'String',
                    'args' => [
                        'show_excerpt' => [
                            'type' => 'Boolean',
                        ],
                    ],
                    'metadata' => [
                        'label' => 'Teaser',
                        'arguments' => [
                            'show_excerpt' => [
                                'label' => 'Excerpt',
                                'description' => 'Display the excerpt field if has content, otherwise the intro text. To use an excerpt field, create a custom field with the name excerpt.',
                                'type' => 'checkbox',
                                'default' => true,
                                'text' => 'Prefer excerpt over intro text',
                            ],
                        ],
                        'filters' => ['limit'],
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::teaser',
                    ],
                ],

                'publish_up' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Published',
                        'filters' => ['date'],
                    ],
                ],

                'created' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Created',
                        'filters' => ['date'],
                    ],
                ],

                'modified' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Modified',
                        'filters' => ['date'],
                    ],
                ],

                'metaString' => [
                    'type' => 'String',
                    'args' => [
                        'format' => [
                            'type' => 'String',
                        ],
                        'separator' => [
                            'type' => 'String',
                        ],
                        'link_style' => [
                            'type' => 'String',
                        ],
                        'show_publish_date' => [
                            'type' => 'Boolean',
                        ],
                        'show_author' => [
                            'type' => 'Boolean',
                        ],
                        'show_category' => [
                            'type' => 'Boolean',
                        ],
                        'date_format' => [
                            'type' => 'String',
                        ],
                    ],
                    'metadata' => [
                        'label' => 'Meta',
                        'arguments' => [

                            'format' => [
                                'label' => 'Format',
                                'description' => 'Display the meta text in a sentence or a horizontal list.',
                                'type' => 'select',
                                'default' => 'list',
                                'options' => [
                                    'List' => 'list',
                                    'Sentence' => 'sentence',
                                ],
                            ],
                            'separator' => [
                                'label' => 'Separator',
                                'description' => 'Set the separator between fields.',
                                'default' => '|',
                                'enable' => 'arguments.format === "list"',
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
                            ],
                            'show_publish_date' => [
                                'label' => 'Display',
                                'description' => 'Show or hide fields in the meta text.',
                                'type' => 'checkbox',
                                'default' => true,
                                'text' => 'Show date',
                            ],
                            'show_author' => [
                                'type' => 'checkbox',
                                'default' => true,
                                'text' => 'Show author',
                            ],
                            'show_category' => [
                                'type' => 'checkbox',
                                'default' => true,
                                'text' => 'Show category',
                            ],
                            'date_format' => [
                                'label' => 'Date Format',
                                'description' => 'Select a predefined date format or enter a custom format.',
                                'type' => 'data-list',
                                'default' => '',
                                'options' => [
                                    'Aug 6, 1999 (M j, Y)' => 'M j, Y',
                                    'August 06, 1999 (F d, Y)' => 'F d, Y',
                                    '08/06/1999 (m/d/Y)' => 'm/d/Y',
                                    '08.06.1999 (m.d.Y)' => 'm.d.Y',
                                    '6 Aug, 1999 (j M, Y)' => 'j M, Y',
                                    'Tuesday, Aug 06 (l, M d)' => 'l, M d',
                                ],
                                'enable' => 'arguments.show_publish_date',
                                'attrs' => [
                                    'placeholder' => 'Default',
                                ],
                            ],
                        ],
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::metaString',
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

                'images' => [
                    'type' => 'ArticleImages',
                    'metadata' => [
                        'label' => '',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::images',
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

                'hits' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Hits',
                    ],
                ],

                'urls' => [
                    'type' => 'ArticleUrls',
                    'metadata' => [
                        'label' => 'Link',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::urls',
                    ],
                ],

                'event' => [
                    'type' => 'ArticleEvent',
                    'metadata' => [
                        'label' => 'Events',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::event',
                    ],
                ],

                'category' => [
                    'type' => 'Category',
                    'metadata' => [
                        'label' => 'Category',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::category',
                    ],
                ],

                'author' => [
                    'type' => 'User',
                    'metadata' => [
                        'label' => 'Author',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::author',
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
                'label' => 'Article',
            ],

        ];
    }

    public static function content($article)
    {
        if (isset($article->text)) {
            return (!empty($article->toc) ? $article->toc : '') . $article->text;
        }

        if ($article->params->get('show_intro', '1') === '1') {
            return "{$article->introtext} {$article->fulltext}";
        }

        if ($article->fulltext) {
            return $article->fulltext;
        }

        return $article->introtext;
    }

    public static function teaser($article, $args)
    {
        $args += ['show_excerpt' => true];

        if ($args['show_excerpt'] && class_exists(\FieldsHelper::class)) {
            $fields = \FieldsHelper::getFields('com_content.article', $article, true);

            foreach ($fields as $field) {
                if ($field->name === 'excerpt' && $field->rawvalue) {
                    return $field->rawvalue;
                }
            }
        }

        return $article->introtext;
    }

    public static function link($article)
    {
        return RouteHelper::getArticleRoute($article->id, $article->catid, $article->language);
    }

    public static function images($article)
    {
        return json_decode($article->images);
    }

    public static function urls($article)
    {
        return json_decode($article->urls);
    }

    public static function author($article)
    {
        $user = Factory::getUser($article->created_by);

        if ($article->created_by_alias && $user) {
            $user = clone $user;
            $user->name = $article->created_by_alias;
        }

        return $user;
    }

    public static function category($article)
    {
        return Categories::getInstance('content', ['countItems' => true])->get($article->catid);
    }

    public static function tags($article)
    {
        if (!isset($article->tags)) {
            return (new TagsHelper())->getItemTags('com_content.article', $article->id);
        }

        return $article->tags->itemTags;
    }

    public static function event($article)
    {
        return $article;
    }

    public static function tagString($article, array $args)
    {
        $tags = static::tags($article);
        $args += ['separator' => ', ', 'show_link' => true, 'link_style' => ''];

        return app(View::class)->render(Path::get('../../templates/tags'), compact('article', 'tags', 'args'));
    }

    public static function metaString($article, array $args)
    {
        $args += [
            'format' => 'list',
            'separator' => '|',
            'link_style' => '',
            'show_publish_date' => true,
            'show_author' => true,
            'show_category' => true,
            'date_format' => '',
        ];

        return app(View::class)->render(Path::get('../../templates/meta'), compact('article', 'args'));
    }
}

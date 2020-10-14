<?php

namespace YOOtheme;

use Joomla\CMS\Language\Text;
use Joomla\CMS\Pagination\PaginationObject;
use Joomla\CMS\Plugin\PluginHelper;

return [

    'transforms' => [

        'render' => function ($node, $params) {

            // Single Article
            if (!isset($params['pagination'])) {

                $article = isset($params['item'])
                    ? $params['item']
                    : (
                        isset($params['article'])
                            ? $params['article']
                            : false
                    );

                if (!$article) {
                    return false;
                }

                if (!isset($article->pagination)) {

                    $p = clone $article->params;
                    $p->set('show_item_navigation', true);

                    if (!PluginHelper::isEnabled('content', 'pagenavigation')) {
                        return false;
                    }

                    jimport('plugins.content.pagenavigation', JPATH_ROOT);
                    $dispatcher = \JEventDispatcher::getInstance();
                    $plugin = new \PlgContentPagenavigation($dispatcher, ['params' => ['display' => 0]]);
                    $plugin->onContentBeforeDisplay('com_content.article', $article, $p, 0);
                }

                if (!isset($article->pagination)) {
                    return false;
                }

                $node->props['pagination_type'] = 'previous/next';
                $node->props['pagination'] = [
                    'previous' => $article->prev ? new PaginationObject($article->prev_label, '', null, $article->prev, true) : null,
                    'next' => $article->next ? new PaginationObject($article->next_label, '', null, $article->next, true) : null,
                ];

                return;

            }

            // Article Index
            if ($params['pagination']->pagesTotal < 2) {
                return false;
            }

            $list = $params['pagination']->getPaginationPages();

            $total = $params['pagination']->pagesTotal;
            $current = (int) $params['pagination']->pagesCurrent;
            $endSize = 1;
            $midSize = 3;
            $dots = false;

            $pagination = [];

            if ($list['previous']['active']) {
                $pagination['previous'] = $list['previous']['data'];
            }

            $list['start']['data']->text = 1;
            $list['end']['data']->text = $total;

            for ($n = 1; $n <= $total; $n++) {

                $active = $n <= $endSize
                    || $current && $n >= $current - $midSize && $n <= $current + $midSize
                    || $n > $total - $endSize;

                if ($active || $dots) {

                    if ($active) {
                        $pagination[$n] = $n === 1
                            ? $list['start']['data']
                            : ($n === $total
                                ? $list['end']['data']
                                : $list['pages'][$n]['data']);

                        $pagination[$n]->active = $n === $current;
                    } else {
                        $pagination[$n] = new PaginationObject(Text::_('&hellip;'));
                    }

                    $dots = $active;
                }

            }

            if ($list['next']['active']) {
                $pagination['next'] = $list['next']['data'];
            }

            $node->props['pagination'] = $pagination;

        },

    ],

];

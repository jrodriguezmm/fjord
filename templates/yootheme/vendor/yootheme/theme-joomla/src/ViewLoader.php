<?php

namespace YOOtheme\Theme\Joomla;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\FileLayout;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;
use Joomla\Registry\Registry;
use YOOtheme\Config;

class ViewLoader
{
    public static function loadArticle($name, array $parameters, callable $next)
    {
        $defaults = array_fill_keys(['title', 'author', 'content', 'hits', 'created', 'modified', 'published', 'category', 'image', 'tags', 'icons', 'readmore', 'pagination', 'link', 'permalink', 'event', 'single'], null);

        /**
         * @var Config           $config
         * @var \stdClass        $article
         * @var \stdClass|string $image
         * @var bool             $single
         */
        extract(array_replace($defaults, $parameters), EXTR_SKIP);

        // Params
        if (!isset($params)) {
            $params = $article->params;
        } elseif (is_array($params)) {
            $params = new Registry($params);
        }

        // Event
        if (isset($article->event)) {

            $event = $article->event;

            if (!$single && $params['show_intro']) {
                $event->afterDisplayTitle = '';
            }
        }

        // Builder
        if ($config('app.isBuilder')) {
            return $next('~theme/templates/article-builder', compact('config', 'article', 'content'));
        }

        // Link
        if (!isset($link)) {
            $link = \ContentHelperRoute::getArticleRoute($article->slug, $article->catid, $article->language);
        }

        // Permalink
        if (!isset($permalink)) {
            $permalink = Route::_($link, true, 0, 1);
        }

        if ($params['access-view'] === false) {
            $menu = Factory::getApplication()->getMenu()->getActive();
            $return = base64_encode(Route::_($link, false));
            $link = new Uri(Route::_("index.php?option=com_users&view=login&Itemid={$menu->id}", false));
            $link->setVar('return', $return);
        }

        // Title
        if ($params['show_title']) {

            $title = $article->title;

            if ($params['link_titles']) {
                $title = HTMLHelper::_('link', $link, $title, ['class' => 'uk-link-reset']);
            }
        }

        if (!empty($article->created_by_alias)) {
            $article->author = $article->created_by_alias;
        }

        // Hits
        if ($params['show_hits']) {
            $hits = $article->hits;
        }

        // Create date
        if ($params['show_create_date']) {
            $created = HTMLHelper::_('date', $article->created, Text::_('DATE_FORMAT_LC3'));
            $created = '<time datetime="' . HTMLHelper::_('date', $article->created, 'c') . "\">{$created}</time>";
        }

        // Modify date
        if ($params['show_modify_date']) {
            $modified = HTMLHelper::_('date', $article->modified, Text::_('DATE_FORMAT_LC3'));
            $modified = '<time datetime="' . HTMLHelper::_('date', $article->modified, 'c') . "\">{$modified}</time>";
        }

        // Image
        if (is_string($image)) {

            $images = new Registry($article->images);
            $imageType = $image;

            if ($images->get("image_{$imageType}")) {

                $image = new \stdClass();
                $image->link = $params['link_titles'] ? $link : null;
                $image->align = $images->get("float_{$imageType}") ?: $params["float_{$imageType}"];
                $image->caption = $images->get("image_{$imageType}_caption");
                $image->attrs = [
                    'src' => $images->get("image_{$imageType}"),
                    'alt' => $images->get("image_{$imageType}_alt"),
                    'title' => $image->caption,
                ];

            } else {

                $image = null;
            }
        }

        // Tags
        if ($params->get('show_tags', 1) && !empty($article->tags->itemTags)) {

            $layout = new FileLayout('joomla.content.tags');

            // check for override in child theme
            if ($childDir = $config('theme.childDir')) {
                $layout->addIncludePath("{$childDir}/html/layouts");
            }

            $tags = $layout->render($article->tags->itemTags);
        }

        // Icons
        if (!isset($icons)) {
            $icons['print'] = $params['show_print_icon'] ? HTMLHelper::_('icon.print_popup', $article, $params) : '';
            $icons['email'] = $params['show_email_icon'] ? HTMLHelper::_('icon.email', $article, $params) : '';
            $icons['edit'] = $params['access-edit'] ? HTMLHelper::_('icon.edit', $article, $params) : '';
        }

        $icons = array_filter($icons);

        // Readmore
        if ($params['show_readmore'] && (!empty($article->readmore) || !$single && is_numeric($config('~theme.blog.content_length')) && (int) $config('~theme.blog.content_length') >= 0)) {

            $readmore = new \stdClass();
            $readmore->link = $link;

            if ($params['access-view']) {

                $attribs = new Registry($article->attribs);

                if (!$readmore->text = $attribs->get('alternative_readmore')) {
                    $readmore->text = Text::_($params['show_readmore_title'] ? 'COM_CONTENT_READ_MORE' : 'TPL_YOOTHEME_READ_MORE');
                }

                if ($params['show_readmore_title']) {
                    $readmore->text .= HTMLHelper::_('string.truncate', $article->title, $params['readmore_limit']);
                }

            } else {
                $readmore->text = Text::_('COM_CONTENT_REGISTER_TO_READ_MORE');
            }
        }

        // Pagination
        if (isset($article->pagination)) {
            $pagination = $article->pagination;
        }

        // Blog
        if (isset($parameters['layout']) && $parameters['layout'] === 'blog') {

            $data = $config('~theme.post', []);

            // Merge blog config?
            if (!$single) {
                $data = array_merge($data, $config('~theme.blog', []));
            }

            // Has excerpt field?
            if (!$single && isset($article->jcfields)) {
                foreach ($article->jcfields as $field) {
                    if ($field->name === 'excerpt' && $field->rawvalue) {
                        $content = $field->rawvalue;
                        break;
                    }
                }
            }

            $params->loadArray($data);
        }

        return $next($name, array_diff_key(get_defined_vars(), array_flip(['data', 'next', 'name', 'parameters', 'defaults'])));
    }
}

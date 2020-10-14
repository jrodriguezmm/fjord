<?php

namespace YOOtheme\Theme\Joomla;

use Joomla\CMS\Router\Router;
use Joomla\CMS\Uri\Uri;
use Joomla\Component\Content\Site\Helper\RouteHelper;
use YOOtheme\Config;
use YOOtheme\Metadata;
use YOOtheme\Path;
use YOOtheme\Str;
use YOOtheme\Url;

class ArticlesListener
{
    public static function prepareData(Config $config, Metadata $metadata, $event)
    {
        list($context, $article) = $event->getArguments();

        if ($context !== 'com_content.article') {
            return;
        }

        // on error $article is an array instead of object
        $article = (object) $article;

        if (empty($article->id)) {
            return;
        }

        $articles = json_encode([
            'context' => $context,
            'apikey' => $config('app.apikey'),
            'url' => Url::to("{$config('req.baseUrl')}/index.php?option=com_ajax&template={$config('theme.template')}&p=customizer&section=builder&format=html", [
                'section' => 'builder',
                'site' => static::getRoute($article),
            ]),
        ]);

        $metadata->set('script:articles-data', "var \$articles = {$articles};");
        $metadata->set('script:articles', ['src' => Path::get('../app/articles.min.js'), 'defer' => true]);
    }

    public static function beforeSave(Config $config, $event)
    {
        list($context, $article) = $event->getArguments();

        if (!in_array($context, ['com_content.form', 'com_content.article'], true)) {
            return;
        }

        // use "jform.articletext" from request to keep builder data, when JText filters are active
        if (preg_match('/<!--\s{.*}\s-->\s*$/', $config('req.body.jform.articletext'), $matches)) {
            $article->fulltext = $matches[0];
        }
    }

    protected static function getRoute($article)
    {
        $route = RouteHelper::getArticleRoute($article->id, $article->catid, $article->language);
        $site = (string) Router::getInstance('site')->build($route);

        // Workaround for bug in Joomla 3.7
        $base = Uri::root(true) . '/administrator';
        if (Str::startsWith($site, $base)) {
            $site = Uri::root(true) . Str::substr($site, strlen($base));
        }

        return $site;
    }
}

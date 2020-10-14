<?php

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use YOOtheme\Builder\Joomla\Source\UserHelper;

$author = $published = $category = '';

// Author
if ($args['show_author']) {

    $author = $article->created_by_alias ?: $article->author;

    if (!isset($article->contact_link)) {
        $article->contact_link = UserHelper::getContactLink($article->created_by);
    }

    if (!empty($article->contact_link)) {
        $author = HTMLHelper::_('link', $article->contact_link, $author);
    }
}

// Publish date
if ($args['show_publish_date']) {
    $published = HTMLHelper::_('date', $article->publish_up, $args['date_format'] ?: Text::_('DATE_FORMAT_LC3'));
    $published = '<time datetime="' . HTMLHelper::_('date', $article->publish_up, 'c') . "\">{$published}</time>";
}

// Category
if ($args['show_category']) {

    $category = $article->category_title;

    if ($article->catid) {
        $category = HTMLHelper::_('link', Route::_(ContentHelperRoute::getCategoryRoute($article->catid)), $category);
    }
}

if (!$published && !$author && !$category) {
    return;
}

if ($args['link_style']) {
    echo "<span class=\"uk-{$args['link_style']}\">";
}

switch ($args['format']) {

    case 'list':

        echo implode(" {$args['separator']} ", array_filter([$published, $author, $category]));
        break;

    default: // sentence

        if ($author && $published) {
            Text::printf('TPL_YOOTHEME_META_AUTHOR_DATE', $author, $published);
        } elseif ($author) {
            Text::printf('TPL_YOOTHEME_META_AUTHOR', $author);
        } elseif ($published) {
            Text::printf('TPL_YOOTHEME_META_DATE', $published);
        }

        if ($category) {
            echo ' ';
            Text::printf('TPL_YOOTHEME_META_CATEGORY', $category);
        }
}

if ($args['link_style']) {
    echo '</span>';
}

<?php

defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;
use function YOOtheme\app;
use YOOtheme\Config;
use YOOtheme\Path;

// prefer child theme's pagination
if (Path::get(__FILE__) !== $file = Path::get('~theme/html/pagination.php')) {
    return include $file;
}

function pagination_list_render($list) {

    $config = app(Config::class);
    if (!$config('~theme.blog.pagination_startend')) {
        $list['start']['active'] = false;
        $list['end']['active'] = false;
    }

    // find out the id of the page, that is the current page
    $currentId = 0;

    foreach ($list['pages'] as $id => $page) {
        if (!$page['active']) {
            $currentId = $id;
        }
    }

    // set the range for the inner pages that should be displayed
    // this displays + - $range page-buttons around the current page
    // due to joomla-restrictions there won't be displayed more than -5 and +4 buttons.
    $range = 3;

    // start building pagination-list
    $html = ['<ul class="uk-pagination uk-margin-large uk-flex-center">'];

    // add first-button
    if ($list['start']['active'] == 1) {
        $html[] = $list['start']['data'];
    }

    // add previous-button
    if ($list['previous']['active'] == 1) {
        $html[] = $list['previous']['data'];
    }

    // add buttons for surrounding pages
    foreach ($list['pages'] as $id => $page) {
        // only show the buttons that are within the range
        if ($id <= $currentId + $range && $id >= $currentId - $range) {
            $html[] = $page['data'];
        }
    }

    // add next-button
    if ($list['next']['active'] == 1) {
        $html[] = $list['next']['data'];
    }

    // add last-button
    if ($list['end']['active'] == 1) {
        $html[] = $list['end']['data'];
    }

    // close pagination-list
    $html[] = '</ul>';

    return implode("\n", $html);
}

function pagination_item_active($item) {
    $cls = '';
    $title = '';

    if ($item->text == Text::_('JNEXT')) {
        $title = $item->text;
        $item->text = '<span uk-pagination-next></span>';
        $cls = 'next';
    } elseif ($item->text == Text::_('JPREV')) {
        $title = $item->text;
        $item->text = '<span uk-pagination-previous></span>';
        $cls = 'previous';
    } elseif ($item->text == Text::_('JLIB_HTML_START')) {
        $cls = 'first';
    } elseif ($item->text == Text::_('JLIB_HTML_END')) {
        $cls = 'last';
    }

    return "<li><a class=\"{$cls}\" href=\"{$item->link}\" title=\"{$title}\">{$item->text}</a></li>";
}

function pagination_item_inactive($item) {
    return "<li class=\"uk-active\"><span>{$item->text}</span></li>";
}

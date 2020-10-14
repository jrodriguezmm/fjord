<?php

namespace YOOtheme;

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;

$view = app(View::class);

echo $view('~theme/templates/search', [

    'position' => $module->position,

    'attrs' => [
        'id' => "search-{$module->id}",
        'action' => Route::_(\FinderHelperRoute::getSearchRoute($params->get('searchfilter', null))),
        'method' => 'get',
        'role' => 'search',
        'class' => ($class = $params->get('moduleclass_sfx')) ? [$class] : '',
    ],

    'fields' => [

        ['tag' => 'input', 'name' => 'q', 'placeholder' => Text::_('MOD_FINDER_SEARCH_VALUE')],
        ['tag' => 'input', 'type' => 'hidden', 'name' => 'option', 'value' => 'com_finder'],
        ['tag' => 'input', 'type' => 'hidden', 'name' => 'Itemid', 'value' => $params->get('set_itemid', 0) ?: $app->input->getInt('Itemid')],

    ],

]);

/*
 * This segment of code sets up the autocompleter.
 */
if ($params->get('show_autosuggest', 1))
{
    HTMLHelper::_('jquery.framework');
    HTMLHelper::_('stylesheet', 'com_finder/finder.css', ['version' => 'auto', 'relative' => true]);
    HTMLHelper::_('script', 'jui/jquery.autocomplete.min.js', ['version' => 'auto', 'relative' => true]);

    $script = "
    jQuery(document).ready(function() {
        var suggest = jQuery('#search-{$module->id} input[type=\"search\"]').autocomplete({
            serviceUrl: '" . Route::_('index.php?option=com_finder&task=suggestions.suggest&format=json&tmpl=component') . "',
            paramName: 'q',
            minChars: 1,
            maxHeight: 400,
            width: 300,
            zIndex: 9999,
            deferRequestBy: 500
        });
	});";

    Factory::getDocument()->addScriptDeclaration($script);
}

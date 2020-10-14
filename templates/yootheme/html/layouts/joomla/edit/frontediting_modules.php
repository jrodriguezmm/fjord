<?php

defined('JPATH_BASE') or die;

use YOOtheme\Path;

if (Path::get(__FILE__) !== $file = Path::get('~theme/html/layouts/joomla/edit/frontediting_modules.php')) {
    return include $file;
}

$mod = $displayData['module'];

if (!intval($mod->id)) {
    return;
}

include JPATH_ROOT . '/layouts/joomla/edit/frontediting_modules.php';

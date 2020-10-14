<?php

defined('_JEXEC') or die;

$classes = [];
$aliases = [];

// class aliases for Joomla < 4.0
if (version_compare(JVERSION, '4.0', '<')) {
    $aliases['ContactHelperRoute'] = 'Joomla\Component\Contact\Site\Helper\RouteHelper';
    $aliases['ContentHelperRoute'] = 'Joomla\Component\Content\Site\Helper\RouteHelper';
    $aliases['TagsHelperRoute'] = 'Joomla\Component\Tags\Site\Helper\RouteHelper';
    $aliases['UsersHelper'] = 'Joomla\Component\Users\Administrator\Helper\UsersHelper';
    $classes['ContactHelperRoute'] = JPATH_SITE . '/components/com_contact/helpers/route.php';
    $classes['ContentHelperRoute'] = JPATH_SITE . '/components/com_content/helpers/route.php';
    $classes['MediaHelper'] = JPATH_ADMINISTRATOR . '/components/com_media/helpers/media.php';
    $classes['TagsHelperRoute'] = JPATH_SITE . '/components/com_tags/helpers/route.php';
    $classes['UsersHelper'] = JPATH_ADMINISTRATOR . '/components/com_users/helpers/users.php';
}

// class aliases for Joomla < 3.9
if (version_compare(JVERSION, '3.9', '<')) {
    $aliases['JFile'] = 'Joomla\CMS\Filesystem\File';
    $aliases['JFolder'] = 'Joomla\CMS\Filesystem\Folder';
    $aliases['JPath'] = 'Joomla\CMS\Filesystem\Path';
}

// class aliases for Joomla < 3.8
if (version_compare(JVERSION, '3.8', '<')) {
    $aliases['JAccess'] = 'Joomla\CMS\Access\Access';
    $aliases['JComponentHelper'] = 'Joomla\CMS\Component\ComponentHelper';
    $aliases['JControllerLegacy'] = 'Joomla\CMS\MVC\Controller\BaseController';
    $aliases['JDate'] = 'Joomla\CMS\Date\Date';
    $aliases['JDocumentRenderer'] = 'Joomla\CMS\Document\DocumentRenderer';
    $aliases['JEditor'] = 'Joomla\CMS\Editor\Editor';
    $aliases['JFactory'] = 'Joomla\CMS\Factory';
    $aliases['JFormField'] = 'Joomla\CMS\Form\FormField';
    $aliases['JHelperMedia'] = 'Joomla\CMS\Helper\MediaHelper';
    $aliases['JHelperRoute'] = 'Joomla\CMS\Helper\RouteHelper';
    $aliases['JHelperTags'] = 'Joomla\CMS\Helper\TagsHelper';
    $aliases['JHtml'] = 'Joomla\CMS\HTML\HTMLHelper';
    $aliases['JHttpFactory'] = 'Joomla\CMS\Http\HttpFactory';
    $aliases['JLanguageMultilang'] = 'Joomla\CMS\Language\Multilanguage';
    $aliases['JLayoutHelper'] = 'Joomla\CMS\Layout\LayoutHelper';
    $aliases['JMenu'] = 'Joomla\CMS\Menu\AbstractMenu';
    $aliases['JModelLegacy'] = 'Joomla\CMS\MVC\Model\BaseDatabaseModel';
    $aliases['JModuleHelper'] = 'Joomla\CMS\Helper\ModuleHelper';
    $aliases['JPlugin'] = 'Joomla\CMS\Plugin\CMSPlugin';
    $aliases['JPluginHelper'] = 'Joomla\CMS\Plugin\PluginHelper';
    $aliases['JRoute'] = 'Joomla\CMS\Router\Route';
    $aliases['JRouter'] = 'Joomla\CMS\Router\Router';
    $aliases['JSession'] = 'Joomla\CMS\Session\Session';
    $aliases['JText'] = 'Joomla\CMS\Language\Text';
    $aliases['JUri'] = 'Joomla\CMS\Uri\Uri';
}

// register classes
foreach ($classes as $class => $path) {
    JLoader::register($class, $path);
}

// register class aliases
foreach ($aliases as $original => $alias) {
    JLoader::registerAlias($alias, $original);
}

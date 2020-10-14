<?php

namespace YOOtheme\Theme\Joomla;

use Joomla\CMS\Application\CMSApplication;
use Joomla\CMS\Document\Document;
use Joomla\CMS\Document\HtmlDocument;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Language;
use Joomla\CMS\MVC\Controller\BaseController;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;
use Joomla\Input\Input;
use YOOtheme\Config;
use YOOtheme\Event;

class ThemeListener
{
    public static function initTheme(Config $config)
    {
        $class = new \ReflectionClass(BaseController::class);

        // override views cache array
        if ($config('app.isSite') && $class->hasProperty('views')) {
            $views = $class->getProperty('views');
            $views->setAccessible(true);
            $views->setValue(new ViewsObject());
        }
    }

    public static function afterDispatch(Config $config, Document $document, Input $input, Language $language, CMSApplication $cms)
    {
        // is template active?
        if (!$config('~theme') || $config('app.isAdmin') || $input->getCmd('p') === 'customizer' || $input->getCmd('tmpl') === 'component') {
            return;
        }

        $itemId = ($item = $cms->getMenu()->getDefault()) ? $item->id : 0;
        $siteUrl = Route::_("index.php?Itemid={$itemId}", false, 0, true);

        $language->load('tpl_yootheme', $config('theme.rootDir'));
        $document->setBase(htmlspecialchars(Uri::current()));

        $config->set('~theme.site_url', $siteUrl);
        $config->set('~theme.direction', $document->getDirection());
        $config->set('~theme.page_class', $cms->getParams()->get('pageclass_sfx'));

        if (PluginHelper::isEnabled('content', 'emailcloak')) {
            static::fixEmailCloak($document);
        }

        if (($custom = $config('~theme.custom_js', '')) && $document instanceof HtmlDocument) {
            static::addCustomScript($document, $custom);
        }

        if ($config('~theme.jquery') || strpos($custom, 'jQuery') !== false) {
            HTMLHelper::_('jquery.framework');
        }

        Event::emit('theme.head');
    }

    protected static function fixEmailCloak(Document $document)
    {
        $document->addScriptDeclaration("document.addEventListener('DOMContentLoaded', function() {
            Array.prototype.slice.call(document.querySelectorAll('a span[id^=\"cloak\"]')).forEach(function(span) {
                span.innerText = span.textContent;
            });
        });");
    }

    protected static function addCustomScript(HtmlDocument $document, $script)
    {
        if (stripos(trim($script), '<script') !== 0) {
            $script = "<script>{$script}</script>";
        }

        $document->addCustomTag($script);
    }
}

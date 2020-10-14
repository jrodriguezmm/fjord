<?php

namespace YOOtheme\Theme\Joomla;

use Joomla\CMS\Application\CMSApplication;
use Joomla\CMS\Document\HtmlDocument;
use Joomla\CMS\Editor\Editor;
use Joomla\CMS\Language\Language;
use Joomla\CMS\Table\Table;
use Joomla\CMS\Uri\Uri;
use YOOtheme\Config;
use YOOtheme\Url;

class EditorListener
{
    public static function initCustomizer(Config $config, Language $language)
    {
        $root = Uri::root();
        $editor = Editor::getInstance();
        $element = $config('joomla.config.editor');
        $language->load("plg_editors_{$element}");

        // skip visual editor
        if (in_array($element, ['none', 'codemirror'])) {
            return;
        }

        // current editor plugin
        $plugin = Table::getInstance('Extension');
        $plugin->load([
            'folder' => 'editors',
            'element' => $element,
        ]);

        // add editor config
        $config->add('customizer', [

            'editor' => [
                'id' => 'editor-xtd',
                'title' => isset($plugin->name) ? $language->_($plugin->name) : 'Editor',
                'iframe' => Url::route('theme/editor', ['format' => 'html', 'tmpl' => 'component']),
                'buttons' => $editor->getButtons('editor-xtd', ['pagebreak', 'readmore', 'widgetkit']),
                'settings' => [
                    'branding' => false,
                    'content_css' => "{$root}templates/system/css/editor.css",
                    'directionality' => $config('locale.rtl') ? 'rtl' : 'ltr',
                    'document_base_url' => $root,
                    'entity_encoding' => 'raw',
                    'insert_button_items' => '', // e.g. 'hr charmap',
                    'plugins' => 'link autolink hr lists charmap paste',
                    'toolbar1' => 'formatselect bold italic bullist numlist blockquote alignleft aligncenter alignright',
                    'toolbar2' => 'link insert strikethrough hr pastetext removeformat charmap outdent indent',
                ],
            ],

        ]);
    }

    public static function renderEditor(Config $config, CMSApplication $cms, HtmlDocument $document)
    {
        $type = $config('joomla.config.editor');
        $editor = Editor::getInstance($type);
        $exclude = ['pagebreak', 'readmore', 'widgetkit'];

        ob_start();

        echo "<form>{$editor->display('content', '', '100%', '100%', '', '30', $exclude)}</form>";

		$document->setBuffer(ob_get_clean(), 'component');
    }
}

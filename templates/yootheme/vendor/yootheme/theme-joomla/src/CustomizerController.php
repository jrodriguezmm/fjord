<?php

namespace YOOtheme\Theme\Joomla;

use Joomla\CMS\Application\CMSApplication;
use Joomla\CMS\Document\HtmlDocument;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\CMS\User\User;
use Joomla\Registry\Registry;
use YOOtheme\Config;
use YOOtheme\Database;
use YOOtheme\Event;
use YOOtheme\Http\Request;
use YOOtheme\Http\Response;
use YOOtheme\Metadata;
use YOOtheme\Path;
use YOOtheme\Url;

class CustomizerController
{
    public static function index(Config $config, User $user, Metadata $metadata, CMSApplication $cms, HtmlDocument $document, Request $request)
    {
        HTMLHelper::_('behavior.keepalive');

        // init customizer
        Event::emit('customizer.init');

        // init config
        $config->add('customizer', [
            'config' => $config('~theme'),
            'return' => $request('return') ?: Url::to('administrator/index.php'),
        ]);

        // apikey editable?
        if (!$user->authorise('core.admin', 'com_plugins')) {
            $config->del('customizer.sections.settings.fields.settings.items.api-key');
        }

        // add style/script
        $metadata->set('style:customizer', ['href' => Path::get('../assets/css/admin.css')]);
        $metadata->set('script:customizer', ['src' => Path::get('../app/customizer.min.js')]);

        // set system template
        $cms->set('theme', 'system');
        $cms->input->set('tmpl', 'component');

        // set document title/icon
        $document->setTitle("Website Builder - {$config('joomla.config.sitename')}");
        $document->addFavicon(Url::to(Path::get('../assets/images/favicon.png')));
    }

    public static function save(Request $request, Response $response, Config $config, Database $db, User $user)
    {
        $request->abortIf(!$user->authorise('core.edit', 'com_templates'), 403, 'Insufficient User Rights.');

        // alter custom_data type to MEDIUMTEXT only in MySQL database
        if (strpos($db->driver, 'mysql') !== false) {
            foreach (['extensions' => 'custom_data', 'template_styles' => 'params'] as $table => $field) {

                $query = "SHOW FIELDS FROM @{$table} WHERE Field = '{$field}'";
                $alter = "ALTER TABLE @{$table} CHANGE `{$field}` `{$field}` MEDIUMTEXT NOT NULL";

                if ($db->fetchObject($query)->Type == 'text') {
                    $db->executeQuery($alter);
                }
            }
        }

        // get config values
        $values = $request('config');

        // update apikey in plugin
        if (isset($values['yootheme_apikey'])) {

            $plugin = PluginHelper::getPlugin('installer', 'yootheme');

            if ($plugin && $user->authorise('core.admin', 'com_plugins')) {

                $reg = new Registry($plugin->params);
                $reg->set('apikey', $values['yootheme_apikey']);

                $db->executeQuery("UPDATE @extensions SET params = :params WHERE element = 'yootheme' AND folder = 'installer'", ['params' => $reg->toString()]);
            }

            unset($values['yootheme_apikey']);
        }

        // prepare style params
        $params = ['config' => json_encode($values), 'yootheme' => 'true'];

        // update style params
        $db->update('@template_styles', ['params' => json_encode($params)], ['id' => $config('theme.id')]);

        return 'success';
    }
}

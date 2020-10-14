<?php

namespace YOOtheme\Theme\Joomla;

use Joomla\CMS\Helper\ModuleHelper;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\CMS\User\User;
use YOOtheme\Config;
use YOOtheme\Metadata;
use YOOtheme\Path;
use YOOtheme\Translator;
use YOOtheme\Url;
use YOOtheme\View;

class ModulesListener
{
    public static function initTheme(Config $config)
    {
        if ($config('app.isSite')) {

            $renderer = version_compare(JVERSION, '3.8', '>=') ? 'Joomla\CMS\Document\Renderer\Html\ModulesRenderer' : 'JDocumentRendererHtmlModules';

            class_alias('YOOtheme\Theme\Joomla\ModulesRenderer', $renderer);
        }
    }

    public static function initCustomizer(Config $config, User $user, ModulesHelper $helper)
    {
        $config->add('customizer', [

            'module' => [
                'types' => $helper->getTypes(),
                'modules' => $helper->getModules(),
                'positions' => $helper->getPositions(),
                'canEdit' => $user->authorise('core.edit', 'com_modules'),
                'canDelete' => $user->authorise('core.edit.state', 'com_modules'),
                'canCreate' => $user->authorise('core.create', 'com_modules'),
            ],

        ]);

        if ($user->authorise('core.manage', 'com_modules')) {

            $component = PluginHelper::isEnabled('system', 'advancedmodules') ? 'com_advancedmodules' : 'com_modules';

            $config->set('customizer.sections.joomla-modules.url', "administrator/index.php?option={$component}");
            $config->addFile('customizer', Path::get('../config/customizer.json'));
        }
    }

    public static function loadModules(Config $config, View $view, $event)
    {
        list($modules) = $event->getArguments();

        if ($config('app.isAdmin') || !$config('~theme')) {
            return;
        }

        $view['sections']->add('breadcrumbs', function () use ($config) {
            return ModuleHelper::renderModule(static::createModule([
                'name' => 'yoo_breadcrumbs',
                'module' => 'mod_breadcrumbs',
                'params' => [
                    'showLast' => $config('~theme.site.breadcrumbs_show_current'),
                    'showHome' => $config('~theme.site.breadcrumbs_show_home'),
                    'homeText' => $config('~theme.site.breadcrumbs_home_text'),
                ],
            ]));
        });

        if ($position = $config('~theme.header.search')) {

            $search = static::createModule([
                'name' => 'yoo_search',
                'module' => $config('~theme.search_module'),
                'position' => $position,
            ]);

            array_push($modules, $search);

            $search = static::createModule([
                'name' => 'yoo_search',
                'module' => $config('~theme.search_module'),
                'position' => 'mobile',
            ]);

            array_push($modules, $search);
        }

        if ($position = $config('~theme.header.social')) {

            $social = static::createModule([
                'name' => 'yoo_socials',
                'module' => 'mod_custom',
                'position' => $position,
                'content' => $view('~theme/templates/socials'),
            ]);

            strpos($position, 'left') ? array_unshift($modules, $social) : array_push($modules, $social);
        }

        $temp = $config('req.customizer.module');

        if ($config('~theme.header.layout') === 'stacked-center-c') {
            $headerModules = self::filterHeaderModules($modules);
            foreach (array_slice($headerModules, ceil(count($headerModules) / 2)) as $module) {
                $module->position .= '-split';
            }
        }

        foreach ($modules as $module) {

            $module->type = str_replace('mod_', '', $module->module);
            $module->attrs = ['id' => "module-{$module->id}", 'class' => []];

            if ($temp && $temp['id'] == $module->id) {
                $module->content = $temp['content'];
            }

            $config->update("~theme.modules.{$module->id}", function ($values) use ($module) {

                $params = json_decode($module->params);

                if (isset($params->yoo_config)) {
                    $config = $params->yoo_config;
                } elseif (isset($params->config)) {
                    $config = $params->config;
                } else {
                    $config = '{}';
                }

                return array_merge($values ?: [], json_decode($config, true), [
                    'showtitle' => $module->showtitle,
                    'class' => [isset($params->moduleclass_sfx) ? $params->moduleclass_sfx : ''],
                    'title_tag' => isset($params->header_tag) ? $params->header_tag : 'h3',
                    'title_class' => isset($params->header_class) ? $params->header_class : '',
                    'is_list' => in_array($module->type, ['articles_archive', 'articles_categories', 'articles_latest', 'articles_popular', 'tags_popular', 'tags_similar']),
                ]);

            });

        }

        $event->setArgument(0, $modules);
    }

    public static function editModule(Config $config, Metadata $metadata, Translator $translator, $event)
    {
        list($form, $data) = $event->getArguments();

        if (!in_array($form->getName(), ['com_modules.module', 'com_advancedmodules.module', 'com_config.modules'])) {
            return;
        }

        // don't show theme settings in builder module
        if (isset($data->module) && $data->module == 'mod_yootheme_builder') {
            return;
        }

        if (!isset($data->params['yoo_config']) && isset($data->params['config'])) {
            $data->params['yoo_config'] = $data->params['config'];
        }

        $module = $config->loadFile(Path::get('../config/modules.json'));
        $module['locales'] = $translator->getResources();

        $metadata->set('script:module-data', sprintf('var $module = %s;', json_encode($module)));
        $metadata->set('script:module-edit', ['src' => Path::get('../app/module-edit.min.js')]);
        $metadata->set('style:module-styles', ['href' => Url::to('~yootheme/theme-joomla/assets/css/admin.css')]);

        $form->load('<form><fields name="params"><fieldset name="template" label="Template"><field name="yoo_config" type="hidden" default="{}" /></fieldset></fields></form>');
    }

    public static function createModule($module)
    {
        static $id = 0;

        $module = (object) array_merge(['id' => 'tm-' . (++$id), 'title' => '', 'showtitle' => 0, 'position' => '', 'params' => '{}'], (array) $module);

        if (is_array($module->params)) {
            $module->params = json_encode($module->params);
        }

        return $module;
    }

    protected static function filterHeaderModules($modules)
    {
        return array_filter($modules, function ($module) {
            return $module->position === 'header';
        });
    }
}

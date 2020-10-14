<?php

namespace YOOtheme;

use Joomla\CMS\Helper\ModuleHelper;

return [

    'transforms' => [

        'render' => function ($node) {

            $load = new \ReflectionMethod('Joomla\CMS\Helper\ModuleHelper', 'load');
            $load->setAccessible(true);

            foreach ($load->invoke(null) as $module) {

                if ($node->props['module'] !== $module->id) {
                    continue;
                }

                $config = app(Config::class);
                $index = "~theme.modules.{$module->id}";
                $props = $config->get($index, []);

                $node->attrs['class'] = array_merge($node->attrs['class'], $props['class']);
                $node->props = Arr::merge($props, $node->props);

                // override module config with props
                $config->set($index, $node->props);

                // render module content
                $node->module = (object) [
                    'title' => $module->title,
                    'content' => ModuleHelper::renderModule($module),
                ];

                // reset module config
                $config->set($index, $props);

                break;
            }

            // return false, if no module content was found
            if (empty($node->props['module']) || empty($node->module->content)) {
                return false;
            }

        },

    ],

];

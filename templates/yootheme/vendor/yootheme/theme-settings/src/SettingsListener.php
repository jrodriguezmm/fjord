<?php

namespace YOOtheme\Theme;

use YOOtheme\Config;
use YOOtheme\Url;

class SettingsListener
{
    public static function initHead(Config $config)
    {
        $assets = "~yootheme/theme-{$config('app.platform')}/assets";

        $config->set('~theme.body_class', [$config('~theme.page_class')]);
        $config->set('~theme.favicon', Url::to($config('~theme.favicon') ?: "{$assets}/images/favicon.png"));
        $config->set('~theme.touchicon', Url::to($config('~theme.touchicon') ?: "{$assets}/images/apple-touch-icon.png"));
    }
}

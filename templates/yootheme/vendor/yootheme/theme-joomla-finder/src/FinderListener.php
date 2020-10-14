<?php

namespace YOOtheme\Theme\Joomla;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\User\User;
use YOOtheme\Config;

class FinderListener
{
    public static function initCustomizer(Config $config, User $user)
    {
        $params = ComponentHelper::getParams('com_media');

        $allowable = array_map('trim', explode(',', $params->get('upload_extensions')));
        $allowable = array_map(function ($extension) {
            return '.' . $extension;
        }, $allowable);
        $allowedMime = array_map('trim', explode(',', $params->get('upload_mime')));

        // allow all allowable file extensions and MIME types in input field
        $accepted = implode(',', array_merge($allowable, $allowedMime));

        $config->add('customizer', [

            'media' => [
                // TODO
                // 'path' => ComponentHelper::getParams('com_media')->get('file_path'),
                'accept' => $accepted,
                'legacy' => version_compare(JVERSION, '4.0', '<'),
                'canCreate' => $user->authorise('core.manage', 'com_media') || $user->authorise('core.create', 'com_media'),
                'canDelete' => $user->authorise('core.manage', 'com_media') || $user->authorise('core.delete', 'com_media'),
            ],

        ]);
    }
}

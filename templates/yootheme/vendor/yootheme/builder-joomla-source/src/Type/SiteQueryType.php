<?php

namespace YOOtheme\Builder\Joomla\Source\Type;

use Joomla\CMS\Factory;

class SiteQueryType
{
    /**
     * @return array
     */
    public static function config()
    {
        return [

            'fields' => [

                'site' => [
                    'type' => 'Site',
                    'metadata' => [
                        'label' => 'Site',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::resolve',
                    ],
                ],

            ],

        ];
    }

    public static function resolve()
    {
        $config = Factory::getConfig();
        $document = Factory::getDocument();

        return [
            'title' => $config->get('sitename'),
            'page_title' => $document->getTitle(),
        ];
    }
}

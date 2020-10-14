<?php

namespace YOOtheme\Builder\Joomla\Source\Type;

use YOOtheme\Builder\Joomla\Source\UserHelper;

class UserType
{
    /**
     * @return array
     */
    public static function config()
    {
        return [

            'fields' => [

                'name' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Name',
                        'filters' => ['limit'],
                    ],
                ],

                'username' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Username',
                        'filters' => ['limit'],
                    ],
                ],

                'email' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Email',
                        'filters' => ['limit'],
                    ],
                ],

                'registerDate' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Registered',
                        'filters' => ['date'],
                    ],
                ],

                'lastvisitDate' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Last Visit Date',
                        'filters' => ['date'],
                    ],
                ],

                'link' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Link',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::link',
                    ],
                ],

            ],

            'metadata' => [
                'type' => true,
                'label' => 'User',
            ],

        ];
    }

    public static function link($user)
    {
        return UserHelper::getContactLink($user->id);
    }
}

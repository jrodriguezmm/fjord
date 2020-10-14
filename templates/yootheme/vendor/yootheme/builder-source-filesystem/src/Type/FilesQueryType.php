<?php

namespace YOOtheme\Builder\Source\Filesystem\Type;

use YOOtheme\Builder\Source\Filesystem\FileHelper;

class FilesQueryType
{
    /**
     * @return array
     */
    public static function config()
    {
        return [

            'fields' => [

                'files' => [

                    'type' => [
                        'listOf' => 'File',
                    ],

                    'args' => [
                        'pattern' => [
                            'type' => 'String',
                        ],
                        'offset' => [
                            'type' => 'Int',
                        ],
                        'limit' => [
                            'type' => 'Int',
                        ],
                        'order' => [
                            'type' => 'String',
                        ],
                        'order_direction' => [
                            'type' => 'String',
                        ],
                    ],

                    'metadata' => [
                        'label' => 'Files',
                        'group' => 'External',
                        'fields' => [
                            'pattern' => [
                                'label' => 'Path Pattern',
                                'description' => 'Pick a folder to load file content dynamically. Alternatively, set a path <a href="https://www.php.net/manual/en/function.glob.php" target="_blank">glob pattern</a> to filter files. For example <code>files/*.zip</code> or <code>images/*.{jpg,png}</code>. The path is relative to the system folder. To relate the path to the current child theme use <code>~theme/*</code>.',
                                'type' => 'select-file',
                            ],
                            '_offset' => [
                                'description' => 'Set the starting point and limit the number of files.',
                                'type' => 'grid',
                                'width' => '1-2',
                                'fields' => [
                                    'offset' => [
                                        'label' => 'Start',
                                        'type' => 'number',
                                        'default' => 0,
                                        'modifier' => 1,
                                        'attrs' => [
                                            'min' => 1,
                                            'required' => true,
                                        ],
                                    ],
                                    'limit' => [
                                        'label' => 'Quantity',
                                        'type' => 'limit',
                                        'default' => 10,
                                        'attrs' => [
                                            'min' => 1,
                                        ],
                                    ],
                                ],
                            ],
                            '_order' => [
                                'type' => 'grid',
                                'width' => '1-2',
                                'description' => 'The Default order will follow the order set by the brackets or fallback to the default files order set by the system.',
                                'fields' => [
                                    'order' => [
                                        'label' => 'Order',
                                        'type' => 'select',
                                        'default' => 'name',
                                        'options' => [
                                            'Default' => 'default',
                                            'Alphabetical' => 'name',
                                            'Random' => 'rand',
                                        ],
                                    ],
                                    'order_direction' => [
                                        'label' => 'Direction',
                                        'type' => 'select',
                                        'default' => 'ASC',
                                        'options' => [
                                            'Ascending' => 'ASC',
                                            'Descending' => 'DESC',
                                        ],
                                        'enable' => 'order != "rand"',
                                    ],
                                ],
                            ],
                        ],
                    ],

                    'extensions' => [
                        'call' => __CLASS__ . '::resolve',
                    ],

                ],

            ],

        ];
    }

    public static function resolve($root, array $args)
    {
        return FileHelper::query($args);
    }
}

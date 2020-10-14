<?php

namespace YOOtheme\Builder\Source\Filesystem;

class SourceListener
{
    public static function initSource($source)
    {
        $source->queryType(Type\FileQueryType::config());
        $source->queryType(Type\FilesQueryType::config());
        $source->objectType('File', Type\FileType::config());
    }
}

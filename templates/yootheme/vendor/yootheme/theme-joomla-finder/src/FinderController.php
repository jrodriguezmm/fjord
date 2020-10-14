<?php

namespace YOOtheme\Theme\Joomla;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Filesystem\File as JFile;
use Joomla\CMS\Filesystem\Path as JPath;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\User\User;
use Joomla\Input\Input;
use YOOtheme\File;
use YOOtheme\Http\Request;
use YOOtheme\Http\Response;
use YOOtheme\Path;
use YOOtheme\Str;

class FinderController
{
    public static function index(Request $request, Response $response, Input $input)
    {
        $params = ComponentHelper::getParams('com_media');

        // get media root and current path
        $root = Path::resolve(JPATH_ROOT, $params->get('file_path'));
        $path = Path::join($root, $input->get('folder', '', 'path'));

        if (!Str::startsWith($path, $root)) {
            $path = $root;
        }

        $files = [];

        foreach (File::listDir($path, true) as $file) {

            $filename = basename($file);

            // skip index and hidden files
            if ($filename == 'index.html' || Str::startsWith($filename, '.')) {
                continue;
            }

            $files[] = [
                'name' => $filename,
                'path' => Path::relative($root, $file),
                'url' => Path::relative(JPATH_ROOT, $file),
                'type' => File::isDir($file) ? 'folder' : 'file',
                'size' => HTMLHelper::_('number.bytes', File::getSize($file)),
            ];
        }

        return $response->withJson($files);
    }

    public static function rename(Request $request, Response $response, User $user)
    {
        $params = ComponentHelper::getParams('com_media');

        if (!$user->authorise('core.create', 'com_media') || !$user->authorise('core.delete', 'com_media')) {
            $request->abort(403, 'Insufficient User Rights.');
        }

        $newName = $request('newName');
        $allowed = "{$params->get('upload_extensions')},svg";
        $extension = File::getExtension($newName);
        $isValidFilename = !empty($newName)
            && (empty($extension) || in_array($extension, explode(',', $allowed)))
            && (defined('PHP_WINDOWS_VERSION_MAJOR')
                ? !preg_match('#[\\/:"*?<>|]#', $newName)
                : strpos($newName, '/') === false);

        if (!$isValidFilename) {
            $request->abort(400, 'Invalid file name.');
        }

        $root = Path::resolve(JPATH_ROOT, $params->get('file_path'));
        $oldFile = Path::resolve($root, JPath::clean($request('oldFile')));
        $path = dirname($oldFile);
        $newPath = Path::resolve($path, $newName);

        if (!Str::startsWith($path, $root) || $path !== dirname($newPath)) {
            $request->abort(400, 'Invalid path.');
        }

        if (!JFile::move($oldFile, $newPath)) {
            $request->abort(500, 'Error writing file.');
        }

        return $response->withJson('Successfully renamed.');
    }
}

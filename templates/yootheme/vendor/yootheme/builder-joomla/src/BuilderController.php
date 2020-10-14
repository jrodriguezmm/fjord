<?php

namespace YOOtheme\Builder\Joomla;

use Joomla\CMS\Application\CMSApplication;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Filesystem\File;
use Joomla\CMS\Filesystem\Path;
use Joomla\CMS\Helper\MediaHelper;
use Joomla\CMS\Http\HttpFactory;
use YOOtheme\Config;
use YOOtheme\Http\Request;
use YOOtheme\Http\Response;
use YOOtheme\Http\Uri;

class BuilderController
{
    /**
     * @param Request        $request
     * @param Response       $response
     * @param CMSApplication $app
     * @param Config         $config
     *
     * @throws \Exception
     *
     * @return Response
     */
    public static function loadImage(Request $request, Response $response, CMSApplication $app, Config $config)
    {
        $src = $request('src');
        $md5 = $request('md5');

        $http = HttpFactory::getHttp();
        $params = ComponentHelper::getParams('com_media');

        $uri = new Uri($src);
        $file = basename($uri->getPath());

        if ($uri->getHost() === 'images.unsplash.com') {
            $file .= ".{$uri->getQueryParam('fm', 'jpg')}";
        }

        $file = File::makeSafe($file);
        $path = Path::check(rtrim(implode('/', [JPATH_ROOT, $params->get('image_path'), $config('~theme.media_folder')]), '/\\'));

        try {

            // file already exists?
            while ($iterate = @md5_file("{$path}/{$file}")) {

                if ($iterate === $md5 || is_null($md5)) {
                    return $response->withJson(strtr(substr("{$path}/{$file}", strlen(JPATH_ROOT) + 1), '\\', '/'));
                }

                $file = preg_replace_callback('/-?(\d*)(\.[^.]+)?$/', function ($match) {
                    return sprintf('-%02d%s', intval($match[1]) + 1, isset($match[2]) ? $match[2] : '');
                }, $file, 1);
            }

            // create file
            File::write("{$path}/{$file}", '');

            // download file
            $tmp = "{$path}/" . uniqid();
            $res = $http->get($src);

            $request->abortIf($res->code != 200, $res->code, 'Download failed.')
                    ->abortIf(!File::write($tmp, $res->body), 500, 'Error writing file.');

            // allow .svg files
            $params->set('upload_extensions', "{$params->get('upload_extensions')},svg");

            // ignore MIME-type check for .svg files
            $params->set('ignore_extensions', $params->get('ignore_extensions') ? "{$params->get('ignore_extensions')},svg" : 'svg');

            if (!(new MediaHelper())->canUpload(['name' => $file, 'tmp_name' => $tmp, 'size' => filesize($tmp)])) {

                File::delete($tmp);

                $queue = $app->getMessageQueue();
                $message = count($queue) ? "{$file}: {$queue[0]['message']}" : '';

                $request->abort(500, $message);
            }

            // move file
            $request->abortIf(!File::move($tmp, "{$path}/{$file}"), 500, 'Error writing file.');

            return $response->withJson(strtr(substr("{$path}/{$file}", strlen(JPATH_ROOT) + 1), '\\', '/'));

        } catch (\Exception $e) {

            // delete incomplete file
            File::delete("{$path}/{$file}");

            throw $e;
        }
    }
}

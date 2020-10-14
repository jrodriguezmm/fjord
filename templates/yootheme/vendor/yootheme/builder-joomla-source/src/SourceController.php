<?php

namespace YOOtheme\Builder\Joomla\Source;

use YOOtheme\Http\Request;
use YOOtheme\Http\Response;

class SourceController
{
    /**
     * @param Request  $request
     * @param Response $response
     *
     * @throws \Exception
     *
     * @return Response
     */
    public static function articles(Request $request, Response $response)
    {
        $titles = [];

        foreach (ArticleHelper::get($request('ids')) as $article) {
            $titles[$article->id] = $article->title;
        }

        return $response->withJson((object) $titles);
    }
}

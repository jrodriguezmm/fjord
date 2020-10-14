<?php

namespace YOOtheme\Theme\Joomla;

use YOOtheme\Builder;
use YOOtheme\Database;
use YOOtheme\Http\Request;
use YOOtheme\Http\Response;

class ModulesController
{
    public static function getModule(Request $request, Response $response, Database $db, Builder $builder)
    {
        $query = 'SELECT id, content FROM @modules WHERE id = :id';
        $module = $db->fetchObject($query, ['id' => $request('id')]);
        $module->content = $builder->load($module->content);

        return $response->withJson($module);
    }

    public static function saveModule(Request $request, Response $response, Database $db)
    {
        $db->update('@modules', ['content' => json_encode($request('content'))], ['id' => $request('id')]);

        return $response->withJson(['message' => 'success']);
    }

    public static function getModules(Request $request, Response $response, ModulesHelper $helper)
    {
        return $response->withJson($helper->getModules());
    }

    public static function getPositions(Request $request, Response $response, ModulesHelper $helper)
    {
        return $response->withJson($helper->getPositions());
    }
}

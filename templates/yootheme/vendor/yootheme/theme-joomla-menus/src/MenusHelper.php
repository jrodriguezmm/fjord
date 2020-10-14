<?php

namespace YOOtheme\Theme\Joomla;

use Joomla\CMS\Menu\AbstractMenu;

class MenusHelper
{
    public function getMenus()
    {
        return array_map(function ($menu) {
            return [
                'id' => $menu->value,
                'name' => $menu->text,
            ];
        }, \JHtmlMenu::menus());
    }

    public function getItems()
    {
        return array_values(array_map(function ($item) {
            return [
                'id' => $item->id,
                'title' => $item->title,
                'level' => $item->level > 1 ? 1 : 0,
                'menu' => $item->menutype,
                'parent' => $item->parent_id,
            ];
        }, AbstractMenu::getInstance('site')->getMenu()));
    }
}

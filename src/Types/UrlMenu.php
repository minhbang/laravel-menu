<?php

namespace Minhbang\Menu\Types;

use Minhbang\Menu\Contracts\Type;

/**
 * Class UrlMenu
 *
 * @package Minhbang\Menu\Types
 */
class UrlMenu extends MenuType
{
    /**
     * @param \Minhbang\Menu\Menu $menu
     *
     * @return string
     */
    public function buildUrl($menu)
    {
        return $menu->params['url'];
    }

    /**
     * @return string
     */
    protected function formView()
    {
        return 'menu::type.url_form';
    }

    /**
     * @return array
     */
    protected function paramsAttributes()
    {
        return [
            ['name' => 'url', 'title' => __('Url'), 'rule' => 'required', 'default' => '#'],
        ];
    }
}
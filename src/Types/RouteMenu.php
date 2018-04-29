<?php

namespace Minhbang\Menu\Types;

use Minhbang\Kit\Support\HasRouteAttribute;
use Minhbang\Menu\Contracts\Type;

/**
 * Class RouteMenu
 *
 * @package Minhbang\Menu\Types
 */
class RouteMenu extends MenuType
{
    use HasRouteAttribute;

    public function formOptions()
    {
        return ['height' => 320] + parent::formOptions();
    }

    /**
     * @param \Minhbang\Menu\Menu $menu
     *
     * @return string
     */
    public function buildUrl($menu)
    {
        return $this->getRouteUrl($menu->params['name']);
    }

    protected function formView()
    {
        return 'menu::type.route_form';
    }

    protected function paramsAttributes()
    {
        return [
            ['name' => 'name', 'title' => __('Route name'), 'rule' => 'required', 'default' => '#'],
        ];
    }
}
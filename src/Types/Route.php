<?php
namespace Minhbang\Menu\Types;

use Minhbang\Menu\Contracts\Type;
use Route as RouteManager;

/**
 * Class Route
 *
 * @package Minhbang\Menu\Types
 */
class Route implements Type
{
    /**
     * @param string $params
     *
     * @return string
     */
    public function url($params)
    {
        return RouteManager::has($params) ? route($params) : "#route_$params";
    }

    /**
     * @return string
     */
    public function title()
    {
        return trans('menu::type.route');
    }

    /**
     * @return string
     */
    public function titleParams()
    {
        return trans('menu::type.route_params');
    }
}
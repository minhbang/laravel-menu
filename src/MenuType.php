<?php
namespace Minhbang\LaravelMenu;

use Route;

/**
 * Class MenuType
 *
 * @package Minhbang\LaravelMenu
 */
class MenuType
{
    /**
     * @var array Danh sách menu type, dạng ['type' => 'type name']
     */
    public $lists;

    function __construct()
    {
        $this->lists = $this->types() + [
                'url'   => trans('menu::type.url'),
                'route' => trans('menu::type.route'),
            ];
    }

    /** Add custom types
     *
     * @return array
     */
    protected function types()
    {
        return [];
    }

    /**
     * @param string $type
     * @param string $params
     * @return string
     */
    public function buildUrl($type, $params)
    {
        $method = 'urlOf' . studly_case($type) . 'Type';
        return method_exists($this, $method) ? $this->$method($params) : "#{$params}";
    }

    /**
     * @param $params
     * @return string
     */
    protected function urlOfUrlType($params)
    {
        return $params;
    }

    /**
     * @param $params
     * @return string
     */
    protected function urlOfRouteType($params)
    {
        return Route::has($params) ? route($params) : "#route_$params";
    }
}
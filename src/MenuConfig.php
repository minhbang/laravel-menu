<?php
namespace Minhbang\LaravelMenu;

use Route;

/**
 * Class MenuConfig
 *
 * @package Minhbang\LaravelMenu
 */
class MenuConfig
{
    /**
     * Get menu list config
     * Todo: cho phép cấu hình menu options, lưu DB
     *
     * @return array
     */
    public function getLists()
    {
        $lists = $this->lists() + [
                'main'   => '{"max_depth":2}',
                'footer' => '{"max_depth":2}',
                'bottom' => '{"max_depth":1}',
            ];
        foreach ($lists as $menu => $options) {
            if ($options === false) {
                unset($lists[$menu]);
            }
        }
        return $lists;
    }

    /**
     * Get types list
     *
     * @return array
     */
    public function getTypes()
    {
        return $this->types() + [
            'url'   => trans('menu::type.url'),
            'route' => trans('menu::type.route'),
        ];
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

    /** Set custom types, dạng ['type' => 'type name']
     *
     * @return array
     */
    protected function types()
    {
        return [];
    }

    /** Set custom menu, dạng ['menu' => 'json options']
     *
     * @return array
     */
    protected function lists()
    {
        return [];
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
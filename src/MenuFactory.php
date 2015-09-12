<?php
namespace Minhbang\LaravelMenu;

use Route;

/**
 * Class MenuFactory
 *
 * @package Minhbang\LaravelMenu
 */
class MenuFactory
{
    /**
     * Get menu list, [id => options]
     *   Options:
     * - max_depth (integer): 'cấp' tối da
     * - presenter (string): Menu Presenter class 'id', cấu hình trong 'config/menu.php',
     * - tag (string): Html tag name, empty => no container tag
     * - attributes (array): Html Tag attributes
     * - item_tag (string): Html item tag name => no container tag
     * - item_attributes (array): Html Item Tag attributes
     *
     * Todo: cho phép cấu hình menu options, lưu DB
     *
     * @return array
     */
    public function getLists()
    {
        $lists = $this->lists() + [
                'top'    => '{"max_depth":1,"presenter":"list1","tag":"ul","item_tag":"li","attributes":{"class":"nav navbar-nav pull-left"}}',
                'main'   => '{"max_depth":2,"attributes":{"class":"nav navbar-nav"}}',
                'footer' => '{"max_depth":2,"presenter":"list2","tag":"","item_tag":"div","item_attributes":{"class":"col-md-2 col-sm-6"}}',
                'bottom' => '{"max_depth":1,"presenter":"list1","tag":"ul","item_tag":"li","attributes":{"class":"pull-right list-inline"}}',
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
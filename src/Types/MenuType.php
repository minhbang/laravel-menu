<?php namespace Minhbang\Menu\Types;

use Validator;

/**
 * Class MenuType
 *
 * @property-read string $name
 * @property-read string $title
 * @property-read string $icon
 * @property-read array $paramsRules
 * @property-read array $paramsAttributes
 * @property-read array $paramsDefault
 * @package Minhbang\Menu\Types
 */
abstract class MenuType
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $icon;

    /**
     * @var array
     */
    protected $paramsAttributes;

    /**
     * @var array
     */
    protected $paramsRules;

    /**
     * @var array
     */
    protected $paramsDefault;

    /**
     * Form View cấu hình trong backend
     *
     * @return string
     */
    abstract protected function formView();

    /**
     * Tạo URL từ menu
     *
     * @param \Minhbang\Menu\Menu $menu
     *
     * @return string
     *
     */
    abstract protected function buildUrl($menu);

    /**
     * Định nghĩa các attributes của params, định dạng:
     * [
     *      ['name' => string, 'title' => string, 'rule' => string, 'default' => mixed],
     *      ...
     * ]
     *
     * @return array
     */
    abstract protected function paramsAttributes();

    /**
     * Xét điều kiện Visible của menu
     *
     * @param \Minhbang\Menu\Menu $menu
     *
     * @return bool
     */
    protected function visible($menu)
    {
        return $menu->configured > 0;
    }

    /**
     * Các thuộc tính CỐ ĐỊNH giá trị, 'name' => 'value'
     *
     * @return array
     */
    public function paramsFixed()
    {
        return [];
    }

    /**
     * Tham số cho modal form cấu hình menu trong backend
     *
     * @return array
     */
    public function formOptions()
    {
        return [];
    }

    /**
     * Tạo URL từ menu
     *
     * @param \Minhbang\Menu\Menu $menu
     *
     * @return string
     *
     */
    public function url($menu)
    {
        return $menu->configured ? $this->buildUrl($menu) : '#empty';
    }

    /**
     * Form cấu hình menu trong backend
     *
     * @param \Minhbang\Menu\Menu $menu
     * @param string $route_prefix
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function form($menu, $route_prefix = '')
    {
        $url = route("{$route_prefix}backend.menu.update_params", ['menu' => $menu->id]);
        $params = $menu->params;
        $labels = $menu->typeInstance()->paramsAttributes;

        return view($this->formView(), compact('menu', 'url', 'params', 'labels'));
    }

    /**
     * Kiểm tra dữ liệu nhập
     *
     * @param array $params
     *
     * @return \Illuminate\Support\MessageBag
     */
    public function validate($params)
    {
        return Validator::make($params, $this->paramsRules, [], $this->paramsAttributes)->errors();
    }

    /**
     * Menu Type constructor.
     *
     * @param string $name
     * @param string $title
     * @param string $icon
     */
    public function __construct($name, $title, $icon)
    {
        $this->name = $name;
        $this->title = $title;
        $this->icon = $icon;

        $except = array_keys($this->paramsFixed());
        $attributes = collect($this->paramsAttributes());
        $this->paramsAttributes = $attributes->pluck('title', 'name')->except($except)->all();
        $this->paramsRules = $attributes->pluck('rule', 'name')->except($except)->all();
        $this->paramsDefault = $attributes->pluck('default', 'name')->except($except)->all();
    }

    function __get($name)
    {
        return in_array($name, [
            'name',
            'title',
            'icon',
            'paramsRules',
            'paramsAttributes',
            'paramsDefault',
        ]) ? $this->$name : null;
    }
}
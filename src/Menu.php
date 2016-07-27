<?php
namespace Minhbang\Menu;

use Request;

/**
 * Class Menu
 *
 * @package Minhbang\Menu
 */
class Menu
{
    /**
     * @var array
     */
    protected $actives = [];

    /**
     * Danh sách menu presenter classes
     *
     * @var array
     */
    protected $presenters = [];

    /**
     * Menu types list
     *
     * @var array
     */
    protected $types;
    /**
     * @var array
     */
    protected $cached_types = [];

    /**
     * @var array
     */
    protected $settings;

    /**
     * Cached Menu manager instance
     *
     * @var  array
     */
    protected $lists = [];

    /**
     * Danh sách menu display names
     *
     * @var  array
     */
    protected $titles = [];

    /**
     * Menu constructor.
     *
     * @param array $actives
     * @param array $presenters
     * @param array $types
     * @param array $settings
     */
    public function __construct($actives = [], $presenters = [], $types = [], $settings = [])
    {
        $this->actives = $actives;
        $this->presenters = $presenters;
        $this->types = $types;
        $this->settings = $settings;
        foreach ($this->settings as $menu => $setting) {
            $this->titles[$menu] = trans("menu::common.{$menu}");
        }
    }

    /**
     * Kiểm tra $uri active
     *
     * @param string $uri
     *
     * @return bool
     */
    public function isActive($uri)
    {
        $current = str_replace(url('/'), '', Request::url());
        if (empty($current)) {
            $active = $uri === '/';
        } else {
            if (isset($this->actives[$uri])) {
                $patterns = $this->actives[$uri];
                if (is_string($patterns)) {
                    $patterns = [$patterns];
                }
                foreach ($patterns as $pattern) {
                    if (str_is($pattern, $current)) {
                        return true;
                    }
                }
            }
            $active = $uri !== '/' && str_is("{$uri}*", $current);
        }
        return $active;
    }

    /**
     * @param string $type
     * @param string $params
     *
     * @return string
     */
    public function buildUrl($type, $params)
    {
        return $this->getType($type)->url($params);
    }

    /**
     * Get menu manager instance
     *
     * @param string $name
     *
     * @return \Minhbang\Menu\Manager
     */
    public function get($name)
    {
        if (!isset($this->settings[$name])) {
            abort(500, 'Invalid Menu name!');
        }
        if (!isset($this->lists[$name])) {

            $this->lists[$name] = new Manager(
                $name,
                $this->newObject($this->settings[$name]['presenter'], $this->presenters, 'Presenter'),
                $this->getOptions($name)
            );
        }
        return $this->lists[$name];
    }

    /**
     * @param $name
     *
     * @return string
     */
    public function render($name)
    {
        return $this->get($name)->html();
    }

    /**
     * Danh sách tên menu
     *
     * @param null|string $name
     * @param mixed $default
     *
     * @return array|mixed
     */
    public function titles($name = null, $default = null)
    {
        if ($name) {
            return isset($this->titles[$name]) ? $this->titles[$name] : $default;
        } else {
            return $this->titles;
        }
    }

    /**
     * Danh sách tên các loại menu
     */
    public function types()
    {
        $lists = [];
        foreach ($this->types as $type => $class) {
            $lists[$type] = $this->getType($type)->title();
        }
        return $lists;
    }

    /**
     * Danh sách tên params của các loại menu
     */
    public function typeParams()
    {
        $lists = [];
        foreach ($this->types as $type => $class) {
            $lists[$type] = $this->getType($type)->titleParams();
        }
        return $lists;
    }

    /**
     * @param $name
     *
     * @return \Minhbang\Menu\Contracts\Type
     */
    protected function getType($name)
    {
        if (!isset($this->cached_types[$name])) {
            $this->cached_types[$name] = $this->newObject($name, $this->types, 'Type');
        }
        return $this->cached_types[$name];
    }

    /**
     * @param string $name
     *
     * @return string
     */
    protected function getOptions($name)
    {
        if ($options = array_get($this->settings, "{$name}.options")) {
            return $options;
        } else {
            abort(500, "Can't get menu setting!");
        }
    }

    /**
     * @param string $name
     * @param arrray $lists
     * @param string $title
     *
     * @return \Minhbang\Menu\Contracts\Type|\Minhbang\Menu\Contracts\Presenter
     */
    protected function newObject($name, $lists, $title)
    {
        if (isset($lists[$name])) {
            return new $lists[$name]();
        } else {
            abort(500, "Invalid Menu {$title} name!");
        }
    }
}
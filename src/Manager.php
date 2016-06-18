<?php
namespace Minhbang\Menu;

use Minhbang\Menu\Roots\UneditableRoot;
use Request;
use Minhbang\Menu\Roots\EditableRoot;

/**
 * Class Manager
 * Quản lý tất cả menus
 *
 * @package Minhbang\Menu
 */
class Manager
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
     * Danh sách Root của các menus
     *
     * @var \Minhbang\Menu\Roots\EditableRoot[]
     */
    protected $lists = [];

    /**
     * Danh sách menu display names
     *
     * @var  array
     */
    protected $titles = [];
    /**
     * @var string
     */
    protected $first_editable;

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
    }

    /**
     * @param array $data
     */
    public function registerMenus($data)
    {
        if (is_array($data)) {
            $data = $data + ['zones' => [], 'presenters' => [], 'types' => [], 'menus' => []];
            foreach ($data['zones'] as $name => $settings) {
                $this->addZone($name, $settings);
            }
            foreach ($data['presenters'] as $name => $presenter) {
                $this->addPresenter($name, $presenter);
            }
            foreach ($data['types'] as $name => $type) {
                $this->addType($name, $type);
            }
            foreach ($data['menus'] as $name => $data) {
                $this->addItem($name, $data);
            }
        }
    }

    public function addBuildInItems()
    {
        $this->addItems(config('menu.menus'));
    }

    /**
     * @param array $items
     */
    public function addItems($items)
    {
        if (is_array($items)) {
            foreach ($items as $name => $data) {
                $this->addItem($name, $data);
            }
        }
    }

    /**
     * @param string $name
     * @param array $data
     */
    public function addItem($name, $data)
    {
        $segments = explode('.', $name);
        if (count($segments) > 2) {
            $zone = array_shift($segments);
            $menu = $zone . '.' . array_shift($segments);
            if (!$this->get($menu)->isEditable()) {
                $this->get($menu)->addItem($segments, $data);
            }
        }
    }

    /**
     * Thêm một menu zone
     *
     * @param string $name
     * @param array $settings
     */
    public function addZone($name, $settings)
    {
        if ($name && $settings) {
            $this->settings[$name] = $settings;
        }
    }

    /**
     * @param string $name
     * @param string $presenter
     */
    public function addPresenter($name, $presenter)
    {
        if ($name && $presenter) {
            $this->presenters[$name] = $presenter;
        }
    }

    /**
     * @param string $name
     * @param string $type
     */
    public function addType($name, $type)
    {
        if ($name && $type) {
            $this->types[$name] = $type;
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
     * @param string $name
     *
     * @return \Minhbang\Menu\Roots\EditableRoot|\Minhbang\Menu\Roots\UneditableRoot
     */
    public function get($name)
    {
        if (!isset($this->lists[$name])) {
            $settings = array_get($this->settings, $name);
            abort_unless($settings, 500, 'Invalid Menu name!');
            $presenter = $this->newObject($settings['presenter'], $this->presenters, 'Presenter');
            $this->lists[$name] = array_get($settings, 'editable') ?
                new EditableRoot($name, $presenter, $settings) :
                new UneditableRoot($name, $presenter, $settings);
        }

        return $this->lists[$name];
    }

    /**
     * @param $name
     * @param array $options
     *
     * @return string
     */
    public function render($name, $options = [])
    {
        return $this->has($name) ? $this->get($name)->html($options) : null;
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
        if (empty($this->titles)) {
            foreach ($this->settings as $zone => $menus) {
                $this->titles[$zone] = [];
                foreach ($menus as $menu => $setting) {
                    if (array_get($setting, 'editable')) {
                        $this->titles[$zone][$menu] = trans("menu::common.menus.{$menu}");
                        if (is_null($this->first_editable)) {
                            $this->first_editable = "{$zone}.{$menu}";
                        }
                    }
                }
            }
        }

        return array_get($this->titles, $name, $default);
    }

    /**
     * @return string
     */
    public function firstEditable()
    {
        return $this->titles() ? $this->first_editable : null;
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function has($name)
    {
        return $name && array_get($this->settings, $name);
    }

    /**
     * Danh sách tên loại menu
     *
     * @param null|string $type
     * @param mixed $default
     *
     * @return array|mixed
     */
    public function types($type = null, $default = null)
    {
        $lists = [];
        foreach ($this->types as $type => $class) {
            $lists[$type] = $this->getType($type)->title();
        }

        return array_get($lists, $type, $default);
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
     * @param array $lists
     * @param string $title
     *
     * @return \Minhbang\Menu\Contracts\Type|\Minhbang\Menu\Contracts\Presenter
     */
    protected function newObject($name, $lists, $title)
    {
        abort_unless(isset($lists[$name]), 500, "Invalid Menu {$title} name!");

        return new $lists[$name]();
    }
}

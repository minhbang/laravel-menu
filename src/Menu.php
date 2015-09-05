<?php
namespace Minhbang\LaravelMenu;

use Request;
use Minhbang\LaravelKit\Traits\Presenter\NestablePresenter;

/**
 * Class Menu
 *
 * @package Minhbang\LaravelMenu
 */
class Menu
{
    use NestablePresenter;
    /**
     * @var array
     */
    protected $actives = [];

    /**
     * @var \Minhbang\LaravelMenu\MenuConfig
     */
    protected $config;

    /**
     * @var array
     */
    protected $presenters = [];
    /**
     * Menu types list
     *
     * @var array
     */
    public $types;

    /**
     * Danh sách menu
     *
     * @var  array
     */
    public $lists;

    /**
     * Danh sách menu label
     *
     * @var  array
     */
    public $labels = [];

    /**
     * @param array $actives
     * @param \Minhbang\LaravelMenu\MenuConfig $config
     * @param array $presenters
     */
    function __construct($actives, $config, $presenters)
    {
        $this->actives = $actives;
        $this->config = $config;
        $this->types = $config->getTypes();
        $this->lists = $config->getLists();
        foreach ($this->lists as $menu => $options) {
            $this->labels[$menu] = trans("menu::common.{$menu}");
        }
        foreach ($presenters as $name => $class_name) {
            $this->presenters[$name] = new $class_name();
        }
    }

    /**
     * Render html theo format boostrap navbar
     *
     * @param \Minhbang\LaravelMenu\MenuItem|string $root
     * @return string|null
     */
    public function html($root = 'main')
    {
        if (is_string($root)) {
            $root = MenuItem::where('name', $root)->first();
        }
        if ($root) {
            $presenter = $root->getOption('presenter', 'default');
            return $this->presenters[$presenter]->html($root);
        } else {
            return null;
        }
    }

    /**
     * Render html theo định dạng của jquery nestable plugin
     *
     * @see https://github.com/dbushell/Nestable
     * @param \Minhbang\LaravelMenu\MenuItem $root
     * @param int $max_depth
     * @return string
     */
    public function nestable($root, $max_depth)
    {
        return $this->toNestable($root->getImmediateDescendants(), $max_depth);
    }

    /**
     * Kiểm tra $uri active
     *
     * @param string $uri
     * @return string
     */
    public function getActive($uri)
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
                        return ' class="active"';
                    }
                }
            }
            $active = $uri !== '/' && str_is("{$uri}*", $current);
        }
        return $active ? ' class="active"' : '';
    }

    /**
     * @param string $type
     * @param string $params
     * @return string
     */
    public function getUrl($type, $params)
    {
        return $this->config->buildUrl($type, $params);
    }

    /**
     * @param string $type
     * @return bool
     */
    public function hasType($type)
    {
        return isset($this->types[$type]);
    }

    /**
     * @param string $menu
     * @return bool
     */
    public function hasMenu($menu)
    {
        return isset($this->lists[$menu]);
    }

    /**
     * @param string $menu
     * @return \Minhbang\LaravelMenu\MenuItem|null
     */
    public function getMenuRoot($menu = 'main')
    {
        if ($this->hasMenu($menu)) {
            if ($root = MenuItem::where('type', 'menu')->where('name', $menu)->first()) {
                return $root;
            }
            return MenuItem::create(
                [
                    'name'    => $menu,
                    'label'   => $menu,
                    'type'    => 'menu',
                    'params'  => '#',
                    'options' => $this->lists[$menu],
                ]
            );
        } else {
            return null;
        }
    }
}
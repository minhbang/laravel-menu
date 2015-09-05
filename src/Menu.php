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
     * @param array $actives
     * @param \Minhbang\LaravelMenu\MenuConfig $config
     */
    function __construct($actives, $config)
    {
        $this->actives = $actives;
        $this->config = $config;
        $this->types = $config->getTypes();
        $this->lists = $config->getLists();
    }

    /**
     * Render html theo format boostrap navbar
     *
     * @param bool $home thêm home icon
     * @return string
     */
    public function html($home = false)
    {
        $roots = $this->getRoots();
        $html = "<ul class=\"nav navbar-nav\">";
        if ($home) {
            $html .= "<li{$this->getActive(url('/'))}><a href=\"" .
                url('/') . "\" class=\"home\"><span class=\"glyphicon glyphicon-home\"></span></a></li>";
        }
        foreach ($roots as $root) {
            $html .= $root->present()->html;
        }
        $html .= '</ul>';
        return $html;
    }

    /**
     * Render html theo định dạng của jquery nestable plugin
     *
     * @see https://github.com/dbushell/Nestable
     * @return string
     */
    public function nestable()
    {
        return $this->toNestable($this->getRoots());
    }

    /**
     * @return \Minhbang\LaravelMenu\MenuItem[]
     */
    protected function getRoots()
    {
        return MenuItem::roots()->get();
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
     * @return string|null
     */
    public function getTypeName($type)
    {
        return isset($this->types[$type]) ? $this->types[$type] : null;
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
     * @param stirng $menu
     * @return string|null
     */
    public function getMenuName($menu)
    {
        return $this->lists[$menu]['label'];
    }

    /**
     * @param stirng $menu
     * @return string|null
     */
    public function getMenuOptions($menu)
    {
        return $this->lists[$menu]['options'];
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
            if ($root = MenuItem::where('type', 'menu')->where('label', $menu)->first()) {
                return $root;
            }
            return MenuItem::create(
                [
                    'label'   => $menu,
                    'type'    => 'menu',
                    'params'  => '#',
                    'options' => $this->getMenuOptions($menu),
                ]
            );
        } else {
            return null;
        }
    }
}
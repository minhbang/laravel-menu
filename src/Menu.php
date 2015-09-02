<?php
namespace Minhbang\LaravelMenu;

use Request;

/**
 * Class Menu
 *
 * @package Minhbang\LaravelMenu
 */
class Menu
{
    /**
     * @var array
     */
    protected $actives = [];

    /**
     * @var \Minhbang\LaravelMenu\MenuType
     */
    protected $type_manager;

    /**
     * @var array
     */
    public $types;

    /**
     * @param array $actives
     * @param \Minhbang\LaravelMenu\MenuType $type_manager
     */
    function __construct($actives, $type_manager)
    {
        $this->actives = $actives;
        $this->type_manager = $type_manager;
        $this->types = $type_manager->lists;
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
        return $this->type_manager->buildUrl($type, $params);
    }
}
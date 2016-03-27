<?php
namespace Minhbang\Menu;

use Minhbang\Kit\Traits\Presenter\NestablePresenter;
use MenuManager;

/**
 * Class Root
 * Quản lý node gốc của 1 loại Menu
 *
 * @package Minhbang\Menu
 */
class Root
{
    use NestablePresenter;
    /**
     * Node gốc
     *
     * @var \Minhbang\Menu\Menu
     */
    protected $node;

    /**
     * @var \Minhbang\Menu\Contracts\Presenter
     */
    protected $presenter;
    /**
     * @var int
     */
    public $max_depth;

    /**
     * Manager constructor.
     *
     * @param string $name
     * @param string $presenter
     * @param array $options
     */
    function __construct($name, $presenter, $options)
    {
        $this->node = Menu::findRootByNameOrCreate($name, $options);
        $this->max_depth = array_get($options, 'max_depth', config('menu.default_max_depth'));
        $this->presenter = $presenter;
    }

    /**
     * Render html theo định dạng của jquery nestable plugin
     *
     * @see https://github.com/dbushell/Nestable
     * @return string
     */
    public function nestable()
    {
        return $this->toNestable($this->node, $this->max_depth);
    }

    /**
     * Render html menu
     *
     * @return string
     */
    public function html()
    {
        return $this->presenter->html($this);
    }

    /**
     * @return array
     */
    public function types()
    {
        return MenuManager::types();
    }

    /**
     * @return array
     */
    public function typeParams()
    {
        return MenuManager::typeParams();
    }

    /**
     * @return array
     */
    public function titles()
    {
        return MenuManager::titles();
    }

    /**
     * @return string
     */
    public function title()
    {
        return MenuManager::titles($this->node->name);
    }

    /**
     * @return \Minhbang\Menu\Menu
     */
    public function node()
    {
        return $this->node;
    }
}
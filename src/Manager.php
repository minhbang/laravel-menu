<?php
namespace Minhbang\Menu;

use Minhbang\Kit\Traits\Presenter\NestablePresenter;

/**
 * Class Manager
 * Quản lý một loại Menu
 *
 * @package Minhbang\Menu
 */
class Manager
{
    use NestablePresenter;
    /**
     * Menu root node
     *
     * @var \Minhbang\Menu\Item
     */
    protected $root;

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
     * @param string $options
     */
    function __construct($name, $presenter, $options)
    {
        $this->root = Item::firstOrCreate(
            ['name' => $name, 'label' => $name],
            ['type' => '#', 'params' => '#', 'options' => json_encode($options)]
        );
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
        return $this->toNestable($this->root()->getImmediateDescendants(), $this->max_depth);
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
        return app('menu')->types();
    }

    /**
     * @return array
     */
    public function typeParams()
    {
        return app('menu')->typeParams();
    }

    /**
     * @return array
     */
    public function titles()
    {
        return app('menu')->titles();
    }

    /**
     * @return string
     */
    public function title()
    {
        return app('menu')->titles($this->root->name);
    }

    /**
     * @return \Minhbang\Menu\Item
     */
    public function root()
    {
        return $this->root;
    }
}
<?php
namespace Minhbang\Menu;

use DB;
use Minhbang\Kit\Support\VnString;

/**
 * Class Seeder
 *
 * @package Minhbang\Menu
 */
class Seeder
{
    /**
     * @param string $name
     * @param array $options
     *
     * @return \Minhbang\Menu\Menu
     */
    protected function seedMenuRoot($name, $options)
    {
        return Menu::firstOrCreate(
            ['name' => $name, 'label' => $name],
            ['type' => '#', 'params' => '#', 'options' => json_encode($options)]
        );
    }

    /**
     * @param string|null $label
     *
     * @return \Minhbang\Menu\Menu
     */
    protected function seedMenuItem($label)
    {
        return Menu::create([
            'name'    => VnString::to_slug($label),
            'label'   => $label,
            'type'    => 'url',
            'params'  => '#',
            'options' => null,
        ]);
    }

    /**
     * @param \Minhbang\Menu\Menu $root
     * @param array $items
     */
    protected function seedMenu($root, $items)
    {
        foreach ($items as $key => $item) {
            if (is_string($item)) {
                $child = $this->seedMenuItem($item);
                $child->makeChildOf($root);
            } else {
                $child = $this->seedMenuItem($key);
                $child->makeChildOf($root);
                $this->seedMenu($child, $item);
            }
        }
    }

    /**
     * @param array $data
     * @param array $settings
     */
    public function seed($data = [], $settings = [])
    {
        DB::table('menus')->truncate();

        foreach ($data as $name => $items) {
            $this->seedMenu($this->seedMenuRoot($name, array_get($settings, "{$name}.options")), $items);
        }
    }
}
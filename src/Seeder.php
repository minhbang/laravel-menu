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
        return Menu::firstOrCreate(['name' => $name, 'label' => $name], [
            'type' => '#',
            'params' => '#',
            'options' => json_encode($options),
            'configured' => 1,
        ]);
    }

    /**
     * @param string|null $label
     * @param string $url
     *
     * @return \Minhbang\Menu\Menu
     */
    protected function seedMenuItem($label, $url = '#')
    {
        return Menu::create([
            'name' => VnString::to_slug($label),
            'label' => $label,
            'type' => 'url',
            'params' => ['url' => $url],
            'options' => null,
            'configured' => 1,
        ]);
    }

    /**
     * 1 item: label, url, items
     * hoặc chỉ string ~ label
     *
     * @param \Minhbang\Menu\Menu $root
     * @param array $items
     */
    protected function seedMenu($root, $items)
    {
        foreach ($items as $item) {
            $item = is_string($item) ? ['label' => $item] : (array) $item;
            $item = $item + ['url' => '#', 'items' => []];
            $child = $this->seedMenuItem($item['label'], $item['url']);
            $child->makeChildOf($root);
            $this->seedMenu($child, $item['items']);
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
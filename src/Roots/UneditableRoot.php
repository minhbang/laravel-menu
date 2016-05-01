<?php
namespace Minhbang\Menu\Roots;

use Minhbang\Menu\Contracts\Root;

/**
 * Class UneditableRoot
 * Root của Uneditable Menu: menu tĩnh, đăng ký qua code hoặc build-in...
 *
 * @package Minhbang\Menu\Roots
 */
class UneditableRoot implements Root
{
    /**
     * @var \Minhbang\Menu\Contracts\Presenter
     */
    protected $presenter;
    /**
     * @var array
     */
    protected $settings;
    /**
     * @var array
     */
    protected $items = [];

    /**
     * UneditableRoot constructor.
     *
     * @param string $name
     * @param \Minhbang\Menu\Contracts\Presenter $presenter
     * @param array $settings
     */
    public function __construct($name, $presenter, $settings)
    {
        $this->presenter = $presenter;
        $this->settings = $settings;
        if ($items = array_get($settings, 'items')) {
            foreach ($items as $menu => $data) {
                $this->addItem($menu, $data);
            }
        }
    }

    /**
     * @return bool
     */
    public function isEditable()
    {
        return false;
    }


    /**
     * Cho phép Group (level 1) empty không?
     *
     * @param bool $removeEmptyGroup
     * @param string $sortBy
     * @param bool $sortAsc
     *
     * @return array
     */
    public function items($removeEmptyGroup = true, $sortBy = 'priority', $sortAsc = true)
    {
        $items = $this->items;
        $this->export($items, $removeEmptyGroup, $sortBy, $sortAsc);

        return $items;
    }


    /**
     * @param string $name
     * @param string $default
     *
     * @return mixed
     */
    public function settings($name = null, $default = null)
    {
        return array_get($this->settings, $name, $default);
    }

    /**
     * Render html menu
     *
     * @param array $options
     *
     * @return string
     */
    public function html($options = [])
    {
        return $this->presenter->html($this, $options);
    }

    /**
     * Add $item menu tên $name("dot" notation)
     * $data = [
     *     'url' => string,
     *     'label' => string,
     *     'icon' => string,
     *     'active' => string | array,
     * ]
     *
     * @param string|array $name
     * @param array $data
     */
    public function addItem($name, $data = [])
    {
        $keys = is_string($name) ? explode('.', $name) : $name;
        $key = array_pop($keys);
        $parent = $this->getParentKey($keys);
        if (empty($parent) || array_has($this->items, $parent)) {
            $item = $this->buildItem($data);
            array_set($this->items, $this->getParentKey($parent) . $key, $item);
            $this->syncActive($keys, $item['active']);
        }
    }

    /**
     * @param array $data
     *
     * @return array
     */
    protected function buildItem($data)
    {
        $item = $data + ['url' => '#', 'active' => [], 'items' => []];
        if (is_string($item['active'])) {
            $item['active'] = [$item['active']];
        }

        return $item;
    }

    /**
     * Sync $active to $parents
     *
     * @param array $parents
     * @param array $active
     */
    protected function syncActive($parents, $active)
    {
        while ($parents && $active) {
            $key = $this->getParentKey($parents) . '.active';
            $parent_active = array_get($this->items, $key, []);
            array_set($this->items, $key, $this->mergeActive($parent_active, $active));
            array_pop($parents);
        }
    }

    /**
     * Lấy array key từ $parents
     *
     * @param array|string $parents
     *
     * @return string
     */
    protected function getParentKey($parents = [])
    {
        return is_string($parents) ? ($parents ? "{$parents}.items." : '') : implode('.items.', $parents);
    }

    /**
     * @param array $active1
     * @param array $active2
     *
     * @return array
     */
    protected function mergeActive($active1, $active2)
    {
        return array_unique(array_merge($active1, $active2));
    }

    /**
     * @param array $items
     * @param bool $removeEmptyGroup
     * @param string $sortBy
     * @param bool $sortAsc
     */
    protected function export(&$items, $removeEmptyGroup, $sortBy, $sortAsc)
    {
        if (!$items) {
            return;
        }
        foreach ($items as $name => &$item) {
            if ($removeEmptyGroup && ($item['url'] == '#') && !$item['items']) {
                unset($items[$name]);
            } else {
                $this->exportAttribute($item['url'], 'route');
                $this->exportAttribute($item['label'], 'trans');
                $this->export($item['items'], $removeEmptyGroup, $sortBy, $sortAsc);
            }
        }
        if ($sortBy) {
            uasort($items, function ($item1, $item2) use ($sortBy, $sortAsc) {
                $attr1 = isset($item1[$sortBy]) ? $item1[$sortBy] : 0;
                $attr2 = isset($item2[$sortBy]) ? $item2[$sortBy] : 0;
                $less = $sortAsc ? -1 : 1;
                if ($attr1 == $attr2) {
                    return 0;
                } else {
                    return $attr1 < $attr2 ? $less : -$less;
                }
            });
        }
    }

    /**
     * @param string $attr
     * @param string $fn
     */
    protected function exportAttribute(&$attr, $fn)
    {
        $len = strlen($fn) + 1;
        if (substr($attr, 0, $len) === "{$fn}:") {
            list($value, $data) = explode('|', substr($attr, $len) . '|');
            $params = [];
            if ($data) {
                foreach (explode(',', $data) as $item) {
                    list($k, $v) = explode(':', $item . ':');
                    $params[$k] = $v;
                }
            }
            $attr = $fn($value, $params);
        }
    }
}
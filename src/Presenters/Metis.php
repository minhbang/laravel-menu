<?php
namespace Minhbang\Menu\Presenters;

use Minhbang\Menu\Contracts\Presenter;
use Request;

/**
 * Class Metis
 *
 * @package Minhbang\Menu\Presenters
 */
class Metis extends Base implements Presenter
{
    /**
     * Render menu item
     *
     * @param array $item
     * @param int $level
     * @param string $sub
     * @param null|string $prefix
     *
     * @return string
     */
    protected function item($item, $level, $sub = null, $prefix = null)
    {
        if (isset($item['visible']) && !$item['visible']) {
            return '';
        }
        $item = $item + ['url' => '#', 'class' => '', 'icon' => false, 'badge' => false];
        if ($item['url'] !== '#') {
            $item['url'] = url(mb_str_prefix($prefix, $item['url']));
        }
        $item['active'] = isset($item['active']) ? $item['active'] : $item['url'];

        $icon = mb_icon_html($item['icon'], '', 'i');
        $icon = $icon ? "$icon " : '';

        $class = $item['class'] . ' ' . mb_menu_active(
                $item['active'],
                $prefix,
                'active',
                $item['url'] == Request::url()
            );

        if ($item['badge']) {
            if (is_string($item['badge'])) {
                $item['badge'] = ['label' => $item['badge']];
            }
            $item['badge'] = $item['badge'] + ['type' => '', 'class' => 'label'];
            $badge_class = ($item['badge']['type'] ? " {$item['badge']['class']}-{$item['badge']['type']}" : '');
            $badge_class = "{$item['badge']['class']}{$badge_class} pull-right";
            $arrow = "<span class=\"{$badge_class}\">{$item['badge']['label']}</span>";
        } else {
            $arrow = $sub ? ' <span class="fa arrow"></span>' : '';
        }
        $label = $level == 1 ? "<span class=\"nav-label\">{$item['label']}</span>" : $item['label'];
        $attributes = isset($item['attributes']) ? $item['attributes'] : [];

        return "<li class=\"{$class}\"><a href=\"{$item['url']}\" {$this->attributes($attributes)}>{$icon}{$label}{$arrow}</a>{$sub}</li>";
    }

    /**
     * Render menu theo định dạng của metisMenu
     * $items = [
     *     [
     *         'url' => string,
     *         'icon' => string,
     *         'label' => string,
     *         'prefix' => string|null
     *         'active' => null|string|array,
     *         'visible' => bool,
     *         'class' => string|null
     *         'badge' => string | ['label' => mixed, 'type' => '', 'class' => 'label'],
     *         'items' => array tương tự,
     *     ],
     *     ...
     * ]
     *
     * @param array $items
     * @param null|string $header
     * @param null|string $id
     * @param null|string $prefix
     * @param int $level
     *
     * @return null|string
     */
    public function items($items, $header = null, $id = null, $prefix = '', $level = 1)
    {
        if ($level > 3) {
            return null;
        }
        $header = $header ? "<li class=\"nav-header\">{$header}</li>" : '';
        $classes = [1 => 'nav', 2 => 'nav nav-second-level', 3 => 'nav nav-third-level'];
        $lis = '';
        foreach ($items as $item) {
            $item = $item + ['prefix' => ''];
            if (empty($item['items'])) {
                $sub = '';
            } else {
                $sub_prefix = trim("{$prefix}/{$item['prefix']}", '/');
                $sub = $this->items($item['items'], null, null, $sub_prefix, $level + 1);
            }
            if (isset($item['active'])) {
                if (!is_array($item['active'])) {
                    $item['active'] = [$item['active']];
                }
                $active_prefix = mb_array_extract('prefix', $item['active'], $item['prefix']);
                $item['active'] = mb_str_prefix($active_prefix, $item['active'], ['/', '*' => '']);
            }
            $lis .= $this->item($item, $level, $sub, $prefix);
        }
        $id = $id ? " id=\"$id\"" : '';

        return "<ul class=\"{$classes[$level]}\" {$id}>{$header}{$lis}</ul>";
    }

    /**
     * @param \Minhbang\Menu\Roots\UneditableRoot $root
     * @param array $options
     *
     * @return string
     */
    public function html($root, $options = [])
    {
        return $this->items($root->items(), array_get($options, 'header'), $root->settings('options.attributes.id'));
    }
}
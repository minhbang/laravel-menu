<?php
namespace Minhbang\Menu\Presenters;

use Minhbang\Menu\Contracts\Presenter;
use Html;

/**
 * Class Main
 *
 * @package Minhbang\Menu\Presenters
 */
class Main extends Base implements Presenter
{
    /**
     * Render menu dạng dropdown đa cấp
     *
     * @param \Minhbang\Menu\Manager $manager
     *
     * @return string
     */
    public function html($manager)
    {
        if ($items = $manager->level1_items()) {
            $menu = $manager->root();
            $max_depth = $menu->getOption('max_depth', config('menu.default_max_depth'));
            $tag = $menu->getOption('tag', 'ul');

            $item_tag = $menu->getOption('item_tag', 'li');
            $item_attributes = $menu->getOption('item_attributes', []);

            $html = '';
            foreach ($items as $item) {
                $html .= $this->htmlItem($item, $max_depth, $tag, $item_tag, $item_attributes);
            }

            $attributes = Html::attributes($menu->getOption('attributes', []));
            $html = "<{$tag}{$attributes}>$html</{$tag}>";

            return $html;
        } else {
            return '';
        }
    }

    /**
     * @param \Minhbang\Menu\Item $item
     * @param integer $max_depth
     * @param string $tag
     * @param string $item_tag
     * @param array $item_attributes
     * @param integer $depth
     *
     * @return string
     */
    protected function htmlItem($item, $max_depth, $tag, $item_tag, $item_attributes, $depth = 1)
    {
        if ($item->isLeaf() || $depth == $max_depth) {
            $attributes = mb_array_merge($item_attributes, $item->getOption('attributes', []));
            if (app('menu')->isActive($item->url)) {
                $this->addClass($attributes, 'active');
            }
            $attributes = Html::attributes($attributes);
            return "<{$item_tag}{$attributes}><a href=\"{$item->url}\">{$item->label}</a></{$item_tag}>";
        } else {
            $dropdown = 'dropdown' . ($depth > 1 ? '-submenu' : '');
            $html = "<{$item_tag} class=\"{$dropdown}\"><a href=\"#\" class=\"dropdown-toggle\" data-toggle=\"dropdown\" data-hover=\"dropdown\" data-delay=\"10\" title=\"{$item->label}\">
                {$item->label}</a>";
            $html .= "<{$tag} class=\"dropdown-menu\" role=\"menu\">";
            foreach ($item->children as $child) {
                $html .= $this->htmlItem($child, $max_depth, $tag, $item_tag, $item_attributes, $depth + 1);
            }
            $html .= "</{$tag}></{$item_tag}>";
            return $html;
        }
    }
}
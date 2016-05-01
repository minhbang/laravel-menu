<?php
namespace Minhbang\Menu\Presenters;

use Minhbang\Menu\Contracts\Presenter;
use Html;
use MenuManager;

/**
 * Class List1
 *
 * @package Minhbang\Menu\Presenters
 */
class List1 extends Base implements Presenter
{
    /**
     * Render menu dạng list 1 cấp
     *
     * @param \Minhbang\Menu\Roots\EditableRoot $root
     * @param array $options
     *
     * @return string
     */
    public function html($root, $options = [])
    {
        $menu = $root->node();
        if ($items = $menu->getImmediateDescendants()) {
            $item_tag = $menu->getOption('item_tag');
            $item_attributes = $menu->getOption('item_attributes', []);
            $html = '';
            foreach ($items as $item) {
                $attributes = mb_array_merge($item_attributes, $item->getOption('attributes', []));
                if (MenuManager::isActive($item->url)) {
                    $this->addClass($attributes, 'active');
                }
                $attributes = Html::attributes($attributes);
                if (empty($item_tag)) {
                    $html .= "<a href=\"{$item->url}\" {$attributes}>{$item->label}</a>";
                } else {
                    $html .= "<{$item_tag}{$attributes}><a href=\"{$item->url}\">{$item->label}</a></{$item_tag}>";
                }
            }

            $tag = $menu->getOption('tag');
            if (!empty($tag)) {
                $attributes = Html::attributes($menu->getOption('attributes', []));
                $html = "<{$tag}{$attributes}>$html</{$tag}>";
            }

            return $html;
        } else {
            return '';
        }
    }
}
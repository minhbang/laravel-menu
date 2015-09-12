<?php
namespace Minhbang\LaravelMenu\Presenters;

use Html;

class List2LevelPresenter extends Presenter
{
    /**
     * Render menu dạng list 2 cấp (ex: dạng footer menu)
     *
     * @param \Minhbang\LaravelMenu\MenuItem $menu root node
     * @return string|null html menu
     */
    public function html($menu)
    {
        $items = $menu->getImmediateDescendants(); // cấp 1
        if (empty($items)) {
            return '';
        } else {
            $item_tag = $menu->getOption('item_tag');
            $item_attributes = $menu->getOption('item_attributes', []);

            $html = '';
            foreach ($items as $item) {
                if (!$item->isLeaf()) {
                    $is_active_item = false;
                    $sub_items = $item->getImmediateDescendants(); // cấp 2
                    $item_html = "<h5>{$item->label}</h5><ul>";
                    foreach ($sub_items as $sub_item) {
                        if (app('menu')->isActive($sub_item->url)) {
                            $active = ' class="active"';
                            $is_active_item = true;
                        } else {
                            $active = '';
                        }
                        $item_html .= "<li{$active}><a href=\"{$sub_item->url}\">{$sub_item->label}</a></li>";
                    }
                    $item_html .= "</ul>";

                    if (empty($item_tag)) {
                        if ($is_active_item) {
                            $item_html = str_replace(
                                ['<h5>', '<ul>'],
                                ['<h5 class="active">', '<ul class="active">'],
                                $item_html
                            );

                        }
                    } else {
                        $attributes = $item_attributes;
                        if ($is_active_item) {
                            $this->addClass($attributes, 'active');
                        }
                        $attributes = Html::attributes($attributes);
                        $item_html = "<{$item_tag}{$attributes}>{$item_html}</{$item_tag}>";
                    }

                    $html .= $item_html;
                }
            }

            $tag = $menu->getOption('tag');
            if (!empty($tag)) {
                $attributes = Html::attributes($menu->getOption('attributes', []));
                $html = "<{$tag}{$attributes}>$html</{$tag}>";
            }

            return $html;
        }
    }
}
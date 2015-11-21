<?php
namespace Minhbang\Menu\Presenters;

use Html;

class List2LevelPresenter extends Presenter
{
    /**
     * Render menu dạng list 2 cấp (ex: dạng footer menu)
     *
     * @param \Minhbang\Menu\Item $menu root node
     *
     * @return string|null html menu
     */
    public function html($menu)
    {
        /** @var \Illuminate\Database\Eloquent\Collection|\Minhbang\Menu\Item[] $items */
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
                    /** @var \Illuminate\Database\Eloquent\Collection|\Minhbang\Menu\Item[] $sub_items */
                    $sub_items = $item->getImmediateDescendants(); // cấp 2
                    $item_html = "<h5>{$item->label}</h5><ul>";
                    foreach ($sub_items as $sub_item) {
                        $attributes = Html::attributes($sub_item->getOption('attributes', []));
                        if (app('menu')->isActive($sub_item->url)) {
                            $this->addClass($attributes, 'active');
                            $is_active_item = true;
                        }
                        $attributes = Html::attributes($attributes);
                        $item_html .= "<li{$attributes}><a href=\"{$sub_item->url}\">{$sub_item->label}</a></li>";
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
                        $attributes = mb_array_merge($item_attributes, $item->getOption('attributes', []));
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
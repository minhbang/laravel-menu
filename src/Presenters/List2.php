<?php
namespace Minhbang\Menu\Presenters;

use Minhbang\Menu\Contracts\Presenter;
use Html;

/**
 * Class List2
 *
 * @package Minhbang\Menu\Presenters
 */
class List2 extends Base implements Presenter
{
    /**
     * Render menu dạng list 2 cấp (ex: dạng footer menu)
     *
     * @param \Minhbang\Menu\Manager $manager
     *
     * @return string
     */
    public function html($manager)
    {
        if ($items = $manager->level1_items()) {
            $menu = $manager->root();
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
        } else {
            return '';
        }
    }
}
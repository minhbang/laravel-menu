<?php
namespace Minhbang\Menu\Presenters;

use Minhbang\Menu\Contracts\Presenter;
use Html;
use MenuManager;

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
     * @param \Minhbang\Menu\Roots\EditableRoot $root
     * @param array $options
     *
     * @return string
     */
    public function html($root, $options = [])
    {
        $menu = $root->node();
        /** @var \Minhbang\Menu\Menu[]|\Illuminate\Support\Collection $items */
        $items = $menu->descendants()->where('depth', '<=', 2)->get();
        if ($items->count()) {
            $item_tag = $menu->getOption('item_tag', 'li');
            $item_attributes = $menu->getOption('item_attributes', []);
            $html = '';
            $current_item_html = '';
            $current_item_attributes = [];
            $is_active_item = false;
            foreach ($items as $item) {
                if ($item->depth == 1) {
                    // Kết thức 'group' trước
                    $html .= $this->endGroupItem($item_tag, $current_item_html, $current_item_attributes, $is_active_item);
                    if ($item->rgt - $item->lft > 1) {
                        // Nếu node level 1 có các mục menu con, start 'group' mới
                        $current_item_html = "<h5>{$item->label}</h5><ul>";
                        $current_item_attributes = mb_array_merge($item_attributes, $item->getOption('attributes', []));
                    } else {
                        // bỏ qua node này, reset
                        $current_item_html = '';
                        $current_item_attributes = [];
                    }
                    $is_active_item = false;
                } else {
                    $attributes = Html::attributes($item->getOption('attributes', []));
                    if (MenuManager::isActive($item->url)) {
                        $this->addClass($attributes, 'active');
                        $is_active_item = true;
                    }
                    $attributes = Html::attributes($attributes);

                    $current_item_html .= "<li{$attributes}><a href=\"{$item->url}\">{$item->label}</a></li>";
                }
            }
            $html .= $this->endGroupItem($item_tag, $current_item_html, $current_item_attributes, $is_active_item);

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

    /**
     * @param string $tag
     * @param string $html
     * @param array $attributes
     * @param bool $is_active
     *
     * @return string
     */
    protected function endGroupItem($tag, $html, $attributes, $is_active)
    {
        if (empty($html)) {
            return '';
        }
        $html .= "</ul>";
        if (empty($tag)) {
            if ($is_active) {
                $html = str_replace(['<h5>', '<ul>'], ['<h5 class="active">', '<ul class="active">'], $html);
            }
        } else {
            if ($is_active) {
                $this->addClass($attributes, 'active');
            }
            $attributes = Html::attributes($attributes);
            $html = "<{$tag}{$attributes}>{$html}</{$tag}>";
        }

        return $html;
    }
}
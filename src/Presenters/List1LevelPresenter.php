<?php
namespace Minhbang\LaravelMenu\Presenters;

use Html;

class List1LevelPresenter extends Presenter
{
    /**
     * Render menu dạng list 1 cấp
     *
     * @param \Minhbang\LaravelMenu\MenuItem $menu root node
     *
     * @return string|null html menu
     */
    public function html($menu)
    {
        /** @var \Illuminate\Database\Eloquent\Collection|\Minhbang\LaravelMenu\MenuItem[] $items */
        $items = $menu->getImmediateDescendants();
        if (empty($items)) {
            return '';
        } else {
            $item_tag = $menu->getOption('item_tag');
            $item_attributes = $menu->getOption('item_attributes', []);

            $html = '';
            foreach ($items as $item) {
                $attributes = mb_array_merge($item_attributes, $item->getOption('attributes', []));
                if (app('menu')->isActive($item->url)) {
                    $this->addClass($attributes, 'active');
                }
                $attributes = Html::attributes($attributes);
                if (empty($item_tag)) {
                    $html .= "<a href=\"{$item->url}\"{$attributes}>{$item->label}</a>";
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
        }
    }
}
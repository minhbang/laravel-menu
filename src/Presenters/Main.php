<?php
namespace Minhbang\Menu\Presenters;

use Minhbang\Menu\Contracts\Presenter;
use Html;
use MenuManager;

/**
 * Class Main
 *
 * @package Minhbang\Menu\Presenters
 */
class Main extends Base implements Presenter
{
    /**
     * @var string
     */
    protected $tag;
    /**
     * @var string
     */
    protected $item_tag;
    /**
     * @var array
     */
    protected $item_attributes;

    /**
     * Render menu dạng dropdown đa cấp
     *
     * @param \Minhbang\Menu\Roots\EditableRoot $root
     * @param array $options
     *
     * @return string
     */
    public function html($root, $options = [])
    {
        $menu = $root->node();
        $max_depth = $menu->getOption('max_depth', config('menu.default_max_depth'));
        /** @var \Minhbang\Menu\Menu[]|\Illuminate\Support\Collection $items */
        $items = $menu->descendants()->where('depth', '<=', $max_depth)->get();
        if ($items->count()) {
            $this->tag = $menu->getOption('tag', 'ul');
            $this->item_tag = $menu->getOption('item_tag', 'li');
            $this->item_attributes = $menu->getOption('item_attributes', []);
            $html = '';
            $depth = 0;
            foreach ($items as $item) {
                if ($item->depth < $depth) {
                    $html .= str_repeat($this->endCurrentItem(), $depth - $item->depth);
                }
                $html .= $this->startNewItem($item);
                $depth = $item->depth;
            }
            $attributes = Html::attributes($menu->getOption('attributes', []));

            return "<{$this->tag}{$attributes}>$html</{$this->tag}>";
        } else {
            return '';
        }
    }

    /**
     * @param \Minhbang\Menu\Menu $item
     *
     * @return string
     */
    protected function startNewItem($item)
    {
        if ($item->rgt - $item->lft == 1) {
            // is leaf
            $attributes = mb_array_merge($this->item_attributes, $item->getOption('attributes', []));
            if (MenuManager::isActive($item->url)) {
                $this->addClass($attributes, 'active');
            }
            $attributes = Html::attributes($attributes);

            return "<{$this->item_tag}{$attributes}><a href=\"{$item->url}\">{$item->label}</a></{$this->item_tag}>";
        } else {
            $dropdown = 'dropdown' . ($item->depth > 1 ? '-submenu' : '');

            return <<<"ITEM"
<{$this->item_tag} class="{$dropdown}">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-delay="10">{$item->label}</a>
    <{$this->tag} class="dropdown-menu" role="menu">
ITEM;
        }
    }

    /**
     * @return string
     */
    protected function endCurrentItem()
    {
        return "</{$this->tag}></{$this->item_tag}>";
    }
}
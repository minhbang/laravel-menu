<?php
namespace Minhbang\LaravelMenu\Presenters;

class DefaultPresenter
{
    /**
     * @param \Minhbang\LaravelMenu\MenuItem $menu root node
     * @return string|null
     */
    public function html($menu)
    {
        $items = $menu->getImmediateDescendants();
        if (empty($items)) {
            return '';
        } else {
            $max_depth = $menu->getOption('max_depth', config('menu.default_max_depth'));
            $html = "<ul class=\"nav navbar-nav\">";
            foreach ($items as $item) {
                $html .= $this->htmlItem($item, $max_depth);
            }
            $html .= '</ul>';
            return $html;
        }
    }

    /**
     * @param \Minhbang\LaravelMenu\MenuItem $item
     * @param integer $max_depth
     * @param integer $depth
     * @return string
     */
    protected function htmlItem($item, $max_depth, $depth = 1)
    {
        if ($item->isLeaf() || $depth == $max_depth) {
            $active = app('menu')->getActive($item->url);
            return "<li{$active}><a href=\"{$item->url}\">{$item->label}</a></li>";
        } else {
            $dropdown = 'dropdown' . ($depth > 1 ? '-submenu' : '');
            $html = "<li class=\"{$dropdown}\"><a href=\"#\" class=\"dropdown-toggle\" data-toggle=\"dropdown\" data-hover=\"dropdown\" data-delay=\"10\" title=\"{$item->label}\">
                {$item->label}</a>";
            $html .= "<ul class=\"dropdown-menu\" role=\"menu\">";
            foreach ($item->children as $child) {
                $html .= $this->htmlItem($child, $max_depth, $depth + 1);
            }
            $html .= '</ul></li>';
            return $html;
        }
    }
}
<?php
namespace Minhbang\Menu\Presenters;

class Presenter
{
    /**
     * @param array $attributes
     * @param string $new_class
     */
    protected function addClass(&$attributes, $new_class)
    {
        if (empty($attributes['class'])) {
            $attributes['class'] = $new_class;
        } else {
            if (!in_array($new_class, explode(' ', $attributes['class']))) {
                $attributes['class'] .= " $new_class";
            }
        }
    }
}
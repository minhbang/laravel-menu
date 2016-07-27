<?php
namespace Minhbang\Menu\Presenters;

/**
 * Class Base
 *
 * @package Minhbang\Menu\Presenters
 */
abstract class Base
{
    /**
     * @param array $attributes
     * @param string $classes
     */
    protected function addClass(&$attributes, $classes)
    {
        if (empty($attributes['class'])) {
            $attributes['class'] = $classes;
        } else {
            if (!in_array($classes, explode(' ', $attributes['class']))) {
                $attributes['class'] .= " $classes";
            }
        }
    }
}
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

    /**
     * Build an HTML attribute string from an array.
     *
     * @param  array $attributes
     *
     * @return string
     */
    protected function attributes($attributes)
    {
        $html = [];
        // For numeric keys we will assume that the key and the value are the same
        // as this will convert HTML attributes such as "required" to a correct
        // form like required="required" instead of using incorrect numerics.
        foreach ((array)$attributes as $key => $value) {
            if (!is_null($value)) {
                $html[] = (is_numeric($key) ? $value : $key) . '="' . e($value) . '"';
            }
        }

        return count($html) ? ' ' . implode(' ', $html) : '';
    }
}
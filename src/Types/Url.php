<?php
namespace Minhbang\Menu\Types;

use Minhbang\Menu\Contracts\Type;

/**
 * Class Url
 *
 * @package Minhbang\Menu\Types
 */
class Url implements Type
{
    /**
     * @param string $params
     *
     * @return string
     */
    public function url($params)
    {
        return $params;
    }

    /**
     * @return string
     */
    public function title()
    {
        return trans('menu::type.url');
    }

    /**
     * @return string
     */
    public function titleParams()
    {
        return trans('menu::type.url_params');
    }
}
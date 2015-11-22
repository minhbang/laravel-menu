<?php
namespace Minhbang\Menu\Contracts;
/**
 * Interface Type
 *
 * @package Minhbang\Menu\Contracts
 */
interface Type
{
    /**
     * Lấy URL của menu theo $params
     *
     * @param string $params
     *
     * @return string
     */
    public function url($params);

    /**
     * Tên menu type
     *
     * @return string
     */
    public function title();

    /**
     * @return string
     */
    public function titleParams();
}
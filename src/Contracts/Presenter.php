<?php
namespace Minhbang\Menu\Contracts;
/**
 * Interface Presenter
 *
 * @package Minhbang\Menu\Contracts
 */
interface Presenter
{
    /**
     * Render menu HTML theo menu $options
     *
     * @param \Minhbang\Menu\Root $root
     *
     * @return string
     */
    public function html($root);
}
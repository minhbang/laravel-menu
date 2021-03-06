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
     * @param \Minhbang\Menu\Contracts\Root $root
     * @param array $options
     *
     * @return string
     */
    public function html($root, $options = []);
}
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
     * @param \Minhbang\Menu\Manager $manager
     *
     * @return string
     */
    public function html($manager);
}
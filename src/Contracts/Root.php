<?php
namespace Minhbang\Menu\Contracts;
/**
 * Interface Root
 *
 * @package Minhbang\Menu\Contracts
 */
interface Root
{
    /**
     * Render menu HTML theo menu $options
     *
     * @param array $options
     *
     * @return string
     */
    public function html($options = []);

    /**
     * @return bool
     */
    public function isEditable();
}
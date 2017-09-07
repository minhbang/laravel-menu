<?php namespace Minhbang\Menu\Types;

abstract class NoParamsMenuType extends MenuType
{
    /**
     * Không tham số
     *
     * @return bool
     */
    protected function formView()
    {
        return false;
    }

    /**
     * Không tham số
     *
     * @return array
     */
    protected function paramsAttributes()
    {
        return [];
    }
}
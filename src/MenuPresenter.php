<?php
namespace Minhbang\Menu;

use Laracasts\Presenter\Presenter;

/**
 * Class MenuPresenter
 *
 * @package Minhbang\Menu
 */
class MenuPresenter extends Presenter
{
    /**
     * @param string $locale
     *
     * @return string
     */
    public function label($locale = null)
    {
        return $locale ? $this->entity->{"label:$locale| "} : $this->entity->label;
    }

    /**
     * @return string
     */
    public function type()
    {
        $type = \MenuManager::types($this->entity->type);

        return "<span class=\"label label-info text-uppercase\">$type</label>";
    }

    /**
     * @return string
     */
    public function params()
    {
        return "<code>{$this->entity->params}</code>";
    }

    /**
     * @return string
     */
    public function options()
    {
        return "<code>{$this->entity->options}</code>";
    }

    /**
     * @param int $max_depth
     *
     * @return string
     */
    public function actions($max_depth)
    {
        if ($this->entity->depth < $max_depth) {
            $child = '<a href="' . url("backend/menu/{$this->entity->id}/create") . '"
               class="modal-link btn btn-primary btn-xs"
               data-toggle="tooltip"
               data-title="' . trans('common.create_child_object', ['name' => trans('menu::common.item')]) . '"
               data-label="' . trans('common.save') . '"
               data-icon="align-justify"><span class="glyphicon glyphicon-plus"></span>
            </a>';
        } else {
            $child = '<a href="#"
               class="btn btn-primary btn-xs disabled"
               data-toggle="tooltip"
               data-title="' . trans('common.create_child_object', ['name' => trans('menu::common.item')]) . '">
                <span class="glyphicon glyphicon-plus"></span>
            </a>';
        }

        $show = '<a href="' . url("backend/menu/{$this->entity->id}") . '"
           data-toggle="tooltip"
           class="modal-link btn btn-success btn-xs"
           data-title="' . trans('common.object_details_view', ['name' => trans('menu::common.item')]) . '"
           data-icon="align-justify"><span class="glyphicon glyphicon-list"></span>
        </a>';
        $edit = '<a href="' . url("backend/menu/{$this->entity->id}/edit") . '"
           data-toggle="tooltip"
           class="modal-link btn btn-info btn-xs"
           data-title="' . trans('common.update_object', ['name' => trans('menu::common.item')]) . '"
           data-label="' . trans('common.save_changes') . '"
           data-icon="align-justify"><span class="glyphicon glyphicon-edit"></span>
        </a>';
        $delete = '<a href="#"
            data-toggle="tooltip"
            data-title="' . trans('common.delete_object', ['name' => trans('menu::common.item')]) . '"
            data-item_id="' . $this->entity->id . '"
            data-item_title="' . $this->entity->label . '"
            class="delete_item btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span>
        </a>';

        return $child . $show . $edit . $delete;
    }
}
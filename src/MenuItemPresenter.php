<?php
namespace Minhbang\LaravelMenu;

use Laracasts\Presenter\Presenter;

/**
 * Class MenuPresenter
 *
 * @package Minhbang\LaravelMenu
 */
class MenuItemPresenter extends Presenter
{
    /**
     * @return string
     */
    public function label()
    {
        return $this->entity->label;
    }

    /**
     * @return string
     */
    public function type()
    {
        $type = app('menu')->getTypeName($this->entity->type);
        return "<span class=\"label label-info text-uppercase\">$type</label>";
    }

    /**
     * @return string
     */
    public function params()
    {
        return '<span class="label label-default">' . $this->entity->params . '</label>';
    }

    /**
     * @param int $max_depth
     * @return string
     */
    public function actions($max_depth)
    {
        if($this->entity->depth < $max_depth) {
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
            data-item_title="' . $this->entity->title . '"
            class="delete_item btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span>
        </a>';
        return $child . $show . $edit . $delete;
    }
}
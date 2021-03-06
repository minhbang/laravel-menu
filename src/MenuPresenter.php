<?php

namespace Minhbang\Menu;

use Laracasts\Presenter\Presenter;

/**
 * Class MenuPresenter
 *
 * @property-read \Minhbang\Menu\Menu $entity
 * @package Minhbang\Menu
 */
class MenuPresenter extends Presenter
{
    /**
     * @return string
     */
    public function label()
    {
        return $this->entity->label.(! $this->entity->configured ?
                '<code> — '.__('Not configured').'</code>' : '');
    }

    /**
     * @return string
     */
    public function type()
    {
        $type = app('menu-manager')->menuType($this->entity->type, 'title');

        return "<span class=\"label label-info text-uppercase\">$type</label>";
    }

    /**
     * @return string
     */
    public function params()
    {
        return "<code>".var_export($this->entity->params, true)."</code>";
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
        if($menuType = $this->entity->typeInstance()) {
            $paramsForm = $menuType->formOptions() + ['height' => null];
            $paramsFormHeight = $paramsForm['height'] ? "data-height=\"{$paramsForm['height']}\"" : '';
            $params = $menuType->hasParams ? '<a href="'.url("backend/menu/{$this->entity->id}/params").'"
               class="modal-link btn btn-warning btn-xs"
               data-toggle="tooltip"
               data-title="'.__('Menu parameters').'"
               data-label="'.__('Save').'"
               '.$paramsFormHeight.'
               data-icon="cogs"><i class="fa fa-cogs"></i>
            </a>' : '<a href="#" class="btn btn-warning btn-xs disabled"><i class="fa fa-cogs"></i></a>';

            if ($this->entity->depth < $max_depth) {
                $child = '<a href="'.url("backend/menu/{$this->entity->id}/create").'"
               class="modal-link btn btn-primary btn-xs"
               data-toggle="tooltip"
               data-title="'.__('Create child :name', ['name' => __('Menu item')]).'"
               data-label="'.__('Save').'"
               data-icon="align-justify"><span class="glyphicon glyphicon-plus"></span>
            </a>';
            } else {
                $child = '<a href="#"
               class="btn btn-primary btn-xs disabled"
               data-toggle="tooltip"
               data-title="'.__('Create child :name', ['name' => __('Menu item')]).'">
                <span class="glyphicon glyphicon-plus"></span>
            </a>';
            }

            $show = '<a href="'.url("backend/menu/{$this->entity->id}").'"
           data-toggle="tooltip"
           class="modal-link btn btn-success btn-xs"
           data-title="'.__('Details of :name', ['name' => __('Menu item')]).'"
           data-icon="align-justify"><span class="glyphicon glyphicon-list"></span>
        </a>';
            $edit = '<a href="'.url("backend/menu/{$this->entity->id}/edit").'"
           data-toggle="tooltip"
           class="modal-link btn btn-info btn-xs"
           data-title="'.__('Update :name', ['name' => __('Menu item')]).'"
           data-label="'.__('Save Shanges').'"
           data-icon="align-justify"><span class="glyphicon glyphicon-edit"></span>
        </a>';
            $delete = '<a href="#"
            data-toggle="tooltip"
            data-title="'.__('Delete :name', ['name' => __('Menu item')]).'"
            data-item_id="'.$this->entity->id.'"
            data-item_title="'.$this->entity->label.'"
            class="delete_item btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span>
        </a>';

            return $params.$child.$show.$edit.$delete;
        } else{
            return '';
        }
    }
}

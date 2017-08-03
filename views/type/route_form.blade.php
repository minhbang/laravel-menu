<?php
/**
 * @var \Minhbang\Menu\Menu $menu
 * @var array $params
 */
?>
@extends('kit::backend.layouts.modal')
@section('content')
    {!! Form::model($params,['class' => 'form-horizontal form-modal','url' => $url, 'method' => 'put']) !!}
    <div class="form-group">
        <label class="col-xs-3 control-label">{{ trans('menu::common.menu') }}</label>
        <div class="col-xs-9">
            <p class="form-control-static text-primary">{{ $menu->label }}</p>
        </div>
    </div>
    <div class="form-group {{ $errors->has("name") ? ' has-error':'' }}">
        {!! Form::label("name", $labels['name'], ['class' => "col-xs-3 control-label"]) !!}
        <div class="col-xs-9">
            {!! Form::select(
                "name", $menu->typeInstance()->getRoutes(), null, ['prompt' => trans('menu::type.route.name_hint'), 'class' => 'form-control selectize'])
            !!}
            @if($errors->has('name'))
                <p class="help-block">{{ $errors->first('name') }}</p>
            @endif
        </div>
    </div>
    {!! Form::close() !!}
@stop
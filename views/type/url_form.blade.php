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
        <label class="col-xs-3 control-label">{{ __('Menu') }}</label>
        <div class="col-xs-9">
            <p class="form-control-static text-primary">{{ $menu->label }}</p>
        </div>
    </div>
    <div class="form-group {{ $errors->has("url") ? ' has-error':'' }}">
        {!! Form::label("url", $labels['url'], ['class' => "col-xs-3 control-label"]) !!}
        <div class="col-xs-9">
            {!! Form::text("url", null, ['class' => 'form-control']) !!}
            @if($errors->has('url'))
                <p class="help-block">{{ $errors->first('url') }}</p>
            @endif
        </div>
    </div>
    {!! Form::close() !!}
@stop
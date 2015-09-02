@extends('backend.layouts.modal')
@section('content')
    {!! Form::model($menu,['class' => 'form-horizontal','url' => $url, 'method' => $method]) !!}
    <div class="form-group">
        <label class="col-xs-3 control-label">{{ trans('menu.parent') }}</label>
        <div class="col-xs-9">
            <p class="form-control-static text-primary">{{ $parent_label }}</p>
        </div>
    </div>
    <div class="form-group{{ $errors->has('label') ? ' has-error':'' }}">
        {!! Form::label('label', trans('menu::common.label'), ['class' => 'col-xs-3 control-label']) !!}
        <div class="col-xs-9">
            {!! Form::text('label', null, ['class' => 'form-control']) !!}
            @if($errors->has('label'))
                <p class="help-block">{{ $errors->first('label') }}</p>
            @endif
        </div>
    </div>
    <div class="form-group{{ $errors->has('type') ? ' has-error':'' }}">
        {!! Form::type('type', trans('menu::common.type'), ['class' => 'col-xs-3 control-label']) !!}
        <div class="col-xs-9">
            {!! Form::select('type', $types, null, ['class' => 'form-control selectize']) !!}
            @if($errors->has('type'))
                <p class="help-block">{{ $errors->first('type') }}</p>
            @endif
        </div>
    </div>
    <div class="form-group{{ $errors->has('params') ? ' has-error':'' }}">
        {!! Form::params('params', trans('menu::common.params'), ['class' => 'col-xs-3 control-label']) !!}
        <div class="col-xs-9">
            {!! Form::text('params', null, ['class' => 'form-control']) !!}
            @if($errors->has('params'))
                <p class="help-block">{{ $errors->first('params') }}</p>
            @endif
        </div>
    </div>
    {!! Form::close() !!}
@stop
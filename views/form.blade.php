@extends('kit::backend.layouts.modal')
@section('content')
    {!! Form::model($menu,['class' => 'form-horizontal','url' => $url, 'method' => $method]) !!}
    <div class="form-group">
        <label class="col-xs-3 control-label">{{ __('Parent Menu') }}</label>
        <div class="col-xs-9">
            <p class="form-control-static text-primary">{{ $parent_label }}</p>
        </div>
    </div>
    <div class="form-group{{ $errors->has('type') ? ' has-error':'' }}">
        {!! Form::label('type', __('Menu type'), ['class' => 'col-xs-3 control-label']) !!}
        <div class="col-xs-9">
            {!! Form::select('type', $types, null, ['class' => 'form-control selectize']) !!}
            @if($errors->has('type'))
                <p class="help-block">{{ $errors->first('type') }}</p>
            @endif
        </div>
    </div>
    <div class="form-group{{ $errors->has('label') ? ' has-error':'' }}">
        {!! Form::label('label', __('Label'), ['class' => 'col-xs-3 control-label']) !!}
        <div class="col-xs-9">
            {!! Form::text('label', null, ['class' => 'has-slug form-control','data-slug_target' => "#name"]) !!}
            @if($errors->has('label'))
                <p class="help-block">{{ $errors->first('label') }}</p>
            @endif
        </div>
    </div>
    <div class="form-group{{ $errors->has('name') ? ' has-error':'' }}">
        {!! Form::label('name', __('Menu name'), ['class' => 'col-xs-3 control-label']) !!}
        <div class="col-xs-9">
            {!! Form::text('name', null, ['class' => 'form-control', 'id' => 'name']) !!}
            @if($errors->has('name'))
                <p class="help-block">{{ $errors->first('name') }}</p>
            @endif
        </div>
    </div>
    <div class="form-group{{ $errors->has('options') ? ' has-error':'' }}">
        {!! Form::label('options', __('Options'), ['class' => 'col-xs-3 control-label']) !!}
        <div class="col-xs-9">
            {!! Form::text('options', null, ['class' => 'form-control']) !!}
            @if($errors->has('options'))
                <p class="help-block">{{ $errors->first('options') }}</p>
            @endif
        </div>
    </div>
    {!! Form::close() !!}
@stop
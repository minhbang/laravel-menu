@extends('backend.layouts.modal')
@section('content')
    {!! Form::model($menu,['class' => 'form-horizontal','url' => $url, 'method' => $method]) !!}
    <div class="form-group">
        <label class="col-xs-3 control-label">{{ trans('menu::common.parent') }}</label>
        <div class="col-xs-9">
            <p class="form-control-static text-primary">{{ $parent_label }}</p>
        </div>
    </div>
    <ul class="nav nav-tabs m-b-md">
        @foreach($locales as $locale => $locale_title)
            <li role="presentation" class="{{$locale == $active_locale ? 'active': ''}}">
                <a href="#{{$locale}}-attributes" role="tab" data-toggle="tab">
                    <span class="text-{{LocaleManager::css($locale)}}">{{$locale_title}}</span>
                </a>
            </li>
        @endforeach
    </ul>
    <div class="tab-content">
        @foreach($locales as $locale => $locale_title)
            <div role="tabpanel" class="tab-pane{{$locale == $active_locale ? ' active': ''}}"
                 id="{{$locale}}-attributes">
                <div class="form-group{{ $errors->has('label') ? ' has-error':'' }}">
                    {!! Form::label('label', trans('menu::common.label'), ['class' => 'col-xs-3 control-label text-'. LocaleManager::css($locale)]) !!}
                    <div class="col-xs-9">
                        {!! Form::text("{$locale}[label]", $menu->{"label:$locale| "}, ['class' => 'form-control']) !!}
                        @if($errors->has('label'))
                            <p class="help-block">{{ $errors->first('label') }}</p>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <div class="form-group{{ $errors->has('name') ? ' has-error':'' }}">
        {!! Form::label('name', trans('menu::common.name'), ['class' => 'col-xs-3 control-label']) !!}
        <div class="col-xs-9">
            {!! Form::text('name', null, ['class' => 'form-control']) !!}
            @if($errors->has('name'))
                <p class="help-block">{{ $errors->first('name') }}</p>
            @endif
        </div>
    </div>
    <div class="form-group{{ $errors->has('type') ? ' has-error':'' }}">
        {!! Form::label('type', trans('menu::common.type'), ['class' => 'col-xs-3 control-label']) !!}
        <div class="col-xs-9">
            {!! Form::select('type', $types, null, ['class' => 'form-control selectize']) !!}
            @if($errors->has('type'))
                <p class="help-block">{{ $errors->first('type') }}</p>
            @endif
        </div>
    </div>
    <div class="form-group{{ $errors->has('params') ? ' has-error':'' }}">
        {!! Form::label('params', trans('menu::common.params'), ['class' => 'col-xs-3 control-label']) !!}
        <div class="col-xs-9">
            {!! Form::text('params', null, ['class' => 'form-control']) !!}
            @if($errors->has('params'))
                <p class="help-block">{{ $errors->first('params') }}</p>
            @endif
        </div>
    </div>
    <div class="form-group{{ $errors->has('options') ? ' has-error':'' }}">
        {!! Form::label('options', trans('menu::common.options'), ['class' => 'col-xs-3 control-label']) !!}
        <div class="col-xs-9">
            {!! Form::text('options', null, ['class' => 'form-control']) !!}
            @if($errors->has('options'))
                <p class="help-block">{{ $errors->first('options') }}</p>
            @endif
        </div>
    </div>
    {!! Form::close() !!}
@stop
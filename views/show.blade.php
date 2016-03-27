@extends('backend.layouts.modal')
@section('content')
    <ul class="nav nav-tabs nav-tabs-no-boder">
        @foreach($locales as $locale => $lang)
            <li role="presentation" class="{{$locale == $active_locale ? 'active': ''}}">
                <a href="#{{$locale}}-attributes" role="tab" data-toggle="tab">
                    <span class="text-{{LocaleManager::css($locale)}}">{{$lang}}</span>
                </a>
            </li>
        @endforeach
    </ul>
    <div class="tab-content">
        @foreach($locales as $locale => $lang)
            <div role="tabpanel" class="tab-pane{{$locale == $active_locale ? ' active': ''}}"
                 id="{{$locale}}-attributes">
                <table class="table table-hover table-striped table-bordered table-detail">
                    <tr>
                        <td>{{ trans('menu::common.label') }}</td>
                        <td><strong class="text-{{LocaleManager::css($locale)}}">
                                {!!$menu->present()->label($locale)!!}
                            </strong></td>
                    </tr>
                </table>
            </div>
        @endforeach
    </div>
    <table class="table table-hover table-striped table-bordered table-detail">
        <tr>
            <td>ID</td>
            <td><strong>{{ $menu->id}}</strong></td>
        </tr>
        <tr>
            <td>{{ trans('menu::common.name') }}</td>
            <td><strong>{{$menu->name}}</strong></td>
        </tr>
        <tr>
            <td>{{ trans('menu::common.type') }}</td>
            <td><strong>{!!$menu->present()->type!!}</strong></td>
        </tr>
        <tr>
            <td>{{ trans('menu::common.params') }}</td>
            <td><strong>{!!$menu->present()->params!!}</strong></td>
        </tr>
        <tr>
            <td>{{ trans('menu::common.options') }}</td>
            <td><strong>{!!$menu->present()->options!!}</strong></td>
        </tr>
        <tr>
            <td>{{ trans('menu::common.url') }}</td>
            <td class="text-danger"><strong>{{ $menu->url }}</strong></td>
        </tr>
    </table>
@stop
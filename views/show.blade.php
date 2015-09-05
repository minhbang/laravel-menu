@extends('backend.layouts.modal')
@section('content')
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
            <td>{{ trans('menu::common.label') }}</td>
            <td><strong>{!!$menu->present()->label!!}</strong></td>
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
            <td>{{ trans('menu::common.url') }}</td>
            <td class="text-danger"><strong>{{ $menu->url }}</strong></td>
        </tr>
    </table>
@stop
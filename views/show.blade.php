@extends('kit::backend.layouts.modal')
@section('content')
    <table class="table table-hover table-striped table-bordered table-detail">
        <tr>
            <td>ID</td>
            <td><strong>{{ $menu->id}}</strong></td>
        </tr>
        <tr>
            <td>{{ __('Menu name') }}</td>
            <td><strong>{{$menu->name}}</strong></td>
        </tr>
        <tr>
            <td>{{ __('Label') }}</td>
            <td><strong>{!!$menu->present()->label!!}</strong></td>
        </tr>
        <tr>
            <td>{{ __('Menu type') }}</td>
            <td><strong>{!!$menu->present()->type!!}</strong></td>
        </tr>
        <tr>
            <td>{{ __('Parameters') }}</td>
            <td><strong>{!!$menu->present()->params!!}</strong></td>
        </tr>
        <tr>
            <td>{{ __('Options') }}</td>
            <td><strong>{!!$menu->present()->options!!}</strong></td>
        </tr>
        <tr>
            <td>{{ __('Url') }}</td>
            <td class="text-danger"><strong>{{ $menu->url }}</strong></td>
        </tr>
    </table>
@stop
@extends('backend.layouts.master')
@section('content')
    <div class="panel panel-default panel-nestable panel-sidebar">
        <div class="panel-heading clearfix">
            <div class="loading hidden"></div>
            <a href="#" data-action="collapseAll" class="nestable_action btn btn-default btn-xs">
                <span class="glyphicon glyphicon-circle-arrow-up"></span>
            </a>
            <a href="#" data-action="expandAll" class="nestable_action btn btn-default btn-xs">
                <span class="glyphicon glyphicon-circle-arrow-down"></span>
            </a>
        </div>
        <div class="panel-body">
            <div class="row row-height">
                <div class="row-height-inside">
                    <div class="col-xs-9 col-height">
                        <div class="panel-body-content">
                            <div id="nestable-container" class="dd">{!! $nestable !!}</div>
                        </div>
                    </div>
                    <div class="col-xs-3 col-height panel-body-sidebar right">
                        <ul class="nav nav-tabs tabs-right">
                            @foreach($menus as $zone => $items)
                                @if($items)
                                    <li class="group-title">{{trans("menu::common.zones.{$zone}")}}</li>
                                    @foreach($items as $menu => $label)
                                        <li{!! $current =="{$zone}.{$menu}" ? ' class="active"':'' !!}>
                                            <a href="{{route('backend.menu.name', ['name' =>"{$zone}.{$menu}"])}}">{{$label}}</a>
                                        </li>
                                    @endforeach
                                @endif
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

        </div>
        <div class="panel-footer">
            <span class="glyphicon glyphicon-info-sign"></span> {{ trans('menu::common.order_hint')}}
        </div>
    </div>
@stop

@section('script')
    <script type="text/javascript">
        $(document).ready(function () {
            $('.panel-nestable').mbNestable({
                url: {
                    data: '{{route('backend.menu.data')}}',
                    move: '{{route('backend.menu.move')}}',
                    delete: '{{route('backend.menu.destroy', ['menu' => '__ID__'])}}'
                },
                max_depth:{{ $max_depth }},
                trans: {
                    name: '{{ trans('menu::common.item') }}'
                },
                csrf_token: '{{ csrf_token() }}'
            });
            $.fn.mbHelpers.reloadPage = function () {
                $('.panel-nestable').mbNestable('reload');
            }
        });
    </script>
@stop
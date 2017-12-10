<?php
    $tap = new RestRose\Pipe\Tap;

?>
@extends('../layout')

@section('content')
    @if(array_has($conf,'nav'))
        <ol class="breadcrumb">
            @foreach($conf['nav'] as $item)
                <li><a href="{{ $item['href'] }}">{{ $item['link'] }}</a></li>
            @endforeach
        </ol>
    @endif
@endsection

@section('container')
    <div class="row">
        @if(array_has($conf, 'records') && count($conf['records']) > 0)
        <table class="table table-striped">
            <caption><a href="/order"><i class="fa fa-cubes" aria-hidden="true"></i>&nbsp订货单</a> - {{ $conf['pn'] }}</caption>
            <thead>
                <tr>
                    <th>货号</th>
                    <th>名称</th>
                    <th>件数</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($conf['records'] as $record)
                <tr>
                    <td>{{ $record->goods_sn }}</td>
                    <td>{{ $record->fashion_name.'-'.$record->goods_name }}</td>
                    <td>{{ $record->send_num.'-'.$record->num }}</td>
                    <!-- exsits in offers -->
                    @if($record->send_num == $record->num)
                        <td id="info{{ $record->goods_id }}"><a href="javascript:inOffer({{ $record->goods_id.','.$record->num }})" class="btn btn-xs btn-success">已发货</a></td>
                    @else
                        @if($tap->isOrgMaster('shop'))
                           <td id="info{{ $record->goods_id }}"><a href="javascript:deliver({{ $record->goods_sn.','.$conf['pn'].','.$record->num }})" class="btn btn-xs btn-warning">发货</a></td>
                        @elseif($tap->inOrg('shop'))
                            <td><span class="label label-warning">待发货</span></td>

						@endif
                    @endif
                </tr>

                @endforeach
            </tbody>
        </table>


        @else
            <div class="col-md-8 col-md-offset-2">
                <div class="alert alert-warning">尚无记录</div>
            </div>
        @endif
    </div>

<!-- fix model -->
<div class="modal fade" id="deliver-model" tabindex="-1" role="dialog" aria-labelledby="deliver-model" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" 
                        aria-hidden="true">×
                </button>
                <h5 class="modal-title" id="modal-title">
                    发货: <span id="offers-title"></span>
                </h5>
            </div>
            <form method="POST" action="/offer/create" accept-charset="UTF-8" id="fix-form">

            <div class="modal-body" id="modal-body">
                <div class="alert alert-warning" id="offers-head">
                    <strong id="offers-num">num</strong><span id="offers-sn">空</span>
                    <input type="hidden" id="sns">
                    <input type="hidden" id="offer-goods-sn">
                    <input type="hidden" id="offer-num">
                    <input type="hidden" id="offer-pn" value="{{ $conf['pn'] }}">
                </div>
                    <table class="table">
                        <tbody id="offers-for-deliver">
                            <tr >
                                <td>产品1</td>
                                <td>23/11/2013</td>
                                <td><a class="btn btn-xs btn-success" href="javascript:choose()">发货</a></td>
                            </tr>
                        </tbody>
                </table>
                     
            </div>
            <div class="modal-footer" id="modal-footer">
                <a class="btn btn-default btn-sm" 
                        data-dismiss="modal">关闭
                </a>
                <span id="offers-sub"><a class="btn btn-success btn-sm" href="javascript:submitForm();">确定</a>    </span>
            </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

@endsection















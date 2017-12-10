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
<!-- seek -->
<div class="row">
    <div class="seek">
        <div class="input-group col-md-4 col-md-offset-4 col-xs-10 col-xs-offset-1">
          
            @if(array_has($conf,'key'))
                <input type="text" class="form-control" id="key" value="{{ $conf['key'] }}">
            @else
                <input type="text" class="form-control" id="key">
            @endif

            @if(array_has($conf,'shop'))
                <input type="hidden" name="shop" id="shop" value="{{ $conf['shop'] }}">
            @else
                <input type="hidden" name="shop" id="shop">
            @endif
            <a class="input-group-addon" href="javascript:seekKey()">查询</a>
        </div>
    </div>
</div>

<!-- shops -->
<div class="row">

        @if(array_has($conf, 'shops') && count($conf['shops']) > 0 && $tap->isOrgMaster('shop'))
            {{-- label --}}
            @if(array_has($conf, 'label_num') && $conf['label_num'] > 0)
                <a href="#" class="btn btn-warning" data-toggle="modal" data-target="#print"><i class="fa fa-tags" aria-hidden="true"></i>
                    <br/><small>{{ $conf['label_num'] }}</small>
                </a>
            @endif
            {{-- shop list --}}
            @foreach ($conf['shops'] as $shop)
                @if(Input::has('shop') && Input::get('shop') == $shop->shop)
            <a href="javascript:setShop({{ $shop->shop }})" class="btn btn-info">
                @elseif(!Input::has('shop') && $shop->shop_name == null)
            <a href="javascript:setShop({{ $shop->shop }})" class="btn btn-info">
                @else
            <a href="javascript:setShop({{ $shop->shop }})" class="btn btn-default">
                @endif
                {{ $shop->shop_name ? $shop->shop_name : "仓库" }}<br/>
                <small>{{ $shop->all_num.' /￥'.floatval($shop->all_price) }}</small>
            </a> 
            @endforeach
        @else
            @if(array_has($conf, 'shop_info') && count($conf['shop_info']) > 0 && $tap->inOrg('shop'))
            <div class="alert alert-info col-xs-10 col-xs-offset-1">
                
                <strong>{{ $conf['shop_info'][0]['shop_name'] }}: </strong>存货: {{ $conf['shop_info'][0]['all_num'].'件, 共'.$conf['shop_info'][0]['all_price'].'元; '}}
                @if(isset($conf['shop_info'][1]))
                销售累计: {{ $conf['shop_info'][1]['all_num'].'件, 共'.$conf['shop_info'][1]['all_price'].'元; '}}
                @endif
                
            </div>
            @endif
        @endif

</div>
<!-- table -->
    <div class="row">
        @if(array_has($conf, 'records') && count($conf['records']) > 0)
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>编号</th>
                    <th>名称</th>
                    <th>价格</th>
                    <th>货号</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($conf['records'] as $record)
                <tr>
                    @if($record->sold)
                    <td><a class="btn btn-xs btn-danger" href="javascript:sale({{ $record->id }})"> {{ $record->sn }}</a></td>
                    @else
                    <td><a class="btn btn-xs btn-success" href="javascript:sale({{ $record->id }})"> {{ $record->sn }}</a></td>
                    @endif
                    <td>{{ $record->fashion_name.'('.$record->goods_name.')' }}</td>
                    <td>{{ floatval($record->goods_price) }}</td>
                    <td>{{ $record->goods_sn }}</td>
                </tr>

                @endforeach
            </tbody>
        </table>


        @else
            <div class="col-md-8 col-md-offset-2">
                <br/>
                <div class="alert alert-warning">仓库记录为空</div>
            </div>
        @endif
    </div>
@if(array_has($conf, 'records') && count($conf['records']) > 0)
<div class="row"><div class="render">{!! $conf['records']->render() !!}</div></div>
@endif
<!-- fix model -->
<div class="modal fade" id="sale-model" tabindex="-1" role="dialog" aria-labelledby="sale-model" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" 
                        aria-hidden="true">×
                </button>
                <h5 class="modal-title" id="modal-title">
                    销售: <span id="offers-title"></span>
                </h5>
            </div>

            <div class="modal-body" id="modal-body">
                    <input type="hidden" id="sns">
                    <div id="sale-img" class="sale-img"></div> 
        <div class="well sale-price">
        <h4 id="sale-sn">ha</h4>
        <small id="sale-details"></small>
        <p id="sale-price"></p>
        
        <div class="form-group"  >
            <label for="name" class="control-label required">成交价格</label>
            <input type="hidden" id="sale-id">
            <input class="form-control" step="0.01" required="required" name="price" type="number" id="price">
        </div>      
        </div>    
        <div id="sale-warn"></div>         
            </div>
            <div class="modal-footer" id="modal-footer">
                <a class="btn btn-default btn-sm" 
                        data-dismiss="modal">关闭
                </a>
                <span id="sale-sub"><a class="btn btn-success btn-sm" href="javascript:submitSale();">确定</a>    </span>
            </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

{{-- label print Model --}}
<div class="modal fade" id="print" tabindex="-1" role="dialog" aria-labelledby="printLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
                <h5 class="modal-title" id="printLabel">
                    标签打印
                </h5>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning"><strong>注意：</strong>下载标签打印文件将同时把系统中的相关物品状态设置为已打印，此操作不可撤销。在确定打印效果前，请妥善保存该文件。</div>
            </div>
            <div class="modal-footer">
                <a class="btn btn-default btn-sm" data-dismiss="modal">离开</a>
                <a class="btn btn-warning btn-sm" href="/excel/offer/label">下载并设置</a>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal -->
</div>

@endsection
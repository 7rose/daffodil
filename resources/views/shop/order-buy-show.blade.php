<?php
    $tap = new RestRose\Pipe\Tap;
    $wechat = new RestRose\Wechat\Enterprise\Api;

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
{{-- wechat files --}}

    <div class="row">
        @if(array_has($conf, 'records') && count($conf['records']) > 0)
        <table class="table table-striped">
            <caption><a href="/order/buy"><i class="fa fa-ship" aria-hidden="true"></i>&nbsp采购单</a> - {{ $conf['pn'] }}</caption>
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
                    <td>{{ $record->buy_num.'/'.$record->num }}</td>
                    <td>
                        @if($record->buy_num == $record->num)
                            <td id="info{{ $record->goods_id }}"><a href="javascript:inOffer({{ $record->goods_id.','.$record->num }})" class="btn btn-xs btn-success">已入库</a>
                        @else
                            <td id="info{{ $record->goods_id }}"><a href="javascript:fix({{ $record->goods_id.','.$record->num }})" class="btn btn-xs btn-warning">入库</a>
                        @endif
                    </td>
                    <!-- exsits in offers -->
                    {{-- @if($record->offer_count)
                    <td id="info{{ $record->goods_id }}"><a href="javascript:fixFind({{ $record->goods_id.','.$record->num }})" class="btn btn-xs btn-success">已入库</a></td>
                    @else
                    <td id="info{{ $record->goods_id }}"><a href="javascript:fix({{ $record->goods_id.','.$record->num }})" class="btn btn-xs btn-warning">入库</a></td>
                    @endif --}}
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
<div class="modal fade" id="fix-model" tabindex="-1" role="dialog" aria-labelledby="fix-model" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" 
                        aria-hidden="true">×
                </button>
                <h5 class="modal-title" id="modal-title">
                    入库
                    <label class="fix-label"><input type="checkbox" name="need_label" checked="checked" value="1">需要打印标签</label>
                </h5>
            </div>
            <form method="POST" action="/offer/create" accept-charset="UTF-8" id="fix-form">
                <!-- {{ csrf_field() }} -->
            <input type="hidden" name="goods_sn" id="goods_sn">
            <input type="hidden" name="order_buy_pn" id="order_buy_pn"  value="{{ $conf['pn'] }}">
            <div class="modal-body" id="modal-body">
                <div id="form-body"></div>
            </div>
            <div class="modal-footer" id="modal-footer">
                <a class="btn btn-default btn-sm" 
                        data-dismiss="modal">关闭
                </a>
                <a class="btn btn-success btn-sm" href="javascript:submitForm();">提交</a>    
            </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script src="{{ URL::asset('//res.wx.qq.com/open/js/jweixin-1.2.0.js') }}" type="text/javascript" ></script>
<script>
    wx.config(<?php echo $wechat->getSignature(false,['scanQRCode']); ?>);

    // wx.ready(function(){
    //      wx.closeWindow();
    // });
    function scan(id)
    {
        wx.scanQRCode({
            desc: 'scanQRCode desc',
            needResult: 1, // 默认为0，扫描结果由微信处理，1则直接返回扫描结果，
            scanType: ["qrCode","barCode"], // 可以指定扫二维码还是一维码，默认二者都有
            success: function (res) {
               // 回调
               //alert(res);
               $('#ca'+id).val(res.resultStr);
            }
            // error: function(res){
            //       if(res.errMsg.indexOf('function_not_exist') > 0){
            //            alert('版本过低请升级');
            //         }
            //  }
        });
    }
</script>

@endsection















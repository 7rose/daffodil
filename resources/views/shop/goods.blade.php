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

{{-- Seek --}}
<div class="row">
	<div class="seek">
		<div class="input-group col-md-4 col-md-offset-4 col-xs-10 col-xs-offset-1">
          
            @if(array_has($conf,'key'))
            	<input type="text" class="form-control" id="key" value="{{ $conf['key'] }}">
            @else
				<input type="text" class="form-control" id="key">
            @endif
            <a class="input-group-addon" href="javascript:seek()">查询</a>
        </div>
	</div>
</div>
{{-- List --}}

{{-- list --}}
<div class="row">
		
	@if(array_has($conf, 'records') && count($conf['records']) > 0)
		@foreach ($conf['records'] as $record)
			<div class="col-md-2 col-sm-3 col-xs-6">

				{{-- border color --}}
				@if($record->hide)
			    	<div class="thumbnail border_danger">
			    @elseif($record->locked && !$record->hide)
					<div class="thumbnail border_warning">
			    @else
			    	<div class="thumbnail">
			    @endif

			    	<a href="/goods/show/{{ $record->id }}">
					<img src="{{ URL::asset($record->img !='' && $record->img != null ? $record->img : 'daffodil/img/default.png') }}"><span class="badge num num-list" id="badge-num">{{ $record->offer_count }}</span>
					</a>
				    <h6>￥{{ $record->price }}<span class="pull-right text-success">#{{ $record->sn }} </span></h6>
				    <small>{{ $record->fashion_name.' ('.$record->name.')' }}<br/></small>
				    

				    @if($tap->isOrgMaster('supplier'))
				    	<div class="btn-group">
							<a class="btn btn-success btn-xs" href="javascript:addCart({{ $record->id }}, 'cart', {{ $record->offer_count }})" >+&nbsp订货单</a>
							<a class="btn btn-default btn-xs" href="javascript:addCart({{ $record->id }}, 'buy', {{ $record->offer_count }})" >+&nbsp采购单</a>
						</div>

				    @else
				    {{-- nomal --}}
				    <a class="btn btn-success btn-block btn-xs" {!! $record->locked || $record->hide ? " disabled=\"disabled\"" : " href=\"javascript:addCart(".$record->id.", 'cart')\""  !!}>+&nbsp订货单</a>
				    @endif

				</div>
			</div>
		@endforeach
	@else
		<div class="col-md-4 col-md-offset-4">
			<div class="alert alert-warning">提示: 没有相关记录</div>
		</div>
	@endif

</div>
@if(array_has($conf, 'records') && count($conf['records']) > 0)
<div class="row"><div class="render">{!! $conf['records']->render() !!}</div></div>
@endif
<script>
	function seek() {
		var key = $("#key").val();
		key = $.trim(key);
		if(key != "") {
			location.href ='/goods?key='+key;
		}else{
			location.href ='/goods';
		}
	}

	function test() {
		$("#user_bage").html("<span class=\"badge num user-ico\">2</span>");
	}
</script>
@endsection
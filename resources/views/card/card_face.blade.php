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
<div class="row">
	
	@if(array_has($conf, 'records') && count($conf['records']) > 0)
	<table class="table table-striped">
		<caption>优惠券</caption>
		<thead>
			<tr>
				<th>折扣率</th>
				<th>对应产品</th>
				<th>券面文字</th>
			</tr>
		</thead>
		<tbody>	

		@foreach ($conf['records'] as $record)
	
		<tr>
			<td>{{ $record->ratio.'%' }}</td>
			<td>{{ $record->item_title }}</td>
			<td>{{ $record->title }}</td>
	
		</tr>

		@endforeach
	</tbody>
</table>		
	@else
		<div class="col-md-4 col-md-offset-4">
			<div class="alert alert-warning">提示: 没有相关记录</div>
		</div>
	@endif

</div>
<div class="row"><div class="render">{!! $conf['records']->render() !!}</div></div>
<script>
	function seek() {
		var key = $("#key").val();
		key = $.trim(key);
		if(key != "") {
			location.href ='/card_face?key='+key;
		}else{
			location.href ='/card_face';
		}
	}
</script>

@endsection
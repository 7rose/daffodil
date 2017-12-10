<?php

if(Session::has('id')) {
	$tap = new RestRose\Pipe\Tap;
}

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
<div class="container">
		
	@if(array_has($conf, 'records') && count($conf['records']) > 0)
		@foreach ($conf['records'] as $record)
			<div class="well">
				<!-- <img class= "card_ico" src="{{ URL::asset($record->item_img !='' && $record->item_img != null ? $record->item_img : 'daffodil/img/default.png') }}"> -->
				<h4>{{ $record->card_face_ratio.'% '.$record->card_face_title }}</h4>
				<small>{{ '来源:'.$record->from.', 可用于:'.$record->item_title.', 截止时间:'.date("Y-m-d H:i:s",$record->expires_in) }}</small>
			</div>
		@endforeach
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
			location.href ='/card?key='+key;
		}else{
			location.href ='/card';
		}
	}
</script>

@endsection
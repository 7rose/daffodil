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
			@if(Session::has('id'))
            <a class="input-group-addon" href="/item/create"> + </a>
            @endif
            
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
		@foreach ($conf['records'] as $record)
			<div class="col-md-2 col-sm-3 col-xs-6">

				{{-- border color --}}
				@if(!$record->show)
			    	<div class="thumbnail border_danger">
			    @elseif($record->locked && $record->show)
					<div class="thumbnail border_warning">
			    @else
			    	<div class="thumbnail">
			    @endif

			    	<a href="/item/show/{{ $record->id }}">
					<img src="{{ URL::asset($record->img !='' && $record->img != null ? $record->img : 'daffodil/img/default.png') }}">
					</a>
				    <h6>￥{{$record->price.' /'.$record->unit_name }}</h6>
				    <small>{{ $record->title.'('.floatval($record->num).')' }}</small>

				    {{-- shop master --}}
				    @if(Session::has('id') && $tap->isOrgMaster('shop'))
				    	<div class="btn-group">
							<a class="btn btn-success btn-xs" href="#" >下单</a>
							<a class="btn btn-default btn-xs" href="#" >+ 添货</a>
						</div>

				    @else
				    {{-- nomal --}}
				    <a class="btn btn-success btn-block btn-xs" href="#">+&nbsp下单</a>
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
<div class="row"><div class="render">{!! $conf['records']->render() !!}</div></div>
<script>
	function seek() {
		var key = $("#key").val();
		key = $.trim(key);
		if(key != "") {
			location.href ='/item?key='+key;
		}else{
			location.href ='/item';
		}
	}
</script>

@endsection
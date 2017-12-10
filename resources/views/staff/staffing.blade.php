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
            <a class="input-group-addon" href="/staff/create"> + </a>
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
				@if($record->hide)
			    	<div class="thumbnail border_danger">
			    @elseif($record->locked && !$record->hide)
					<div class="thumbnail border_warning">
			    @else
			    	@if($tap->isSelf($record->id))
			    		<div class="thumbnail border_info">
			    	@else
			    		@if($tap->hasRights($record->id))
			    			<div class="thumbnail border_success">
			    		@else
			    			<div class="thumbnail">
			    		@endif
			    	@endif
			    @endif

			    	<a href="/staff/show/{{ $record->id }}">
					<img src="{{ URL::asset($record->img !='' && $record->img != null ? $record->img : 'daffodil/img/default.png') }}">
					</a>
					{{-- gender color --}}
					@if($record->gender == 2)
						<h6 class="text_danger">
				    @else
				    	<h6>
				    @endif
				    	{{ $record->name.' - '.$record->sn }}</h6>
				    {{-- end fo gender color --}}

				    <p><small>{{ $record->mobile }}<br/>{{ $record->department_name.' - '.$record->position_name }}</small></p>
				    
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
			location.href ='/staffing?key='+key;
		}else{
			location.href ='/staffing';
		}
	}
</script>

@endsection
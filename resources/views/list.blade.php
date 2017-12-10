<?php
	$t = new RestRose\Pipe\Tap;
?>
@extends('layout')

@section('content')
	@if(isset($nav))
		<ol class="breadcrumb">
			@foreach($nav as $item)
				<li><a href="{{ $item['href'] }}">{{ $item['link'] }}</a></li>
			@endforeach
		</ol>
	@endif
@endsection

@section('container')

@if(!isset($nav))
<div class="top-pad"></div>
@endif

@if(isset($records))
	@foreach ($records as $record)
		<div class="task success">
			@if($record->img != '' && $record->img != null)
			<div class="task-img"><a href="/staff/show/{{ $record->id }}"><img id="user-ico" src="{{ URL::asset($record->img) }}"  class="task-icon img-thumbnail"></a></div>
			@endif
			<div class="desc">
				<div class="title"> <a href="/staff/show/{{ $record->id }}">{{ $record->name }}</a> </div>
				<div>{{ $record->mobile }}</div>
			</div>
			<div class="time">
				<div class="date">{{ $record->sn }}</div>
				<div> {{ $record->department_name }}{{ $record->position_show ? ' - '.$record->position_name : '' }}</div>
			</div>
		</div>
	@endforeach
@else

<div class="container">
	<div class="col-md-4 col-md-offset-4">
		<div class="alert alert-info text-center">
			<p><i class="fa fa-ban fa-2x"></i></p>
			<p>尚无记录</p>
		</div>
	</div>
</div>

@endif

@endsection
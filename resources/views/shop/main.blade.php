<?php
 	$h=new RestRose\Water\Helper;
?>

@extends('../layout')

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


@if(isset($records))

<table class="table table-condensed">
	<thead>
		<tr>
			<th>店名</th>
			<th>成员</th>
		</tr>
	</thead>
	<tbody>
	@foreach ($records as $record)
	
		<tr>
			<td>{{ $record->name }}</td>
			<td class="shop-staff">{{ $h->oneToMany($record->staff_id,$record->staff_name,$record->staff_img,$record->staff_gender) }}<a href="/staff/create?dp={{ $record->id }}">+</a></td>
		</tr>

	@endforeach
</tbody>
</table>

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
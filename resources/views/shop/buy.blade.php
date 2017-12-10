<?php
	$tap = new RestRose\Pipe\Tap;

	$sum_num = 0;
	$sum_price = 0;
	if(array_has($conf, 'records') && count($conf['records']) > 0){
		foreach ($conf['records'] as $record) {
			$sum_num += $record->num;
			$sum_price += $record->num * $record->goods_price;
		}
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
	<div class="row">
		@if(array_has($conf, 'records') && count($conf['records']) > 0)
		<table class="table table-striped">
			<caption>采购单</caption>
			<thead>
				<tr>
					<th>图片</th>
					<th>名称</th>
					<th>单价</th>
					<th>数量</th>
				</tr>
			</thead>
			<tbody>
				@foreach ($conf['records'] as $record)
				<tr>
					<td><img class="img-rounded cart-img" src="{{ $record->goods_img }}"></td>
					<td>{{ $record->fashion_name.'-'.$record->goods_name }}</td>
					<td>{{ $record->goods_price }}</td>
					<td>{{ $record->num }}</td>
				</tr>
				@endforeach
			</tbody>
		</table>

		{{-- next --}}
		<div class="col-md-6 col-md-offset-3">
			<div class="alert alert-warning"><strong>采购单:</strong>{{ " 物品总数: ".$sum_num.'件;  总价: '.$sum_price."元。" }}
			<form method="POST" action="/buy/build" accept-charset="UTF-8">{{ csrf_field() }}
			<button class="btn btn-success" type="submit">生成采购单</button>
			</form>
			</div>

		</div>
		@else
			<div class="col-md-8 col-md-offset-2">
				<div class="alert alert-warning">采购单中没有项目 &nbsp&nbsp<a href="/fashion" class="btn btn-sm btn-success">
				<i class="fa fa-diamond" aria-hidden="true"></i>&nbsp去看看</a></div>
			</div>
		@endif
	</div>
@endsection
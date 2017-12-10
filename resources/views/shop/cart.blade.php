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
			<caption>订货车&nbsp <a href="/cart/cancel" class="btn btn-danger btn-xs"><i class="fa fa-trash-o" aria-hidden="true"></i>&nbsp清空</a></caption>
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
			<div class="alert alert-info"><strong>订货车:</strong>{{ " 物品总数: ".$sum_num.'件;  总价: '.$sum_price."元。" }}<br/>
			<form method="POST" action="/cart/build" accept-charset="UTF-8">{{ csrf_field() }}
			@if($tap->isOrgMaster('shop'))
			<br/>
			<label for="shop" class="control-label required">购方:</label>
				<select class="form-control" required="required" id="shop" name="shop"><option value="" selected="selected">-- 选择 --</option>
					@foreach($tap->listOf('shop') as $key => $value)
						<option value="{{ $key }}">{{ $value }}</option>
					@endforeach
				</select>
			@elseif($tap->inOrg('shop') && !$tap->isOrgMaster('shop'))
					<input type="hidden" name="shop" id="shop" value="{{ $tap->getHeadDepartment() }}">
					<strong>购方:</strong> {{ $tap->getDepartmentName($tap->getHeadDepartment()) }}
			@endif
			<button class="btn btn-success btn-sm" type="submit">生成订货单</button>
			</form>
			</div>

		</div>
		@else
			<div class="col-md-8 col-md-offset-2">
				<div class="alert alert-warning">尚无记录</div>
			</div>
		@endif
	</div>
@endsection
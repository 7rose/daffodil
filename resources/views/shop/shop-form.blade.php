@extends('shop.layout')
@section('container')

<div class="col-md-4 col-md-offset-4">
    <div class="panel panel-default">
      	<div class="panel-heading">
	        <i class="glyphicon glyphicon-map-marker"></i>&nbsp门店
	    </div>
	    <div class="panel-body">
			{!! form($form) !!}
		</div>
	</div>
</div>

@endsection
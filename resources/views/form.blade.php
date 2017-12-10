@extends('layout')

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

@if(!array_has($conf,'nav'))
<div class="top-pad"></div>
@endif

<div class="col-md-4 col-md-offset-4">
    <div class="panel panel-default">
      	<div class="panel-heading">
		
		@if(array_has($conf, 'icon'))
      	<i class="fa fa-{{ $conf['icon'] }}">
      	@endif
      		
      	</i>&nbsp 
		
		@if(array_has($conf, 'title'))
      	{{ $conf['title'] }}
      	@endif

	    </div>
	    <div class="panel-body">
			{!! form($form) !!}
		</div>
	</div>

	@if($errors->any())
	<div class="alert alert-danger">
		<a href="#" class="close" data-dismiss="alert">
			&times;
		</a>

		@foreach($errors->all() as $error)
		 <p><strong>错误:&nbsp</strong>{{ $error }}</p>
		@endforeach
		
	</div>
	@endif

</div>

@endsection
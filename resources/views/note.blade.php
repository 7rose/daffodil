<?php
	$h = new RestRose\Water\Helper;
?>
@extends('layout')
@section('container')

<div class="top-pad"></div>
<div class="col-md-6 col-md-offset-3">
	<div class="alert alert-{{ $h->setClass($conf) }} text-center">
	  <h4>{{ $h->setTitle($conf) }}</h4> 
	  <hr />
		<i class="fa fa-{{ $h->setIcon($conf) }} fa-4x"></i>
	<p>{{ $h->setContent($conf) }}</p>


	   <p>{{ $h->setButton($conf) }}</p>
	</div>
</div>

@endsection
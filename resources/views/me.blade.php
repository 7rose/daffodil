
@extends('layout')
@section('container')
<div class="alert alert-warning col-xs-10  col-xs-offset-1">登录可显示更多功能哦!
  <a href="/login" class="btn btn-success btn-sm">登录</a>
  </div>
<div class="top-pad"></div>
<div class="col-md-6 col-md-offset-3">
	<a class="btn btn-info" href="/test1">test1</a>
	<a class="btn btn-success" href="/test2">test2</a>
	<a class="btn btn-warning" href="/test3">test3</a>
	<a class="btn btn-danger" href="/test4">test4</a><br/>
	<a href="/login" class="btn btn-success btn-sm">登录</a>
</div>

@endsection
<?php
if(Session::has('id')){
	$tap = new RestRose\Pipe\Tap;
}
?>
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
{{-- image cropper --}}
<link rel="stylesheet" type="text/css" href="{{ URL::asset('node_modules/cropper/dist/cropper.min.css') }}" >
<script src="{{ URL::asset('node_modules/cropper/dist/cropper.min.js')}}"></script>
<script src="{{ URL::asset('daffodil/js/crop.js')}}"></script>

<div class="container" id="crop-avatar">
  <div class="col-sm-3">
  	{{-- div color of image --}}
    @if(!$conf['record']->show)
    	<div class="thumbnail border_danger">
    @elseif($conf['record']->locked && $conf['record']->show)
		<div class="thumbnail border_warning">
    @else
    	<div class="thumbnail">
    @endif

    	{{-- auth: edit image --}}
		@if(Session::has('id') && $tap->isOrgMaster('shop'))
    	<div class="avatar-view" title="更换">
		<img src="{{ URL::asset($conf['record']->img !='' && $conf['record']->img != null ? $conf['record']->img : 'daffodil/img/default.png') }}">
		</div>
		@else
			<img src="{{ URL::asset($conf['record']->img !='' && $conf['record']->img != null ? $conf['record']->img : 'daffodil/img/default.png') }}">
		@endif
		{{-- end of auth --}}

	</div>
</div>

<div class="col-sm-9">
    <div class="panel panel-default">
	    <div class="panel-body">
	    	{{-- base --}}
	        <table class="table table-striped">
			  <caption>基本信息 <br/>
			  {{-- can modify --}}
			  @if(Session::has('id') && $tap->isOrgMaster('shop'))
			  <a class="btn btn-success btn-sm" href="/card_face/generate/{{ $conf['record']->id }}">优惠券</a>
			  <a class="btn btn-info btn-sm" href="/item/edit/{{ $conf['record']->id }}">修改</a>&nbsp<a class="btn btn-danger btn-sm" href="#" data-toggle="modal" data-target="#del">删除</a>
			  	@if($conf['record']->locked)
			  		<a class="btn btn-success btn-sm" href="/item/unlock/{{ $conf['record']->id }}">解锁</a>
			  	@else
			  		<a class="btn btn-warning btn-sm" href="/item/lock/{{ $conf['record']->id }}">锁定</a>
			  	@endif
			  @endif
			  </caption>

			  <tbody>
			  	<tr>
			      <td>类型</td>
			      <td>{{ $conf['record']->type_name }}</td>
			    </tr>
			    <tr>
			      <td>单位</td>
			      <td>{{ $conf['record']->unit_name }}</td>
			    </tr>
			    <tr>
			      <td>名称</td>
			      <td>{{ $conf['record']->title }}</td>
			    </tr>
			    <tr>
			      <td>备注</td>
			      <td>{{ $conf['record']->summary }}</td>
			    </tr>
			  </tbody>
			</table>

	    </div>
	</div>
</div>


    <!-- Cropping modal -->
    <div class="modal fade" id="avatar-modal" aria-hidden="true" aria-labelledby="avatar-modal-label" role="dialog" tabindex="-1">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <form class="avatar-form" action="/item/image/{{ $conf['record']->id }}" enctype="multipart/form-data" method="post">
            <div class="modal-header">
              <button class="close" data-dismiss="modal" type="button">&times;</button>
              <h4 class="modal-title" id="avatar-modal-label">更换图片</h4>
            </div>
            <div class="modal-body">
              <div class="avatar-body">

                <!-- Upload image and data -->
                <div class="avatar-upload">
                  <input type="hidden" value="{{ csrf_token() }}" id="csfr">
                  <input class="avatar-src" name="avatar_src" type="hidden">
                  <input class="avatar-data" name="avatar_data" type="hidden">
                  <label for="avatarInput"><span class="btn btn-success btn-sm"><i class="glyphicon glyphicon-folder-open"></i>&nbsp选择图片</span></label>
                  <div style="display:none;"><input class="avatar-input" id="avatarInput" name="avatar_file" type="file"></div>
                  
                </div>

                <!-- Crop and preview -->
                <div class="row">
                  <div class="col-md-9">
                    <div class="avatar-wrapper"></div>
                  </div>
                </div>

                <div class="row avatar-btns">
                  <div class="col-md-9">
                    <div class="btn-group">
                      <button class="btn btn-primary" data-method="rotate" data-option="-90" type="button" title="Rotate -90 degrees"><- 旋转</button>
                    <div class="btn-group">
                      <button class="btn btn-primary" data-method="rotate" data-option="90" type="button" title="Rotate 90 degrees">旋转 -></button>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <button class="btn btn-primary btn-block avatar-save" type="submit">确定</button>
                  </div>
                </div>
              </div>
            </div>
            <!-- <div class="modal-footer">
              <button class="btn btn-default" data-dismiss="modal" type="button">Close</button>
            </div> -->
          </form>
        </div>
      </div>
    </div><!-- /.modal -->

    <!-- Loading state -->
    <div class="loading" aria-label="Loading" role="img" tabindex="-1"></div>
  </div>

{{-- Note Modal --}}
<div class="modal fade" id="del" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
					&times;
				</button>
				<h4 class="modal-title" id="myModalLabel">
					确认删除
				</h4>
			</div>
			<div class="modal-body">
				删除操作是不可恢复的, 您可以点删除继续, 或者点击关闭本窗口
			</div>
			<div class="modal-footer">
				<a class="btn btn-default" data-dismiss="modal">关闭
				</a>
				<a href="/item/delete/{{ $conf['record']->id }}" class="btn btn-danger">
					删除!
				</a>
			</div>
		</div>
	</div>

@endsection
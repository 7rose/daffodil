<?php
	$tap = new RestRose\Pipe\Tap;
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
    @if($conf['record']->hide)
    	<div class="thumbnail border_danger">
    @elseif($conf['record']->locked && !$conf['record']->hide)
		<div class="thumbnail border_warning">
    @else
    	<div class="thumbnail">
    @endif

    	{{-- auth: edit image --}}
		@if($tap->isSelf($conf['record']->id))
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
			  @if($tap->hasRights($conf['record']->id))
			  <a class="btn btn-info btn-sm" href="/staff/edit/{{ $conf['record']->id }}">修改</a>&nbsp<a class="btn btn-danger btn-sm" href="#" data-toggle="modal" data-target="#del">删除</a>
				  @if($conf['record']->locked)
				  	<a class="btn btn-success btn-sm" href="/staff/unlock/{{ $conf['record']->id }}">解锁</a>
				  @else
				  <a class="btn btn-warning btn-sm" href="/staff/lock/{{ $conf['record']->id }}">锁定</a>
				  @endif
			  @endif
			  </caption>

			  <tbody>
			  	<tr>
			      <td>编号</td>
			      <td>{{ $conf['record']->sn }}</td>
			    </tr>
			  	<tr>
			      <td>姓名</td>
			      <td>{{ $conf['record']->name }}</td>
			    </tr>
			    <tr>
			      <td>性别</td>
			      <td>{{ $conf['record']->gender_name }}</td>
			    </tr>
			    <tr>
			      <td>部门</td>
			      <td>{{ $conf['record']->department_name }}</td>
			    </tr>
			    <tr>
			      <td>职位</td>
			      <td>{{ $conf['record']->position_name }}</td>
			      
			    </tr>
			    <tr>
			      <td>电话</td>
			      <td>{{ $conf['record']->mobile }}</td>
			    </tr>
			    <tr>
			      <td>备注</td>
			      <td>{{ $conf['record']->content }}</td>
			    </tr>
			  </tbody>
			</table>
			{{-- hide --}}
			@if($conf['record']->hide || $conf['record']->locked)
				@if($conf['record']->hide)
				<div class="alert alert-danger"><strong>注意:</strong>这个记录为隐藏状态, 除管理员外的其他人均不可见. 管理员可以通过修改参数使得本记录对所有有权看到的用户可见!</div>
				@endif

				@if($conf['record']->locked)
				<div class="alert alert-warning"><strong>注意:</strong>这个记录被锁定, 对用户可见但不能用于任何操作, 管理员可以通过修改参数使之可用</div>
				@endif
			@else
				{{-- nomal --}}
				{{-- img --}}
				@if($conf['record']->img == '' || $conf['record']->img == null)
					<div class="alert alert-warning"><strong>提示:</strong> 图片未设置, 本人可以点击图像进行操作</div>
				@endif
			@endif

	    </div>
	</div>
</div>


    <!-- Cropping modal -->
    <div class="modal fade" id="avatar-modal" aria-hidden="true" aria-labelledby="avatar-modal-label" role="dialog" tabindex="-1">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <form class="avatar-form" action="/staff/image/{{ $conf['record']->id }}" enctype="multipart/form-data" method="post">
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
				<a href="/staff/delete/{{ $conf['record']->id }}" class="btn btn-danger">
					删除!
				</a>
			</div>
		</div>
	</div>

@endsection
<?php
  $t = new RestRose\Pipe\Tap;
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
{{-- image cropper --}}
<link rel="stylesheet" type="text/css" href="{{ URL::asset('node_modules/cropper/dist/cropper.min.css') }}" >
<script src="{{ URL::asset('node_modules/cropper/dist/cropper.min.js')}}"></script>
<script src="{{ URL::asset('daffodil/js/crop.js')}}"></script>




<div class="container" id="crop-avatar">
  <div class="col-md-6 col-md-offset-3">
    <!-- Current avatar -->

    <div class="avatar-view" title="换  !">
      <img src="{{ URL::asset($record->img != '' ? $record->img : 'upload/personal/eva.png') }}"  alt="头像">
    </div>


    <h5>{{ $record->name }}</h5>
      <table class="table">
        <tr><td>{{ $record->sn }}</td></tr>
        <tr><td>{{ $record->mobile }}</td></tr>
        <tr><td>{{ $record->department_name }} {{ $record->position_show ? ' - '.$record->position_name : '' }}</td></tr>
      </table>
    </div>


    <!-- Cropping modal -->
    <div class="modal fade" id="avatar-modal" aria-hidden="true" aria-labelledby="avatar-modal-label" role="dialog" tabindex="-1">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <form class="avatar-form" action="/staff/image" enctype="multipart/form-data" method="post">
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
                  <label for="avatarInput">本地文件</label>
                  <input class="avatar-input" id="avatarInput" name="avatar_file" type="file">
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

@endsection
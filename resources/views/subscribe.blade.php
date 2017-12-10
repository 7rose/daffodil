@extends('layout')
@section('container')

<div class="top-pad"></div>
<div class="col-md-4 col-md-offset-4">

<div class="panel panel-info">
    <div class="panel-heading">
        <span class="glyphicon glyphicon-barcode"></span>&nbsp关注好生活, 关注乐万家!
    </div>
    <div id="qrcode" class="panel-body">
        <img src="data:image/png;base64, {!! base64_encode(Qrcode::format('png')->errorCorrection('H')->size(200)->merge(URL::asset('logo.png'),.2,true)->encoding('UTF-8')->generate($url)) !!} ">
    </div>
</div>

	

</div>

@endsection
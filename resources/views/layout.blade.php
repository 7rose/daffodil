<?php
$head_img='daffodil/img/default.png';

if(Session::has('openid')){
    $wechat = App\Wechat::where('openid', Session::get('openid'))->first();
    if($wechat->headimgurl != null) $head_img = $wechat->headimgurl;
}
if(Session::has('id')){
    $tap = new RestRose\Pipe\Tap;
    $me = App\Staff::find(Session::get('id'));
    if($me->img != null) $head_img = $me->img;
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>好生活,乐万家!</title>
    <!-- Bootstrap  -->
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('node_modules/bootstrap/dist/css/bootstrap.min.css') }}" >
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('node_modules/font-awesome/css/font-awesome.min.css') }}" >
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('daffodil/css/style.css') }}" >
    <script src="{{ URL::asset('node_modules/jquery/dist/jquery.min.js')}}"></script>
    <script src="{{ URL::asset('node_modules/bootstrap/dist/js/bootstrap.min.js')}}"></script>
</head>
<body>
    {{-- navigation --}}
    <nav class="navbar navbar-default navbar-fixed-top">
       <div class="navbar-inner">
       <a href="/" class="navbar-brand logo"><img class="logo" src="{{ URL::asset('custom/images/logo.svg') }}" ></a>
       </div>
       {{-- icon --}}
       @if(Session::has('id'))
       <ul class="pull-right">
         <li class="dropdown" ><a href="#" id="user" class="dropdown-toggle" data-toggle="dropdown">

         <img id="user-ico" src="{{ URL::asset($head_img) }}"  class="img-circle"><span id="user-bage" class="badge num user-ico"></span></a>


             <ul id= "user-drop" class="dropdown-menu pull-right" role="menu" aria-labelledby="user">
                <li>
                    <a href="/cart"><span class="glyphicon glyphicon-shopping-cart user-menu-ico"></span>订货单<span id="menu-cart" class="badge bg-success"></span></a>
                </li>

                {{-- master of supplier --}}
                @if($tap->isOrgMaster('supplier'))
                <li>
                    <a href="/buy"><span class="glyphicon glyphicon-list user-menu-ico"></span>采购单<span id="menu-buy" class="badge bg-info"></span></a>
                </li>
                @endif

                <li class="divider"></li>
                <li>
                    <a href="/staff/show"><span class="glyphicon glyphicon-home user-menu-ico"></span>个人中心</a>
                </li>
                <li>
                    <a href="/panel"><span class="glyphicon glyphicon-th user-menu-ico"></span>我的应用</a>
                </li>
                
                <li class="divider"></li>
                <li>
                    <a href="/logout"><span class="glyphicon glyphicon-off user-menu-ico"></span>退出{{ ' - '.$tap->fullSQL()->name }}</a>
                </li>
            </ul>
        </li>
       </ul>
    @elseif(Session::has('openid'))
       <ul class="pull-right">
         <li class="dropdown" ><a href="#" id="user" class="dropdown-toggle" data-toggle="dropdown">

         <img id="user-ico" src="{{ URL::asset($head_img) }}" class="img-circle"><span id="user-bage" class="badge num user-ico"></span></a>


             <ul id= "user-drop" class="dropdown-menu pull-right" role="menu" aria-labelledby="user">
                <li>
                    <a href="/home"><span class="glyphicon glyphicon-home user-menu-ico"></span>{{ $wechat->nickname }}</a>
                </li>
                <li class="divider"></li>
                <li>
                    <a href="/panel"><span class="glyphicon glyphicon-th user-menu-ico"></span>应用中心</a>
                </li>
    
            </ul>
        </li>
       </ul>
    @endif

       {{-- end of icon --}}
    </nav>
    <div class="top-height"></div>

    @yield('content')

    <div class="container">
        @yield("container")
    </div>

    {{-- footer --}}
    <div class="footer">
        <p><small>&copy2009-2017, 句容市乐万家早餐有限公司</small></p>
        <p><small><a href="http://www.miitbeian.gov.cn/">苏ICP备17042629号-1</a></small></p>
    </div>
    {{-- js for Cart --}}
<script src="{{ URL::asset('daffodil/js/cart.js')}}"></script>
</body>
</html>
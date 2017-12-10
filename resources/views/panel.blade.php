<?php
  if(Session::has('id')) {
    $tap = new RestRose\Pipe\Tap;
  }
  $helper = new RestRose\Water\Helper;
?>
@extends('layout')
@section('container')

    {{-- nedd login --}}
@if(Session::has('id'))
    
    {{-- shop --}}
    @if($tap->isOrgMaster('shop'))
    <div class="col-md-2 col-sm-4 col-xs-4 wel-grid text-center">
        <a href="/shop">
          <img src="daffodil/img/ico/shop-cart.svg" class="img-circle panel-icon">
        </a>
          <h5>门店</h5>
    </div>
    @endif

   {{-- staff --}}
   @if(!$tap->independentOrg())
    <div class="col-md-2 col-sm-4 col-xs-4 wel-grid text-center">
        <a href="/staffing">
          <img src="daffodil/img/ico/user.svg" class="img-circle panel-icon">
        </a>
          <h5>成员</h5>
    </div>
  @endif

    {{-- supplier --}}
    <div class="col-md-2 col-sm-4 col-xs-4 wel-grid text-center">
        <a href="">
          <img src="daffodil/img/ico/supplier-lwj.svg" class="img-circle panel-icon">
        </a>
          <h5>供应商</h5>
    </div>

    {{-- finance --}}
    <div class="col-md-2 col-sm-4 col-xs-4 wel-grid text-center">
        <a href="">
          <img src="daffodil/img/ico/finance-lwj.svg" class="img-circle panel-icon">
        </a>
          <h5>财务</h5>
    </div>

  {{-- need admin --}}
  @if($tap->isAdmin())
  {{-- structure --}}
    <div class="col-md-2 col-sm-4 col-xs-4 wel-grid text-center">
        <a href="">
          <img src="daffodil/img/ico/structure-lwj.svg" class="img-circle panel-icon">
        </a>
          <h5>企业架构</h5>
    </div>
  
    {{-- auth --}}
    <div class="col-md-2 col-sm-4 col-xs-4 wel-grid text-center">
        <a href="">
          <img src="daffodil/img/ico/auth.svg" class="img-circle panel-icon">
        </a>
          <h5>权限管理</h5>
    </div>
  @endif
  {{-- end of advice --}}
@else

<div class="row">

</div>
@endif
    {{-- advice --}}
    <div class="col-md-2 col-sm-4 col-xs-4 wel-grid text-center">
        <a href="/wechat/subscribe">
          <img src="daffodil/img/ico/advice.svg" class="img-circle panel-icon">
        </a>
          <h5>有奖推荐</h5>
    </div>

    {{-- breakfast --}}
    <div class="col-md-2 col-sm-4 col-xs-4 wel-grid text-center">
        <a href="/item">
          <img src="daffodil/img/ico/item.svg" class="img-circle panel-icon">
        </a>
          <h5>时鲜美味</h5>
    </div>

    {{-- breakfast --}}
    <div class="col-md-2 col-sm-4 col-xs-4 wel-grid text-center">
        <a href="/card">
          <img src="daffodil/img/ico/free.svg" class="img-circle panel-icon">
        </a>
          <h5>卡券</h5>
    </div>

    {{-- order --}}
    <div class="col-md-2 col-sm-4 col-xs-4 wel-grid text-center">
        <a href="/order">
          <img src="daffodil/img/ico/order.svg" class="img-circle panel-icon">
        </a>
          <h5>订单</h5>
    </div>
    
    
    {{-- service --}}
    <div class="col-md-2 col-sm-4 col-xs-4 wel-grid text-center">
        <a href="/service">
          <img src="daffodil/img/ico/service.svg" class="img-circle panel-icon">
        </a>
          <h5>客户服务</h5>
    </div>
    
    {{-- home --}}
    <div class="col-md-2 col-sm-4 col-xs-4 wel-grid text-center">
        <a href="/me">
          <img src="daffodil/img/ico/home-lwj.svg" class="img-circle panel-icon">
        </a>
          <h5>我家</h5>
    </div>
  {{-- end of advice --}}


@endsection
<?php
    $tap = new RestRose\Pipe\Tap;
?>
@extends('../layout')

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
    <div class="row">
        @if(array_has($conf, 'records') && count($conf['records']) > 0)
        <table class="table table-striped">
            <caption><i class="fa fa-cubes" aria-hidden="true"></i>&nbsp订货单</caption>
            <thead>
                <tr>
                    <th>单号</th>
                    <th></th>
                    <th>门店</th>
                    <th>日期</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($conf['records'] as $record)
                <tr>
                    {{-- progress --}}
                    @if($record->send_num == 0)
                        <td><a class="btn btn-xs btn-danger" href="/order/show/{{ $record->pn }}"> {{ $record->pn }}</a></td>
                    @elseif($record->send_num > 0 && $record->send_num < $record->sum_num)
                        <td><a class="btn btn-xs btn-warning" href="/order/show/{{ $record->pn }}"> {{ $record->pn }}</a></td>
                    @else
                        <td><a class="btn btn-xs btn-default" href="/order/show/{{ $record->pn }}"> {{ $record->pn }}</a></td>
                    @endif

                    <td>{{ $record->send_num.'/'.$record->sum_num}}</td>
                    <td>{{ $record->shop_name }}</td>
                    <td>{{ $record->created_at }}</td>
                </tr>

                @endforeach
            </tbody>
        </table>


        @else
            <div class="col-md-8 col-md-offset-2">
                <div class="alert alert-warning">尚无记录</div>
            </div>
        @endif
    </div>
@endsection
@extends('man')

@section('man')
    <div class="header">
        @section('header')
            我是头部
        @show
    </div>
    <div class="content">
        @yield('content')
    </div>
    <div class="footer">
        @include('footer', ['title'=>'我是底部 include 语句测试'])
    </div>
@endsection
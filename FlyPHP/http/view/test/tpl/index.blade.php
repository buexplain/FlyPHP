@extends('layout')

@section('title', '</title><script>alert(100)</script><title>我是网页标题')

@section('header')
    @parent
    继承测试 我是最加到头部的内容
@endsection

@section('content')
    @include('title', ['title'=>'if 语句测试'])

    @if($i == 1)
        if 语句测试 {{$i}}
        @elseif($i == 2)
        elseif 语句测试 {{$i}}
        @else
        else 语句测试 {{$i}}
    @endif

    @include('title', ['title'=>'switch 语句测试'])

    @switch($i)@case(1)
        switch 语句测试 注意 第一个case 必须与 switch保持在一行
        @break
        @default
        switch 的 default 测试
    @endswitch

    @include('title', ['title'=>'for 语句测试'])

    @for($i; $i<10; $i++)
        for 语句测试 $i : {{$i}} <br>
    @endfor

    @include('title', ['title'=>'foreach 语句测试'])

    @foreach((array) $i as $k=>$v)
        foreach 语句测试 {{$k}}=>{{$v}}
    @endforeach

    @include('title', ['title'=>'while 语句测试'])

    @while($i<10)
        @php
            $i++
        @endphp
        while 语句测试 continue 语句测试 php标签测试
        @continue
    @endwhile

    @include('title', ['title'=>'json输出 语句测试'])

    @json(['i'=>$i])

    @include('title', ['title'=>'isset 语句测试'])

    @isset($i)
        isset 语句块测试 $i:{{$i}}
    @endisset

    @include('title', ['title'=>'unset 语句测试'])

    unset 语句测试 删除 $i @unset($i)

    @include('title', ['title'=>'empty 语句测试'])

    @empty($i)
        empty 语句块测试
    @endempty

    @include('title', ['title'=>'or输出 语句测试'])

    {{ $i or 'or 输出语句测试 $i 已经被删除' }}

    @include('title', ['title'=>'不转义html输出 语句测试'])

    {!! '<script>document.write("输出未被html转义的变量")</script>' !!}
@endsection
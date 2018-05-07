<html>
<head>
    <title>应用程序名称 - @yield('title', '1000')</title>
</head>
<body>
@include('title', ['title'=>'我的顶级布局 多级继承测试'])
@yield('man')
</body>
</html>
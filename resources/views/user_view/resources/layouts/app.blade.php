<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="description" content="">
    <meta name="author" content="">

    <title>@yield('title')</title>

    <!-- CSS FILES -->
    @include('user_view.resources.assets.css')

    @stack('custom-css')
    <!--

TemplateMo 584 Pod Talk

https://templatemo.com/tm-584-pod-talk

-->
</head>

<body>

    <main>

        @include('user_view.resources.partials.navbar')


        @include('user_view.resources.partials.header')


        @yield('content')
    </main>


    @include('user_view.resources.partials.footer')


    <!-- JAVASCRIPT FILES -->
    @include('user_view.resources.assets.js')

    @stack('custom-js')
    <!-- JAVASCRIPT ENDFILES -->

</body>

</html>

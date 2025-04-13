<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>D-LEMP - {{$title}}</title>
    @stack('styles')
</head>
<body class="bg-grey-8">
<div id="app" v-cloak>
    <x-nav/>
    <div class="container-fluid mt-3">
        @yield('content')
    </div>

    <log ref="logger"></log>
</div>
<div id="loader" class="text-muted">..Loading..</div>

@stack('scripts')
</body>
</html>
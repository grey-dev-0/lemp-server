<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>LEMP - {{$title}}</title>
    @stack('styles')
</head>
<body>
<div id="app" v-cloak>
    <x-nav/>
    <div class="container-fluid mt-3">
        @yield('content')
    </div>
</div>
<div id="loader" class="text-muted">..Loading..</div>

@stack('scripts')
</body>
</html>
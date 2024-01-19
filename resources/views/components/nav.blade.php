<navbar brand="D-LEMP" home="{{route('home')}}" scheme="dark" bg-color="blue-2">
    <nav-item url="{{route('home')}}" @if(Route::is('home')) active @endif>Home</nav-item>
    <nav-item url="{{route('projects')}}" @if(Route::is('projects')) active @endif>Projects</nav-item>
    <nav-item url="{{route('domains')}}" @if(Route::is('domains')) active @endif>Domains</nav-item>
</navbar>
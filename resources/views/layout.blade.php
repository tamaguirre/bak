{{-- resources/views/layouts/app.blade.php --}}
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title','Clínica BAK')</title>
    <link rel="icon" href="/favicon.png" sizes="any">
    @vite(['resources/scss/app.scss', 'resources/js/app.js'])
</head>
<body>
<div>
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="/logo.png" alt="BAK" height="35" class="logo-bak">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div id="mainNav" class="collapse navbar-collapse">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/">Inicio</a>
                    </li>

                    @if(in_array(auth()->user()->role_id, [1, 2]))
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle"
                               href="#" id="productsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Administración
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="productsDropdown">
                                <li>
                                    <a class="dropdown-item" href="/users">Usuarios</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="/rooms">Pabellones</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="/shifts">Turnos</a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link" href="/calendar" >
                                Reservas
                            </a>
                        </li>
                    @elseif(auth()->user()->role_id == 4)
                        <li class="nav-item dropdown">
                            <a class="nav-link" href="/my-reservations" >
                                Mis Reservas
                            </a>
                        </li>
                    @endif
                </ul>
                <ul class="navbar-nav ms-auto">
                    @auth
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center gap-2"
                               href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <span>{{ auth()->user()->name }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item">Salir</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <main class="py-4">
        <div class="container" id="app">
            @yield('content')
        </div>
    </main>
</div>
</body>
</html>

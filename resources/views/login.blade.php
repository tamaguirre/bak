<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title','Clínica BAK')</title>
    <link rel="icon" href="/favicon.png" sizes="any">
    @vite(['resources/scss/app.scss', 'resources/js/app.js'])
</head>
<body>
<div class="d-flex justify-content-center align-items-center min-vh-100">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="card shadow">
                    <div class="card-body p-4">
                        <img src="/logo.png" alt="BAK" class="img-fluid mb-3">
                        <h2 class="text-center mb-4">Iniciar Sesión</h2>

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="form-group mb-3">
                                <label class="form-label" for="email">Email</label>
                                <input class="form-control" type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>
                            </div>

                            <div class="form-group mb-3">
                                <label class="form-label" for="password">Contraseña</label>
                                <input class="form-control" type="password" id="password" name="password" required>
                            </div>

                            <div class="form-group mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                                    <label class="form-check-label" for="remember">
                                        Recordarme
                                    </label>
                                </div>
                            </div>

                            <div class="d-grid">
                                <button class="btn btn-primary text-white" type="submit">Iniciar Sesión</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>

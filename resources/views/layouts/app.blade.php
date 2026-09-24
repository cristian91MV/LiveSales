<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'LiveSales')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">

        <a class="navbar-brand fw-bold" href="{{ route('dashboard') }}">
            LiveSales
        </a>

        <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarLiveSales"
        >
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarLiveSales">

            <ul class="navbar-nav me-auto">

                <li class="nav-item">
                    <a
                        class="nav-link"
                        href="{{ route('dashboard') }}"
                    >
                        Dashboard
                    </a>
                </li>

                @role('Administrador')
                    <li class="nav-item">
                        <a
                            class="nav-link"
                            href="{{ route('users.index') }}"
                        >
                            Usuarios
                        </a>
                    </li>
                @endrole

            </ul>

            <div class="d-flex align-items-center gap-3 text-white">

                <div class="text-end d-none d-md-block">

                    <div>
                        {{ auth()->user()->name }}
                    </div>

                    <small class="text-white-50">
                        {{ auth()->user()->getRoleNames()->first() ?? 'Sin rol' }}
                    </small>

                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <button
                        type="submit"
                        class="btn btn-outline-light btn-sm"
                    >
                        Cerrar sesión
                    </button>
                </form>

            </div>

        </div>

    </div>
</nav>

<main class="container py-4">

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>
        </div>
    @endif

    @yield('content')

</main>

</body>
</html>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'LiveSales')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>

    <nav class="navbar navbar-dark bg-dark">
        <div class="container d-flex flex-wrap align-items-center">

            {{-- Marca --}}
            <a class="navbar-brand fw-bold me-4" href="{{ route('dashboard') }}">
                LiveSales
            </a>

            {{-- Menú principal --}}
            <div class="d-flex flex-wrap align-items-center gap-3">

                <a class="text-decoration-none text-light" href="{{ route('dashboard') }}">
                    Dashboard
                </a>

                <a class="text-decoration-none text-light" href="{{ route('products.index') }}">
                    Productos
                </a>

                @role('Administrador')
                    <a class="text-decoration-none text-light" href="{{ route('users.index') }}">
                        Usuarios
                    </a>

                    <a class="text-decoration-none text-light" href="{{ route('categories.index') }}">
                        Categorías
                    </a>
                @endrole

            </div>

            {{-- Usuario y logout --}}
            <div class="ms-auto d-flex align-items-center gap-3">

                @auth
                    <span class="text-light">
                        {{ auth()->user()->name }}

                        @if (auth()->user()->roles->isNotEmpty())
                            <small class="text-light opacity-75">
                                ({{ auth()->user()->roles->first()->name }})
                            </small>
                        @endif
                    </span>

                    <form action="{{ route('logout') }}" method="POST" class="m-0">
                        @csrf

                        <button type="submit" class="btn btn-outline-light btn-sm">
                            Cerrar sesión
                        </button>
                    </form>
                @endauth

            </div>

        </div>
    </nav>


    <main>
        <div class="container py-4">

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}

                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                </div>
            @endif


            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}

                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                </div>
            @endif


            @yield('content')

        </div>
    </main>

</body>

</html>

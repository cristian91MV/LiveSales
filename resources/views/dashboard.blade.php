@extends('layouts.app')

@section('title', 'Dashboard - LiveSales')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h1 class="h3 mb-1">
            Dashboard
        </h1>

        <p class="text-muted mb-0">
            Bienvenido, {{ auth()->user()->name }}.
        </p>
    </div>

</div>

<div class="row g-4">

    <div class="col-md-6 col-lg-4">

        <div class="card border-0 shadow-sm h-100">

            <div class="card-body">

                <h5 class="card-title">
                    Usuario actual
                </h5>

                <p class="mb-1">
                    <strong>Nombre:</strong>
                    {{ auth()->user()->name }}
                </p>

                <p class="mb-1">
                    <strong>Correo:</strong>
                    {{ auth()->user()->email }}
                </p>

                <p class="mb-0">
                    <strong>Rol:</strong>
                    {{ auth()->user()->getRoleNames()->first() ?? 'Sin rol' }}
                </p>

            </div>

        </div>

    </div>

    @role('Administrador')

        <div class="col-md-6 col-lg-4">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <h5 class="card-title">
                        Gestión de usuarios
                    </h5>

                    <p class="card-text text-muted">
                        Administra las cuentas internas de LiveSales.
                    </p>

                    <a
                        href="{{ route('users.index') }}"
                        class="btn btn-primary"
                    >
                        Ver usuarios
                    </a>

                </div>

            </div>

        </div>

    @endrole

</div>

@endsection

@extends('layouts.app')

@section('title', 'Nuevo usuario - LiveSales')

@section('content')

<div class="row justify-content-center">

    <div class="col-lg-8">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-1">
                    Nuevo usuario
                </h1>

                <p class="text-muted mb-0">
                    Registra un nuevo usuario interno.
                </p>
            </div>

            <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
                Volver
            </a>
        </div>

        <div class="card border-0 shadow-sm">

            <div class="card-body p-4">

                <form method="POST" action="{{ route('users.store') }}">

                    @csrf

                    <div class="mb-3">

                        <label for="name" class="form-label">
                            Nombre
                        </label>

                        <input
                            type="text"
                            name="name"
                            id="name"
                            value="{{ old('name') }}"
                            class="form-control @error('name') is-invalid @enderror"
                            required
                        >

                        @error('name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    <div class="mb-3">

                        <label for="email" class="form-label">
                            Correo electrónico
                        </label>

                        <input
                            type="email"
                            name="email"
                            id="email"
                            value="{{ old('email') }}"
                            class="form-control @error('email') is-invalid @enderror"
                            required
                        >

                        @error('email')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    <div class="mb-3">

                        <label for="role" class="form-label">
                            Rol
                        </label>

                        <select
                            name="role"
                            id="role"
                            class="form-select @error('role') is-invalid @enderror"
                            required
                        >

                            <option value="">
                                Selecciona un rol
                            </option>

                            <option
                                value="Administrador"
                                @selected(old('role') === 'Administrador')
                            >
                                Administrador
                            </option>

                            <option
                                value="Vendedor"
                                @selected(old('role') === 'Vendedor')
                            >
                                Vendedor
                            </option>

                        </select>

                        @error('role')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    <div class="row">

                        <div class="col-md-6 mb-3">

                            <label for="password" class="form-label">
                                Contraseña
                            </label>

                            <input
                                type="password"
                                name="password"
                                id="password"
                                class="form-control @error('password') is-invalid @enderror"
                                required
                            >

                            @error('password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        <div class="col-md-6 mb-3">

                            <label for="password_confirmation" class="form-label">
                                Confirmar contraseña
                            </label>

                            <input
                                type="password"
                                name="password_confirmation"
                                id="password_confirmation"
                                class="form-control"
                                required
                            >

                        </div>

                    </div>

                    <div class="d-flex justify-content-end gap-2">

                        <a
                            href="{{ route('users.index') }}"
                            class="btn btn-outline-secondary"
                        >
                            Cancelar
                        </a>

                        <button type="submit" class="btn btn-primary">
                            Crear usuario
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>

@endsection

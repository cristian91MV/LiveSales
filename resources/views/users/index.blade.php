@extends('layouts.app')

@section('title', 'Usuarios - LiveSales')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1">Usuarios</h1>
        <p class="text-muted mb-0">
            Gestiona los usuarios internos de LiveSales.
        </p>
    </div>

    <a href="{{ route('users.create') }}" class="btn btn-primary">
        Nuevo usuario
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">

                <thead class="table-light">
                    <tr>
                        <th>Nombre</th>
                        <th>Correo</th>
                        <th>Rol</th>
                        <th>Estado</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse ($users as $user)

                        <tr>
                            <td>
                                {{ $user->name }}
                            </td>

                            <td>
                                {{ $user->email }}
                            </td>

                            <td>
                                <span class="badge bg-secondary">
                                    {{ $user->getRoleNames()->first() ?? 'Sin rol' }}
                                </span>
                            </td>

                            <td>
                                @if ($user->is_active)
                                    <span class="badge bg-success">
                                        Activo
                                    </span>
                                @else
                                    <span class="badge bg-danger">
                                        Inactivo
                                    </span>
                                @endif
                            </td>

                            <td class="text-end">

                                <a
                                    href="{{ route('users.edit', $user) }}"
                                    class="btn btn-sm btn-outline-primary"
                                >
                                    Editar
                                </a>

                                @if ($user->is_active)

                                    <form
                                        action="{{ route('users.deactivate', $user) }}"
                                        method="POST"
                                        class="d-inline"
                                    >
                                        @csrf
                                        @method('PATCH')

                                        <button
                                            type="submit"
                                            class="btn btn-sm btn-outline-danger"
                                            onclick="return confirm('¿Deseas desactivar este usuario?')"
                                        >
                                            Desactivar
                                        </button>
                                    </form>

                                @else

                                    <form
                                        action="{{ route('users.activate', $user) }}"
                                        method="POST"
                                        class="d-inline"
                                    >
                                        @csrf
                                        @method('PATCH')

                                        <button
                                            type="submit"
                                            class="btn btn-sm btn-outline-success"
                                        >
                                            Activar
                                        </button>
                                    </form>

                                @endif

                            </td>
                        </tr>

                    @empty

                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">
                                No existen usuarios registrados.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>
        </div>

    </div>
</div>

<div class="mt-4">
    {{ $users->links() }}
</div>

@endsection

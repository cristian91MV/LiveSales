@extends('layouts.app')

@section('title', 'Categorías')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Categorías</h1>
            <p class="text-muted mb-0">
                Administra las categorías utilizadas por los productos.
            </p>
        </div>

        <a href="{{ route('categories.create') }}"
           class="btn btn-primary">
            Nueva categoría
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            @if ($categories->isEmpty())
                <div class="text-center py-5">
                    <h5>No existen categorías registradas.</h5>

                    <p class="text-muted">
                        Crea la primera categoría para comenzar
                        a registrar productos.
                    </p>

                    <a href="{{ route('categories.create') }}"
                       class="btn btn-primary">
                        Crear categoría
                    </a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Productos</th>
                                <th>Fecha de creación</th>
                                <th class="text-end">Acciones</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($categories as $category)
                                <tr>
                                    <td>
                                        <strong>{{ $category->name }}</strong>
                                    </td>

                                    <td>
                                        <span class="badge text-bg-secondary">
                                            {{ $category->products_count }}
                                        </span>
                                    </td>

                                    <td>
                                        {{ $category->created_at->format('d/m/Y H:i') }}
                                    </td>

                                    <td class="text-end">
                                        <a
                                            href="{{ route('categories.edit', $category) }}"
                                            class="btn btn-sm btn-outline-primary"
                                        >
                                            Editar
                                        </a>

                                        <form
                                            action="{{ route('categories.destroy', $category) }}"
                                            method="POST"
                                            class="d-inline"
                                            onsubmit="return confirm('¿Seguro que deseas eliminar esta categoría?');"
                                        >
                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="btn btn-sm btn-outline-danger"
                                            >
                                                Eliminar
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $categories->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

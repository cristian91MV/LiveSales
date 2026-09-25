@extends('layouts.app')

@section('title', 'Productos')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Productos</h1>

            <p class="text-muted mb-0">
                Catálogo interno de LiveSales.
            </p>
        </div>

        @role('Administrador')
            <a href="{{ route('products.create') }}" class="btn btn-primary">
                Nuevo producto
            </a>
        @endrole
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body">

            <form action="{{ route('products.index') }}" method="GET" class="row g-3">
                <div class="col-lg-5">
                    <label class="form-label">
                        Buscar
                    </label>

                    <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                        placeholder="Código o nombre">
                </div>

                <div class="col-lg-3">
                    <label class="form-label">
                        Categoría
                    </label>

                    <select name="category" class="form-select">
                        <option value="">
                            Todas
                        </option>

                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected(request('category') == $category->id)>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-lg-2">
                    <label class="form-label">
                        Estado
                    </label>

                    <select name="status" class="form-select">
                        <option value="">
                            Todos
                        </option>

                        @foreach ($statuses as $status)
                            <option value="{{ $status->value }}" @selected(request('status') === $status->value)>
                                {{ $status->value }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-lg-2 d-flex align-items-end gap-2">
                    <button class="btn btn-primary w-100">
                        Filtrar
                    </button>

                    <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
                        Limpiar
                    </a>
                </div>
            </form>

        </div>
    </div>


    <div class="card shadow-sm">
        <div class="card-body">

            @if ($products->isEmpty())

                <div class="text-center py-5">
                    <h5>No se encontraron productos.</h5>

                    <p class="text-muted mb-0">
                        Registra un producto o modifica los filtros.
                    </p>
                </div>
            @else
                <div class="table-responsive">

                    <table class="table table-hover align-middle">

                        <thead>
                            <tr>
                                <th>Foto</th>
                                <th>Código</th>
                                <th>Producto</th>
                                <th>Categoría</th>
                                <th>Talla</th>
                                <th>Precio</th>
                                <th>Conservación</th>
                                <th>Estado</th>
                                <th></th>
                            </tr>
                        </thead>

                        <tbody>

                            @foreach ($products as $product)
                                <tr>

                                    <td style="width: 80px;">

                                        @if ($product->primaryPhoto)
                                            <img src="{{ asset('storage/' . $product->primaryPhoto->path) }}"
                                                alt="{{ $product->name }}" class="rounded border"
                                                style="width: 60px; height: 60px; object-fit: cover;">
                                        @else
                                            <div class="bg-light border rounded d-flex align-items-center justify-content-center text-muted"
                                                style="width: 60px; height: 60px;">
                                                Sin foto
                                            </div>
                                        @endif

                                    </td>

                                    <td>
                                        <strong>{{ $product->code }}</strong>
                                    </td>

                                    <td>
                                        {{ $product->name }}
                                    </td>

                                    <td>
                                        {{ $product->category->name }}
                                    </td>

                                    <td>
                                        {{ $product->size ?: '—' }}
                                    </td>

                                    <td>
                                        Bs {{ number_format((float) $product->base_price, 2) }}
                                    </td>

                                    <td>
                                        <span class="badge text-bg-secondary">
                                            {{ str_replace('_', ' ', $product->condition->value) }}
                                        </span>
                                    </td>

                                    <td>
                                        @php
                                            $statusClass = match ($product->status->value) {
                                                'DISPONIBLE' => 'text-bg-success',
                                                'RESERVADO' => 'text-bg-warning',
                                                'VENDIDO' => 'text-bg-primary',
                                                'INACTIVO' => 'text-bg-secondary',
                                                default => 'text-bg-dark',
                                            };
                                        @endphp

                                        <span class="badge {{ $statusClass }}">
                                            {{ $product->status->value }}
                                        </span>
                                    </td>

                                    <td class="text-end">

                                        <div class="d-flex justify-content-end gap-1">

                                            <a href="{{ route('products.show', $product) }}"
                                                class="btn btn-sm btn-outline-primary">
                                                Ver
                                            </a>

                                            @role('Administrador')
                                                <a href="{{ route('products.edit', $product) }}"
                                                    class="btn btn-sm btn-outline-secondary">
                                                    Editar
                                                </a>

                                                @if ($product->status === \App\Enums\ProductStatus::AVAILABLE)
                                                    <form
                                                        action="{{ route('products.deactivate', $product) }}"
                                                        method="POST"
                                                        onsubmit="return confirm(
                        '¿Desactivar este producto?'
                    );">
                                                        @csrf
                                                        @method('PATCH')

                                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                                            Desactivar
                                                        </button>

                                                    </form>
                                                @endif
                                            @endrole

                                        </div>

                                    </td>

                                </tr>
                            @endforeach

                        </tbody>

                    </table>

                </div>

                <div class="mt-3">
                    {{ $products->links() }}
                </div>

            @endif

        </div>
    </div>

@endsection

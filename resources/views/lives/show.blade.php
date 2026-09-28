@extends('layouts.app')

@section('content')
    <div class="container py-4">

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        @php
            $badge = match ($liveSession->status) {
                \App\Enums\LiveStatus::SCHEDULED => 'text-bg-secondary',
                \App\Enums\LiveStatus::ACTIVE => 'text-bg-success',
                \App\Enums\LiveStatus::FINISHED => 'text-bg-primary',
                \App\Enums\LiveStatus::CANCELLED => 'text-bg-danger',
            };

            $editable = in_array(
                $liveSession->status,
                [\App\Enums\LiveStatus::SCHEDULED, \App\Enums\LiveStatus::ACTIVE],
                true,
            );
        @endphp

        <div class="d-flex justify-content-between align-items-start mb-4">
            <div>
                <div class="d-flex align-items-center gap-2 mb-1">
                    <h1 class="h3 mb-0">
                        {{ $liveSession->name }}
                    </h1>

                    <span class="badge {{ $badge }}">
                        {{ $liveSession->status->value }}
                    </span>
                </div>

                <p class="text-muted mb-0">
                    Gestión de transmisión
                </p>
            </div>

            <a href="{{ route('lives.index') }}" class="btn btn-outline-secondary">
                Volver
            </a>
        </div>


        {{-- Acciones del Live --}}
        <div class="card shadow-sm mb-4">
            <div class="card-body">

                <div class="d-flex flex-wrap gap-2">

                    @if ($liveSession->status === \App\Enums\LiveStatus::SCHEDULED)
                        <a href="{{ route('lives.edit', $liveSession) }}" class="btn btn-outline-secondary">
                            Editar
                        </a>

                        <form method="POST" action="{{ route('lives.start', $liveSession) }}">
                            @csrf
                            @method('PATCH')

                            <button class="btn btn-success">
                                Iniciar Live
                            </button>
                        </form>

                        <form method="POST" action="{{ route('lives.cancel', $liveSession) }}"
                            onsubmit="return confirm('¿Cancelar este Live?')">
                            @csrf
                            @method('PATCH')

                            <button class="btn btn-outline-danger">
                                Cancelar Live
                            </button>
                        </form>
                    @elseif ($liveSession->status === \App\Enums\LiveStatus::ACTIVE)
                        <form method="POST" action="{{ route('lives.finish', $liveSession) }}"
                            onsubmit="return confirm('¿Finalizar este Live?')">
                            @csrf
                            @method('PATCH')

                            <button class="btn btn-primary">
                                Finalizar Live
                            </button>
                        </form>

                        <form method="POST" action="{{ route('lives.cancel', $liveSession) }}"
                            onsubmit="return confirm('¿Cancelar el Live activo?')">
                            @csrf
                            @method('PATCH')

                            <button class="btn btn-outline-danger">
                                Cancelar Live
                            </button>
                        </form>
                    @else
                        <span class="text-muted">
                            Este Live es histórico y ya no puede modificarse.
                        </span>
                    @endif

                </div>
            </div>
        </div>


        {{-- Información --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header">
                <strong>Información</strong>
            </div>

            <div class="card-body">
                <div class="row g-3">

                    <div class="col-md-3">
                        <small class="text-muted d-block">
                            Programado
                        </small>

                        {{ $liveSession->scheduled_at?->format('d/m/Y H:i') ?? 'Sin fecha' }}
                    </div>

                    <div class="col-md-3">
                        <small class="text-muted d-block">
                            Inicio real
                        </small>

                        {{ $liveSession->started_at?->format('d/m/Y H:i') ?? '—' }}
                    </div>

                    <div class="col-md-3">
                        <small class="text-muted d-block">
                            Finalización
                        </small>

                        {{ $liveSession->ended_at?->format('d/m/Y H:i') ?? '—' }}
                    </div>

                    <div class="col-md-3">
                        <small class="text-muted d-block">
                            Productos
                        </small>

                        {{ $liveSession->liveProducts->count() }}
                    </div>

                    <div class="col-12">
                        <small class="text-muted d-block">
                            Notas
                        </small>

                        {{ $liveSession->notes ?: 'Sin notas.' }}
                    </div>
                </div>
            </div>
        </div>


        {{-- Agregar producto --}}
        @if ($editable)

            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <strong>Agregar producto al Live</strong>
                </div>

                <div class="card-body">

                    @if ($eligibleProducts->isEmpty())
                        <div class="text-muted">
                            No hay productos disponibles para agregar.
                        </div>
                    @else
                        <form method="POST" action="{{ route('lives.products.store', $liveSession) }}"
                            class="row g-3 align-items-end">
                            @csrf

                            <div class="col-md-7">
                                <label for="product_id" class="form-label">
                                    Producto
                                </label>

                                <select name="product_id" id="product_id"
                                    class="form-select @error('product_id') is-invalid @enderror" required>
                                    <option value="">
                                        Seleccionar producto
                                    </option>

                                    @foreach ($eligibleProducts as $product)
                                        <option value="{{ $product->id }}" data-base-price="{{ $product->base_price }}"
                                            @selected(old('product_id') == $product->id)>
                                            {{ $product->code }}
                                            —
                                            {{ $product->name }}
                                            —
                                            Bs {{ number_format((float) $product->base_price, 2) }}
                                        </option>
                                    @endforeach
                                </select>

                                @error('product_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="col-md-3">
                                <label for="live_price" class="form-label">
                                    Precio Live
                                </label>

                                <input type="number" name="live_price" id="live_price" min="0" step="0.01"
                                    value="{{ old('live_price') }}"
                                    class="form-control @error('live_price') is-invalid @enderror" required>

                                @error('live_price')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="col-md-2">
                                <button class="btn btn-primary w-100">
                                    Agregar
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>

        @endif


        {{-- Productos del Live --}}
        <div class="card shadow-sm">
            <div class="card-header">
                <strong>
                    Productos del Live
                </strong>
            </div>

            <div class="card-body p-0">

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">

                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Producto</th>
                                <th>Estado</th>
                                <th>Precio base</th>
                                <th>Precio Live</th>

                                @if ($editable)
                                    <th class="text-end">
                                        Acciones
                                    </th>
                                @endif
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($liveSession->liveProducts as $liveProduct)
                                <tr>
                                    <td>
                                        {{ $liveProduct->product->code }}
                                    </td>

                                    <td>
                                        {{ $liveProduct->product->name }}
                                    </td>

                                    <td>
                                        <span class="badge text-bg-secondary">
                                            {{ $liveProduct->product->status->value }}
                                        </span>
                                    </td>

                                    <td>
                                        Bs
                                        {{ number_format((float) $liveProduct->product->base_price, 2) }}
                                    </td>

                                    <td>
                                        @if ($editable)
                                            <form method="POST"
                                                action="{{ route('lives.products.update', [$liveSession, $liveProduct]) }}"
                                                class="d-flex gap-2">
                                                @csrf
                                                @method('PUT')

                                                <input type="number" name="live_price" min="0" step="0.01"
                                                    value="{{ $liveProduct->live_price }}"
                                                    class="form-control form-control-sm" style="max-width: 130px" required>

                                                <button class="btn btn-sm btn-outline-primary">
                                                    Guardar
                                                </button>
                                            </form>
                                        @else
                                            Bs
                                            {{ number_format((float) $liveProduct->live_price, 2) }}
                                        @endif
                                    </td>

                                    @if ($editable)
                                        <td class="text-end">

                                            <form method="POST"
                                                action="{{ route('lives.products.destroy', [$liveSession, $liveProduct]) }}"
                                                onsubmit="return confirm('¿Retirar este producto del Live?')">
                                                @csrf
                                                @method('DELETE')

                                                <button class="btn btn-sm btn-outline-danger">
                                                    Retirar
                                                </button>
                                            </form>

                                        </td>
                                    @endif

                                </tr>

                            @empty

                                <tr>
                                    <td colspan="{{ $editable ? 6 : 5 }}" class="text-center text-muted py-4">
                                        Este Live todavía no tiene productos.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>


    @if ($editable && $eligibleProducts->isNotEmpty())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const productSelect =
                    document.getElementById('product_id');

                const livePrice =
                    document.getElementById('live_price');

                if (!productSelect || !livePrice) {
                    return;
                }

                productSelect.addEventListener(
                    'change',
                    function() {
                        const option =
                            this.options[this.selectedIndex];

                        const basePrice =
                            option.dataset.basePrice;

                        if (basePrice) {
                            livePrice.value =
                                Number(basePrice).toFixed(2);
                        }
                    }
                );
            });
        </script>
    @endif

@endsection

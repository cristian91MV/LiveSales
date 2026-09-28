@extends('layouts.app')

@section('content')
<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Lives</h1>
            <p class="text-muted mb-0">
                Gestión de transmisiones de venta.
            </p>
        </div>

        <a
            href="{{ route('lives.create') }}"
            class="btn btn-primary"
        >
            Nuevo Live
        </a>
    </div>

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

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form
                method="GET"
                action="{{ route('lives.index') }}"
                class="row g-3 align-items-end"
            >
                <div class="col-md-4">
                    <label
                        for="status"
                        class="form-label"
                    >
                        Estado
                    </label>

                    <select
                        name="status"
                        id="status"
                        class="form-select"
                    >
                        <option value="">
                            Todos
                        </option>

                        @foreach (\App\Enums\LiveStatus::cases() as $liveStatus)
                            <option
                                value="{{ $liveStatus->value }}"
                                @selected($status === $liveStatus->value)
                            >
                                {{ $liveStatus->value }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-auto">
                    <button class="btn btn-primary">
                        Filtrar
                    </button>

                    <a
                        href="{{ route('lives.index') }}"
                        class="btn btn-outline-secondary"
                    >
                        Limpiar
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Estado</th>
                            <th>Programado</th>
                            <th>Inicio</th>
                            <th>Productos</th>
                            <th class="text-end">
                                Acciones
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($liveSessions as $liveSession)
                            <tr>
                                <td>
                                    {{ $liveSession->name }}
                                </td>

                                <td>
                                    @php
                                        $badge = match ($liveSession->status) {
                                            \App\Enums\LiveStatus::SCHEDULED => 'text-bg-secondary',
                                            \App\Enums\LiveStatus::ACTIVE => 'text-bg-success',
                                            \App\Enums\LiveStatus::FINISHED => 'text-bg-primary',
                                            \App\Enums\LiveStatus::CANCELLED => 'text-bg-danger',
                                        };
                                    @endphp

                                    <span class="badge {{ $badge }}">
                                        {{ $liveSession->status->value }}
                                    </span>
                                </td>

                                <td>
                                    {{ $liveSession->scheduled_at?->format('d/m/Y H:i') ?? 'Sin fecha' }}
                                </td>

                                <td>
                                    {{ $liveSession->started_at?->format('d/m/Y H:i') ?? '—' }}
                                </td>

                                <td>
                                    {{ $liveSession->live_products_count }}
                                </td>

                                <td class="text-end">
                                    <a
                                        href="{{ route('lives.show', $liveSession) }}"
                                        class="btn btn-sm btn-outline-primary"
                                    >
                                        Ver
                                    </a>

                                    @if ($liveSession->status === \App\Enums\LiveStatus::SCHEDULED)
                                        <a
                                            href="{{ route('lives.edit', $liveSession) }}"
                                            class="btn btn-sm btn-outline-secondary"
                                        >
                                            Editar
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td
                                    colspan="6"
                                    class="text-center text-muted py-4"
                                >
                                    No se encontraron Lives.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-3">
        {{ $liveSessions->links() }}
    </div>
</div>
@endsection

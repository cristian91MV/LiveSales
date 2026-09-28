@extends('layouts.app')

@section('content')
<div class="container py-4">

    <div class="mb-4">
        <h1 class="h3">Nuevo Live</h1>
        <p class="text-muted">
            Prepara una nueva transmisión de venta.
        </p>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">

            <form
                method="POST"
                action="{{ route('lives.store') }}"
            >
                @csrf

                <div class="mb-3">
                    <label
                        for="name"
                        class="form-label"
                    >
                        Nombre *
                    </label>

                    <input
                        type="text"
                        name="name"
                        id="name"
                        maxlength="150"
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
                    <label
                        for="scheduled_at"
                        class="form-label"
                    >
                        Fecha y hora programada
                    </label>

                    <input
                        type="datetime-local"
                        name="scheduled_at"
                        id="scheduled_at"
                        value="{{ old('scheduled_at') }}"
                        class="form-control @error('scheduled_at') is-invalid @enderror"
                    >

                    @error('scheduled_at')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label
                        for="notes"
                        class="form-label"
                    >
                        Notas
                    </label>

                    <textarea
                        name="notes"
                        id="notes"
                        rows="4"
                        maxlength="5000"
                        class="form-control @error('notes') is-invalid @enderror"
                    >{{ old('notes') }}</textarea>

                    @error('notes')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="d-flex gap-2">
                    <button class="btn btn-primary">
                        Crear Live
                    </button>

                    <a
                        href="{{ route('lives.index') }}"
                        class="btn btn-outline-secondary"
                    >
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

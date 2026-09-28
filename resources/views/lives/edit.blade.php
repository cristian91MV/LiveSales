@extends('layouts.app')

@section('content')
<div class="container py-4">

    <div class="mb-4">
        <h1 class="h3">
            Editar Live
        </h1>

        <p class="text-muted">
            {{ $liveSession->name }}
        </p>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">

            <form
                method="POST"
                action="{{ route('lives.update', $liveSession) }}"
            >
                @csrf
                @method('PUT')

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
                        value="{{ old('name', $liveSession->name) }}"
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
                        value="{{ old(
                            'scheduled_at',
                            $liveSession->scheduled_at?->format('Y-m-d\TH:i')
                        ) }}"
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
                    >{{ old('notes', $liveSession->notes) }}</textarea>

                    @error('notes')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="d-flex gap-2">
                    <button class="btn btn-primary">
                        Guardar cambios
                    </button>

                    <a
                        href="{{ route('lives.show', $liveSession) }}"
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

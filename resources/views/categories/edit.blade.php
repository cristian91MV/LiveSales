@extends('layouts.app')

@section('title', 'Editar categoría')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-1">Editar categoría</h1>

                    <p class="text-muted mb-0">
                        Modifica la información de la categoría.
                    </p>
                </div>

                <a href="{{ route('categories.index') }}"
                   class="btn btn-outline-secondary">
                    Volver
                </a>
            </div>

            <div class="card shadow-sm">
                <div class="card-body">
                    <form
                        action="{{ route('categories.update', $category) }}"
                        method="POST"
                    >
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="name" class="form-label">
                                Nombre
                            </label>

                            <input
                                type="text"
                                name="name"
                                id="name"
                                value="{{ old('name', $category->name) }}"
                                class="form-control @error('name') is-invalid @enderror"
                                maxlength="100"
                                required
                                autofocus
                            >

                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a
                                href="{{ route('categories.index') }}"
                                class="btn btn-outline-secondary"
                            >
                                Cancelar
                            </a>

                            <button
                                type="submit"
                                class="btn btn-primary"
                            >
                                Guardar cambios
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

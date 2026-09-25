@extends('layouts.app')

@section('title', 'Nuevo producto')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1">Nuevo producto</h1>
        <p class="text-muted mb-0">
            Registra una unidad física para utilizarla posteriormente en los Lives.
        </p>
    </div>

    <a href="{{ route('products.index') }}"
       class="btn btn-outline-secondary">
        Volver
    </a>
</div>

@if ($categories->isEmpty())

    <div class="alert alert-warning">
        Antes de registrar productos debes crear al menos una categoría.

        <div class="mt-3">
            <a href="{{ route('categories.create') }}"
               class="btn btn-warning">
                Crear categoría
            </a>
        </div>
    </div>

@else

<div class="card shadow-sm">
    <div class="card-body">

        <form
            action="{{ route('products.store') }}"
            method="POST"
            enctype="multipart/form-data"
        >
            @csrf

            <div class="row g-3">

                <div class="col-md-4">
                    <label for="code" class="form-label">
                        Código
                    </label>

                    <input
                        type="text"
                        name="code"
                        id="code"
                        value="{{ old('code') }}"
                        class="form-control @error('code') is-invalid @enderror"
                        maxlength="50"
                        required
                    >

                    @error('code')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="col-md-8">
                    <label for="name" class="form-label">
                        Nombre
                    </label>

                    <input
                        type="text"
                        name="name"
                        id="name"
                        value="{{ old('name') }}"
                        class="form-control @error('name') is-invalid @enderror"
                        maxlength="150"
                        required
                    >

                    @error('name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="category_id" class="form-label">
                        Categoría
                    </label>

                    <select
                        name="category_id"
                        id="category_id"
                        class="form-select @error('category_id') is-invalid @enderror"
                        required
                    >
                        <option value="">
                            Selecciona una categoría
                        </option>

                        @foreach ($categories as $category)
                            <option
                                value="{{ $category->id }}"
                                @selected(old('category_id') == $category->id)
                            >
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>

                    @error('category_id')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="col-md-3">
                    <label for="size" class="form-label">
                        Talla
                    </label>

                    <input
                        type="text"
                        name="size"
                        id="size"
                        value="{{ old('size') }}"
                        class="form-control @error('size') is-invalid @enderror"
                        maxlength="50"
                        placeholder="Ej. 6-9 meses"
                    >

                    @error('size')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="col-md-3">
                    <label for="base_price" class="form-label">
                        Precio base
                    </label>

                    <div class="input-group">
                        <span class="input-group-text">Bs</span>

                        <input
                            type="number"
                            name="base_price"
                            id="base_price"
                            value="{{ old('base_price') }}"
                            class="form-control @error('base_price') is-invalid @enderror"
                            min="0"
                            step="0.01"
                            required
                        >

                        @error('base_price')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <label for="condition" class="form-label">
                        Estado de conservación
                    </label>

                    <select
                        name="condition"
                        id="condition"
                        class="form-select @error('condition') is-invalid @enderror"
                        required
                    >
                        <option value="">
                            Selecciona una opción
                        </option>

                        @foreach ($conditions as $condition)
                            <option
                                value="{{ $condition->value }}"
                                @selected(old('condition') === $condition->value)
                            >
                                {{ str_replace('_', ' ', $condition->value) }}
                            </option>
                        @endforeach
                    </select>

                    @error('condition')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="status" class="form-label">
                        Estado operativo
                    </label>

                    <select
                        name="status"
                        id="status"
                        class="form-select @error('status') is-invalid @enderror"
                        required
                    >
                        @foreach ($statuses as $status)
                            <option
                                value="{{ $status->value }}"
                                @selected(
                                    old(
                                        'status',
                                        \App\Enums\ProductStatus::AVAILABLE->value
                                    ) === $status->value
                                )
                            >
                                {{ $status->value }}
                            </option>
                        @endforeach
                    </select>

                    @error('status')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="col-12">
                    <label for="description" class="form-label">
                        Descripción
                    </label>

                    <textarea
                        name="description"
                        id="description"
                        rows="3"
                        class="form-control @error('description') is-invalid @enderror"
                    >{{ old('description') }}</textarea>

                    @error('description')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="col-12">
                    <label for="detail_description" class="form-label">
                        Detalles o defectos
                    </label>

                    <textarea
                        name="detail_description"
                        id="detail_description"
                        rows="3"
                        class="form-control @error('detail_description') is-invalid @enderror"
                        placeholder="Obligatorio si seleccionas CON_DETALLES"
                    >{{ old('detail_description') }}</textarea>

                    @error('detail_description')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="col-12">
                    <label for="images" class="form-label">
                        Fotografías
                    </label>

                    <input
                        type="file"
                        name="images[]"
                        id="images"
                        class="form-control @error('images') is-invalid @enderror @error('images.*') is-invalid @enderror"
                        accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                        multiple
                        required
                    >

                    <div class="form-text">
                        Puedes seleccionar varias imágenes. Máximo 4 MB por fotografía.
                    </div>

                    @error('images')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                    @error('images.*')
                        <div class="text-danger small mt-1">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

            </div>

            <div class="d-flex justify-content-end gap-2 mt-4">
                <a
                    href="{{ route('products.index') }}"
                    class="btn btn-outline-secondary"
                >
                    Cancelar
                </a>

                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    Guardar producto
                </button>
            </div>

        </form>

    </div>
</div>

@endif

@endsection

@extends('layouts.app')

@section('title', 'Editar producto')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h1 class="h3 mb-1">
            Editar producto
        </h1>

        <p class="text-muted mb-0">
            {{ $product->code }} - {{ $product->name }}
        </p>
    </div>

    <a
        href="{{ route('products.show', $product) }}"
        class="btn btn-outline-secondary"
    >
        Volver
    </a>

</div>


<div class="card shadow-sm mb-4">

    <div class="card-header">
        Información del producto
    </div>

    <div class="card-body">

        <form
            action="{{ route('products.update', $product) }}"
            method="POST"
            enctype="multipart/form-data"
        >
            @csrf
            @method('PUT')

            <div class="row g-3">

                <div class="col-md-4">

                    <label
                        for="code"
                        class="form-label"
                    >
                        Código
                    </label>

                    <input
                        type="text"
                        name="code"
                        id="code"
                        value="{{ old('code', $product->code) }}"
                        class="form-control @error('code') is-invalid @enderror"
                        required
                    >

                    @error('code')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                <div class="col-md-8">

                    <label
                        for="name"
                        class="form-label"
                    >
                        Nombre
                    </label>

                    <input
                        type="text"
                        name="name"
                        id="name"
                        value="{{ old('name', $product->name) }}"
                        class="form-control @error('name') is-invalid @enderror"
                        required
                    >

                    @error('name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                <div class="col-md-6">

                    <label
                        for="category_id"
                        class="form-label"
                    >
                        Categoría
                    </label>

                    <select
                        name="category_id"
                        id="category_id"
                        class="form-select @error('category_id') is-invalid @enderror"
                        required
                    >

                        @foreach ($categories as $category)

                            <option
                                value="{{ $category->id }}"
                                @selected(
                                    old(
                                        'category_id',
                                        $product->category_id
                                    ) == $category->id
                                )
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

                    <label
                        for="size"
                        class="form-label"
                    >
                        Talla
                    </label>

                    <input
                        type="text"
                        name="size"
                        id="size"
                        value="{{ old('size', $product->size) }}"
                        class="form-control @error('size') is-invalid @enderror"
                    >

                    @error('size')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                <div class="col-md-3">

                    <label
                        for="base_price"
                        class="form-label"
                    >
                        Precio base
                    </label>

                    <div class="input-group">

                        <span class="input-group-text">
                            Bs
                        </span>

                        <input
                            type="number"
                            name="base_price"
                            id="base_price"
                            value="{{ old('base_price', $product->base_price) }}"
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

                    <label
                        for="condition"
                        class="form-label"
                    >
                        Estado de conservación
                    </label>

                    <select
                        name="condition"
                        id="condition"
                        class="form-select @error('condition') is-invalid @enderror"
                        required
                    >

                        @foreach ($conditions as $condition)

                            <option
                                value="{{ $condition->value }}"
                                @selected(
                                    old(
                                        'condition',
                                        $product->condition->value
                                    ) === $condition->value
                                )
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

                    <label
                        for="status"
                        class="form-label"
                    >
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
                                        $product->status->value
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

                    <label
                        for="description"
                        class="form-label"
                    >
                        Descripción
                    </label>

                    <textarea
                        name="description"
                        id="description"
                        rows="3"
                        class="form-control @error('description') is-invalid @enderror"
                    >{{ old('description', $product->description) }}</textarea>

                    @error('description')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                <div class="col-12">

                    <label
                        for="detail_description"
                        class="form-label"
                    >
                        Detalles o defectos
                    </label>

                    <textarea
                        name="detail_description"
                        id="detail_description"
                        rows="3"
                        class="form-control @error('detail_description') is-invalid @enderror"
                    >{{ old('detail_description', $product->detail_description) }}</textarea>

                    @error('detail_description')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                <div class="col-12">

                    <label
                        for="images"
                        class="form-label"
                    >
                        Agregar fotografías
                    </label>

                    <input
                        type="file"
                        name="images[]"
                        id="images"
                        class="form-control @error('images.*') is-invalid @enderror"
                        accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                        multiple
                    >

                    <div class="form-text">
                        Las fotografías actuales no se eliminarán.
                    </div>

                    @error('images.*')
                        <div class="text-danger small mt-1">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

            </div>


            <div class="d-flex justify-content-end mt-4">

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


<div class="card shadow-sm">

    <div class="card-header">
        Fotografías
    </div>

    <div class="card-body">

        <div class="row g-3">

            @foreach ($product->photos as $photo)

                <div class="col-md-4 col-lg-3">

                    <div class="card h-100">

                        <img
                            src="{{ asset('storage/' . $photo->path) }}"
                            alt="{{ $product->name }}"
                            class="card-img-top"
                            style="height: 180px; object-fit: cover;"
                        >

                        <div class="card-body">

                            @if ($photo->is_primary)

                                <span class="badge text-bg-primary mb-2">
                                    Principal
                                </span>

                            @else

                                <form
                                    action="{{ route(
                                        'products.photos.primary',
                                        [$product, $photo]
                                    ) }}"
                                    method="POST"
                                    class="mb-2"
                                >
                                    @csrf
                                    @method('PATCH')

                                    <button
                                        type="submit"
                                        class="btn btn-sm btn-outline-primary w-100"
                                    >
                                        Hacer principal
                                    </button>

                                </form>

                            @endif


                            <form
                                action="{{ route(
                                    'products.photos.destroy',
                                    [$product, $photo]
                                ) }}"
                                method="POST"
                                onsubmit="return confirm(
                                    '¿Eliminar esta fotografía?'
                                );"
                            >
                                @csrf
                                @method('DELETE')

                                <button
                                    type="submit"
                                    class="btn btn-sm btn-outline-danger w-100"
                                >
                                    Eliminar
                                </button>

                            </form>

                        </div>

                    </div>

                </div>

            @endforeach

        </div>

    </div>

</div>

@endsection

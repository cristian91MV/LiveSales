@extends('layouts.app')

@section('title', $product->name)

@section('content')

<div class="d-flex gap-2">

    @role('Administrador')
        <a
            href="{{ route('products.edit', $product) }}"
            class="btn btn-primary"
        >
            Editar
        </a>
    @endrole

    <a
        href="{{ route('products.index') }}"
        class="btn btn-outline-secondary"
    >
        Volver
    </a>

</div>


<div class="row g-4">

    <div class="col-lg-5">

        <div class="card shadow-sm">

            <div class="card-body">

                @if ($product->primaryPhoto)

                    <img
                        src="{{ asset('storage/' . $product->primaryPhoto->path) }}"
                        alt="{{ $product->name }}"
                        class="img-fluid rounded w-100"
                        style="max-height: 500px; object-fit: contain;"
                    >

                @endif

                @if ($product->photos->count() > 1)

                    <div class="row g-2 mt-2">

                        @foreach ($product->photos as $photo)

                            <div class="col-4">

                                <img
                                    src="{{ asset('storage/' . $photo->path) }}"
                                    alt="{{ $product->name }}"
                                    class="img-fluid rounded border w-100"
                                    style="height: 120px; object-fit: cover;"
                                >

                                @if ($photo->is_primary)
                                    <div class="text-center mt-1">
                                        <span class="badge text-bg-primary">
                                            Principal
                                        </span>
                                    </div>
                                @endif

                            </div>

                        @endforeach

                    </div>

                @endif

            </div>

        </div>

    </div>


    <div class="col-lg-7">

        <div class="card shadow-sm">

            <div class="card-body">

                <dl class="row mb-0">

                    <dt class="col-sm-4">
                        Categoría
                    </dt>

                    <dd class="col-sm-8">
                        {{ $product->category->name }}
                    </dd>


                    <dt class="col-sm-4">
                        Precio base
                    </dt>

                    <dd class="col-sm-8">
                        Bs {{ number_format((float) $product->base_price, 2) }}
                    </dd>


                    <dt class="col-sm-4">
                        Talla
                    </dt>

                    <dd class="col-sm-8">
                        {{ $product->size ?: 'No especificada' }}
                    </dd>


                    <dt class="col-sm-4">
                        Estado operativo
                    </dt>

                    <dd class="col-sm-8">
                        {{ $product->status->value }}
                    </dd>


                    <dt class="col-sm-4">
                        Conservación
                    </dt>

                    <dd class="col-sm-8">
                        {{ str_replace('_', ' ', $product->condition->value) }}
                    </dd>


                    <dt class="col-sm-4">
                        Descripción
                    </dt>

                    <dd class="col-sm-8">
                        {{ $product->description ?: 'Sin descripción.' }}
                    </dd>


                    <dt class="col-sm-4">
                        Detalles
                    </dt>

                    <dd class="col-sm-8">
                        {{ $product->detail_description ?: 'Sin detalles registrados.' }}
                    </dd>


                    <dt class="col-sm-4">
                        Registrado
                    </dt>

                    <dd class="col-sm-8">
                        {{ $product->created_at->format('d/m/Y H:i') }}
                    </dd>

                </dl>

            </div>

        </div>

    </div>

</div>

@endsection

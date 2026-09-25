@extends('layouts.app')

@section('title', 'Editar cliente - LiveSales')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h1 class="h3 mb-1">
            Editar cliente
        </h1>

        <p class="text-muted mb-0">
            {{ $customer->name }}
        </p>
    </div>

    <a
        href="{{ route('customers.show', $customer) }}"
        class="btn btn-outline-secondary"
    >
        Volver
    </a>

</div>


<div class="card shadow-sm">

    <div class="card-body">

        <form
            action="{{ route('customers.update', $customer) }}"
            method="POST"
        >
            @csrf
            @method('PUT')

            <div class="row g-3">

                <div class="col-md-12">

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
                        value="{{ old('name', $customer->name) }}"
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

                    <label
                        for="tiktok_username"
                        class="form-label"
                    >
                        Usuario de TikTok
                    </label>

                    <div class="input-group">

                        <span class="input-group-text">
                            @
                        </span>

                        <input
                            type="text"
                            name="tiktok_username"
                            id="tiktok_username"
                            value="{{ old(
                                'tiktok_username',
                                $customer->tiktok_username
                            ) }}"
                            class="form-control @error('tiktok_username') is-invalid @enderror"
                            maxlength="100"
                        >

                        @error('tiktok_username')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                </div>


                <div class="col-md-6">

                    <label
                        for="whatsapp"
                        class="form-label"
                    >
                        WhatsApp
                    </label>

                    <input
                        type="tel"
                        name="whatsapp"
                        id="whatsapp"
                        value="{{ old(
                            'whatsapp',
                            $customer->whatsapp
                        ) }}"
                        class="form-control @error('whatsapp') is-invalid @enderror"
                        maxlength="30"
                    >

                    @error('whatsapp')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

            </div>


            <div class="d-flex justify-content-end gap-2 mt-4">

                <a
                    href="{{ route('customers.show', $customer) }}"
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

@endsection

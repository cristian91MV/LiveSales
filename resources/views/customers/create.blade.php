@extends('layouts.app')

@section('title', 'Nuevo cliente - LiveSales')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h1 class="h3 mb-1">
            Nuevo cliente
        </h1>

        <p class="text-muted mb-0">
            Registra un comprador. Solo el nombre es obligatorio.
        </p>
    </div>

    <a
        href="{{ route('customers.index') }}"
        class="btn btn-outline-secondary"
    >
        Volver
    </a>

</div>


<div class="card shadow-sm">

    <div class="card-body">

        <form
            action="{{ route('customers.store') }}"
            method="POST"
        >
            @csrf

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
                        value="{{ old('name') }}"
                        class="form-control @error('name') is-invalid @enderror"
                        maxlength="150"
                        required
                        autofocus
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
                            value="{{ old('tiktok_username') }}"
                            class="form-control @error('tiktok_username') is-invalid @enderror"
                            maxlength="100"
                            placeholder="usuario"
                        >

                        @error('tiktok_username')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    <div class="form-text">
                        Puedes escribirlo con o sin @.
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
                        value="{{ old('whatsapp') }}"
                        class="form-control @error('whatsapp') is-invalid @enderror"
                        maxlength="30"
                        placeholder="+591 7123-4567"
                    >

                    @error('whatsapp')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                    <div class="form-text">
                        Puede registrarse después si todavía no lo conoces.
                    </div>

                </div>

            </div>


            <div class="d-flex justify-content-end gap-2 mt-4">

                <a
                    href="{{ route('customers.index') }}"
                    class="btn btn-outline-secondary"
                >
                    Cancelar
                </a>

                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    Guardar cliente
                </button>

            </div>

        </form>

    </div>

</div>

@endsection

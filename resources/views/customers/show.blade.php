@extends('layouts.app')

@section('title', $customer->name . ' - LiveSales')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h1 class="h3 mb-1">
                {{ $customer->name }}
            </h1>

            <p class="text-muted mb-0">
                Información del cliente
            </p>
        </div>

        <div class="d-flex gap-2">

            <a href="{{ route('customers.edit', $customer) }}" class="btn btn-primary">
                Editar
            </a>

            <a href="{{ route('customers.index') }}" class="btn btn-outline-secondary">
                Volver
            </a>

        </div>

    </div>


    <div class="row g-4">

        <div class="col-lg-7">

            <div class="card shadow-sm">

                <div class="card-header">
                    Datos del cliente
                </div>

                <div class="card-body">

                    <dl class="row mb-0">

                        <dt class="col-sm-4">
                            Nombre
                        </dt>

                        <dd class="col-sm-8">
                            {{ $customer->name }}
                        </dd>


                        <dt class="col-sm-4">
                            TikTok
                        </dt>

                        <dd class="col-sm-8">

                            @if ($customer->tiktok_username)
                                {{ '@' . $customer->tiktok_username }}
                            @else
                                <span class="text-muted">
                                    No registrado
                                </span>
                            @endif

                        </dd>


                        <dt class="col-sm-4">
                            WhatsApp
                        </dt>

                        <dd class="col-sm-8">

                            @if ($customer->whatsapp)
                                {{ $customer->whatsapp }}
                            @else
                                <span class="text-muted">
                                    No registrado
                                </span>
                            @endif

                        </dd>


                        <dt class="col-sm-4">
                            Fecha de registro
                        </dt>

                        <dd class="col-sm-8">
                            {{ $customer->created_at->format('d/m/Y H:i') }}
                        </dd>


                        <dt class="col-sm-4">
                            Última actualización
                        </dt>

                        <dd class="col-sm-8">
                            {{ $customer->updated_at->format('d/m/Y H:i') }}
                        </dd>

                    </dl>

                </div>

            </div>

        </div>


        <div class="col-lg-5">

            <div class="card shadow-sm">

                <div class="card-header">
                    Historial comercial
                </div>

                <div class="card-body">

                    <p class="text-muted mb-0">
                        El historial de pedidos y el estado de cliente
                        nuevo o recurrente estarán disponibles cuando
                        se implemente Order Management.
                    </p>

                </div>

            </div>

        </div>

    </div>

@endsection

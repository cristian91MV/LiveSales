@extends('layouts.app')

@section('title', 'Clientes - LiveSales')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h1 class="h3 mb-1">
                Clientes Prueba
            </h1>

            <p class="text-muted mb-0">
                Compradores registrados en LiveSales.
            </p>
        </div>

        <a href="{{ route('customers.create') }}" class="btn btn-primary">
            Nuevo cliente
        </a>

    </div>


    <div class="card shadow-sm mb-4">

        <div class="card-body">

            <form action="{{ route('customers.index') }}" method="GET" class="row g-3">

                <div class="col-md-9">

                    <label for="search" class="form-label">
                        Buscar cliente
                    </label>

                    <input type="text" name="search" id="search" value="{{ request('search') }}" class="form-control"
                        placeholder="Nombre, TikTok o WhatsApp">

                </div>


                <div class="col-md-3 d-flex align-items-end gap-2">

                    <button type="submit" class="btn btn-primary flex-grow-1">
                        Buscar
                    </button>

                    <a href="{{ route('customers.index') }}" class="btn btn-outline-secondary">
                        Limpiar
                    </a>

                </div>

            </form>

        </div>

    </div>


    <div class="card shadow-sm">

        <div class="card-body">

            @if ($customers->isEmpty())

                <div class="text-center py-5">

                    <h5>
                        No se encontraron clientes.
                    </h5>

                    <p class="text-muted mb-0">
                        Registra un nuevo cliente o cambia la búsqueda.
                    </p>

                </div>
            @else
                <div class="table-responsive">

                    <table class="table table-hover align-middle">

                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>TikTok</th>
                                <th>WhatsApp</th>
                                <th>Registrado</th>
                                <th class="text-end">
                                    Acciones
                                </th>
                            </tr>
                        </thead>

                        <tbody>

                            @foreach ($customers as $customer)
                                <tr>

                                    <td>
                                        <strong>
                                            {{ $customer->name }}
                                        </strong>
                                    </td>

                                    <td>
                                        @if ($customer->tiktok_username)
                                            {{ '@' . $customer->tiktok_username }}
                                        @else
                                            <span class="text-muted">
                                                No registrado
                                            </span>
                                        @endif
                                    </td>

                                    <td>
                                        @if ($customer->whatsapp)
                                            {{ $customer->whatsapp }}
                                        @else
                                            <span class="text-muted">
                                                No registrado
                                            </span>
                                        @endif
                                    </td>

                                    <td>
                                        {{ $customer->created_at->format('d/m/Y H:i') }}
                                    </td>

                                    <td class="text-end">

                                        <div class="d-flex justify-content-end gap-1">

                                            <a href="{{ route('customers.show', $customer) }}"
                                                class="btn btn-sm btn-outline-primary">
                                                Ver
                                            </a>

                                            <a href="{{ route('customers.edit', $customer) }}"
                                                class="btn btn-sm btn-outline-secondary">
                                                Editar
                                            </a>

                                        </div>

                                    </td>

                                </tr>
                            @endforeach

                        </tbody>

                    </table>

                </div>


                <div class="mt-3">
                    {{ $customers->links() }}
                </div>

            @endif

        </div>

    </div>

@endsection

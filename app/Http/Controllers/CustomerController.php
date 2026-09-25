<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Models\Customer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CustomerController extends Controller
{
    public function index(Request $request): View
    {
        $rawSearch = trim(
            $request->string('search')->toString()
        );

        $tiktokSearch = Str::lower(
            preg_replace('/^@/', '', $rawSearch)
        );

        $phoneSearch = preg_replace(
            '/\D+/',
            '',
            $rawSearch
        );

        $customers = Customer::query()
            ->when(
                $rawSearch !== '',
                function ($query) use (
                    $rawSearch,
                    $tiktokSearch,
                    $phoneSearch
                ) {
                    $query->where(
                        function ($query) use (
                            $rawSearch,
                            $tiktokSearch,
                            $phoneSearch
                        ) {
                            $query->where(
                                'name',
                                'like',
                                "%{$rawSearch}%"
                            );

                            if ($tiktokSearch !== '') {
                                $query->orWhere(
                                    'tiktok_username',
                                    'like',
                                    "%{$tiktokSearch}%"
                                );
                            }

                            if ($phoneSearch !== '') {
                                $query->orWhere(
                                    'whatsapp',
                                    'like',
                                    "%{$phoneSearch}%"
                                );
                            }
                        }
                    );
                }
            )
            ->latest()
            ->paginate(25)
            ->withQueryString();

        return view(
            'customers.index',
            compact('customers')
        );
    }

    public function create(): View
    {
        return view('customers.create');
    }

    public function store(
        StoreCustomerRequest $request
    ): RedirectResponse {
        $customer = Customer::create(
            $request->validated()
        );

        return redirect()
            ->route('customers.show', $customer)
            ->with(
                'success',
                'Cliente registrado correctamente.'
            );
    }

    public function show(Customer $customer): View
    {
        return view(
            'customers.show',
            compact('customer')
        );
    }

    public function edit(Customer $customer): View
    {
        return view(
            'customers.edit',
            compact('customer')
        );
    }

    public function update(
        UpdateCustomerRequest $request,
        Customer $customer
    ): RedirectResponse {
        $customer->update(
            $request->validated()
        );

        return redirect()
            ->route('customers.show', $customer)
            ->with(
                'success',
                'Cliente actualizado correctamente.'
            );
    }
}

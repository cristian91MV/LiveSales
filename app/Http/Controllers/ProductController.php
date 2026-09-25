<?php

namespace App\Http\Controllers;

use App\Actions\Products\CreateProductAction;
use App\Actions\Products\UpdateProductAction;
use App\Enums\ProductCondition;
use App\Enums\ProductStatus;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $products = Product::query()
            ->with([
                'category',
                'primaryPhoto',
            ])
            ->when(
                $request->filled('search'),
                function ($query) use ($request) {
                    $search = trim(
                        $request->string('search')->toString()
                    );

                    $query->where(function ($query) use ($search) {
                        $query
                            ->where('code', 'like', "%{$search}%")
                            ->orWhere('name', 'like', "%{$search}%");
                    });
                }
            )
            ->when(
                $request->filled('category'),
                fn ($query) => $query->where(
                    'category_id',
                    $request->input('category')
                )
            )
            ->when(
                $request->filled('status')
                && in_array(
                    $request->input('status'),
                    ProductStatus::values(),
                    true
                ),
                fn ($query) => $query->where(
                    'status',
                    $request->input('status')
                )
            )
            ->latest()
            ->paginate(25)
            ->withQueryString();

        $categories = Category::query()
            ->orderBy('name')
            ->get();

        return view('products.index', [
            'products' => $products,
            'categories' => $categories,
            'statuses' => ProductStatus::cases(),
        ]);
    }

    public function show(Product $product): View
    {
        $product->load([
            'category',
            'photos',
            'primaryPhoto',
        ]);

        return view('products.show', compact('product'));
    }

    public function create(): View
    {
        $categories = Category::query()
            ->orderBy('name')
            ->get();

        return view('products.create', [
            'categories' => $categories,
            'conditions' => ProductCondition::cases(),
            'statuses' => [
                ProductStatus::AVAILABLE,
                ProductStatus::INACTIVE,
            ],
        ]);
    }

    public function store(
        StoreProductRequest $request,
        CreateProductAction $action
    ): RedirectResponse {
        $product = $action->execute(
            $request->validated()
        );

        return redirect()
            ->route('products.show', $product)
            ->with(
                'success',
                'Producto registrado correctamente.'
            );
    }

    public function edit(Product $product): View
    {
        $product->load([
            'category',
            'photos',
            'primaryPhoto',
        ]);

        $categories = Category::query()
            ->orderBy('name')
            ->get();

        return view('products.edit', [
            'product' => $product,
            'categories' => $categories,
            'conditions' => ProductCondition::cases(),
            'statuses' => [
                ProductStatus::AVAILABLE,
                ProductStatus::INACTIVE,
            ],
        ]);
    }

    public function update(
        UpdateProductRequest $request,
        Product $product,
        UpdateProductAction $action
    ): RedirectResponse {
        $product = $action->execute(
            $product,
            $request->validated()
        );

        return redirect()
            ->route('products.show', $product)
            ->with(
                'success',
                'Producto actualizado correctamente.'
            );
    }

    public function deactivate(Product $product): RedirectResponse
    {
        if ($product->status !== ProductStatus::AVAILABLE) {
            return redirect()
                ->route('products.index')
                ->with(
                    'error',
                    'Solo los productos DISPONIBLE pueden desactivarse.'
                );
        }

        $product->update([
            'status' => ProductStatus::INACTIVE,
        ]);

        return redirect()
            ->route('products.index')
            ->with(
                'success',
                'Producto desactivado correctamente.'
            );
    }
}

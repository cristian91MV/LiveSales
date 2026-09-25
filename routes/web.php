<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductPhotoController;
use App\Http\Controllers\CustomerController;

/*
|--------------------------------------------------------------------------
| Rutas públicas
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
});

/*
|--------------------------------------------------------------------------
| Página raíz
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login');
});

/*
|--------------------------------------------------------------------------
| Logout
|--------------------------------------------------------------------------
*/

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

/*
|--------------------------------------------------------------------------
| Rutas internas
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'active'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');


    /*
|--------------------------------------------------------------------------
| Productos - Administrador y Vendedor
|--------------------------------------------------------------------------
*/

    Route::get('/products', [ProductController::class, 'index'])
        ->name('products.index');


    /*
|--------------------------------------------------------------------------
| Productos - solo Administrador
|--------------------------------------------------------------------------
*/

    Route::middleware('role:Administrador')->group(function () {

        Route::get('/products/create', [ProductController::class, 'create'])
            ->name('products.create');

        Route::post('/products', [ProductController::class, 'store'])
            ->name('products.store');

        Route::get('/products/{product}/edit', [ProductController::class, 'edit'])
            ->whereNumber('product')
            ->name('products.edit');

        Route::put('/products/{product}', [ProductController::class, 'update'])
            ->whereNumber('product')
            ->name('products.update');

        Route::patch(
            '/products/{product}/deactivate',
            [ProductController::class, 'deactivate']
        )
            ->whereNumber('product')
            ->name('products.deactivate');

        Route::delete(
            '/products/{product}/photos/{photo}',
            [ProductPhotoController::class, 'destroy']
        )
            ->whereNumber('product')
            ->whereNumber('photo')
            ->name('products.photos.destroy');

        Route::patch(
            '/products/{product}/photos/{photo}/primary',
            [ProductPhotoController::class, 'setPrimary']
        )
            ->whereNumber('product')
            ->whereNumber('photo')
            ->name('products.photos.primary');
    });


    /*
|--------------------------------------------------------------------------
| Detalle de producto - Administrador y Vendedor
|--------------------------------------------------------------------------
*/

    Route::get('/products/{product}', [ProductController::class, 'show'])
        ->whereNumber('product')
        ->name('products.show');

    /*
    |--------------------------------------------------------------------------
    | Gestión de usuarios - solo Administrador
    |--------------------------------------------------------------------------
    */

    Route::middleware('role:Administrador')
        ->prefix('users')
        ->name('users.')
        ->group(function () {

            Route::get('/', [UserController::class, 'index'])
                ->name('index');

            Route::get('/create', [UserController::class, 'create'])
                ->name('create');

            Route::post('/', [UserController::class, 'store'])
                ->name('store');

            Route::get('/{user}/edit', [UserController::class, 'edit'])
                ->name('edit');

            Route::put('/{user}', [UserController::class, 'update'])
                ->name('update');

            Route::patch('/{user}/activate', [UserController::class, 'activate'])
                ->name('activate');

            Route::patch('/{user}/deactivate', [UserController::class, 'deactivate'])
                ->name('deactivate');
        });


    /*
    |--------------------------------------------------------------------------
    | Gestión de categorías - solo Administrador
    |--------------------------------------------------------------------------
    */

    Route::middleware('role:Administrador')
        ->prefix('categories')
        ->name('categories.')
        ->group(function () {

            Route::get('/', [CategoryController::class, 'index'])
                ->name('index');

            Route::get('/create', [CategoryController::class, 'create'])
                ->name('create');

            Route::post('/', [CategoryController::class, 'store'])
                ->name('store');

            Route::get('/{category}/edit', [CategoryController::class, 'edit'])
                ->name('edit');

            Route::put('/{category}', [CategoryController::class, 'update'])
                ->name('update');

            Route::delete('/{category}', [CategoryController::class, 'destroy'])
                ->name('destroy');
        });
    /*
|--------------------------------------------------------------------------
| Gestión de clientes - Administrador y Vendedor
|--------------------------------------------------------------------------
*/
    Route::prefix('customers')
        ->name('customers.')
        ->group(function () {

            Route::get(
                '/',
                [CustomerController::class, 'index']
            )->name('index');

            Route::get(
                '/create',
                [CustomerController::class, 'create']
            )->name('create');

            Route::post(
                '/',
                [CustomerController::class, 'store']
            )->name('store');

            Route::get(
                '/{customer}/edit',
                [CustomerController::class, 'edit']
            )
                ->whereNumber('customer')
                ->name('edit');

            Route::put(
                '/{customer}',
                [CustomerController::class, 'update']
            )
                ->whereNumber('customer')
                ->name('update');

            Route::get(
                '/{customer}',
                [CustomerController::class, 'show']
            )
                ->whereNumber('customer')
                ->name('show');
        });
});

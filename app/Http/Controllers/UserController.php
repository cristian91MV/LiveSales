<?php

namespace App\Http\Controllers;

use App\Actions\Users\DeactivateUserAction;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use RuntimeException;
use View;

class UserController extends Controller
{
    /**
     * Display a listing of users paginated (25 per page) with eager loading of roles.
     */
    public function index()
    {
        $users = User::with('roles')
            ->orderBy('created_at', 'desc')
            ->paginate(25);

        return view('users.index', ['users' => $users]);
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created user in database.
     * Creates the user and assigns the role within a transaction to guarantee atomicity.
     */
    public function store(StoreUserRequest $request)
    {
        DB::transaction(function () use ($request) {
            // Create the user with is_active = true by default
            $user = User::create([
                'name' => $request->validated('name'),
                'email' => $request->validated('email'),
                'password' => $request->validated('password'),
                'is_active' => true,
            ]);

            // Assign the role
            $user->assignRole($request->validated('role'));
        });

        return redirect()
            ->route('users.index')
            ->with('success', 'Usuario creado exitosamente.');
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        return view('users.edit', ['user' => $user]);
    }

    /**
     * Update the specified user in database.
     * Applies protection for the last active Administrator when role changes from Administrator to Vendor.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $validated = $request->validated();
        $oldRole = $user->roles->first()?->name;
        $newRole = $validated['role'];

        // If changing from Administrador to Vendedor, wrap in transaction with lockForUpdate
        if ($oldRole === 'Administrador' && $newRole === 'Vendedor') {
            try {
                DB::transaction(function () use ($user, $validated) {
                    // Get all active Administrators with pessimistic lock
                    $activeAdmins = User::query()
                        ->whereHas('roles', fn ($query) => $query->where('name', 'Administrador'))
                        ->where('is_active', true)
                        ->lockForUpdate()
                        ->get();

                    // Filter excluding the target user
                    $otherActiveAdmins = $activeAdmins->reject(fn ($admin) => $admin->id === $user->id);

                    // If no other active administrators, reject the operation
                    if ($otherActiveAdmins->isEmpty()) {
                        throw new RuntimeException(
                            'No es posible realizar esta operación porque dejaría al sistema sin administradores activos.'
                        );
                    }

                    // Update user
                    $user->name = $validated['name'];
                    $user->email = $validated['email'];

                    if (!empty($validated['password'])) {
                        $user->password = $validated['password'];
                    }

                    $user->save();

                    // Sync the role
                    $user->syncRoles([$validated['role']]);
                });
            } catch (RuntimeException $e) {
                return redirect()
                    ->route('users.edit', $user)
                    ->with('error', $e->getMessage());
            }
        } else {
            // No role change affecting Administrators, update normally
            $user->name = $validated['name'];
            $user->email = $validated['email'];

            if (!empty($validated['password'])) {
                $user->password = $validated['password'];
            }

            $user->save();

            // Sync the role
            $user->syncRoles([$validated['role']]);
        }

        return redirect()
            ->route('users.index')
            ->with('success', 'Usuario actualizado exitosamente.');
    }

    /**
     * Activate the specified user.
     */
    public function activate(User $user)
    {
        $user->is_active = true;
        $user->save();

        return redirect()
            ->route('users.index')
            ->with('success', 'Usuario activado exitosamente.');
    }

    /**
     * Deactivate the specified user.
     * Delegates to DeactivateUserAction and handles the exception if last Administrator.
     */
    public function deactivate(User $user)
    {
        try {
            (new DeactivateUserAction())->execute($user);
        } catch (RuntimeException $e) {
            return redirect()
                ->route('users.index')
                ->with('error', $e->getMessage());
        }

        return redirect()
            ->route('users.index')
            ->with('success', 'Usuario desactivado exitosamente.');
    }
}

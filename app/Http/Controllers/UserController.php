<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\CommonDataService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Muestra la lista de usuarios.
     */
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search', ''));

        $usuarios = User::query()
            // Las socias no se muestran en la lista de usuarios
            ->whereRaw("LOWER(COALESCE(role, '')) != ?", ['socia'])
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('username', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('role', 'like', "%{$search}%");
                });
            })
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('staff.index', [
            'usuarios' => $usuarios,
            'search' => $search,
        ]);
    }

    /**
     * Muestra el formulario para crear al nuevo usuario.
     */
    public function create(): View
    {
        $roles = CommonDataService::getUserRolesAdmin();
        return view('staff.create', compact('roles'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'role' => 'required|string|in:' . implode(',', CommonDataService::getUserRoles()),
            'suspendido' => ['nullable', 'boolean'],
        ]);

        $validated['suspendido'] = $request->boolean('suspendido');

        User::create($validated);

        return redirect()
            ->route('usuarios.index')
            ->with('success', 'Usuario creado correctamente');
    }

    public function edit(User $usuario): View
    {
        $roles = CommonDataService::getUserRolesAdmin();
        return view('staff.edit', compact('usuario', 'roles'));
    }

    public function update(Request $request, User $usuario): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', Rule::unique('users', 'username')->ignore($usuario->id)],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($usuario->id)],
            'password' => ['nullable', 'string', 'min:6', 'confirmed'],
            'role' => 'required|string|in:' . implode(',', CommonDataService::getUserRoles()),
            'suspendido' => ['nullable', 'boolean'],
        ]);

        if (empty($validated['password'])) {
            unset($validated['password']);
        }

        $validated['suspendido'] = $request->boolean('suspendido');

        $usuario->update($validated);

        return redirect()
            ->route('usuarios.index')
            ->with('success', 'Usuario actualizado correctamente');
    }

    public function destroy(User $usuario): RedirectResponse
    {
        if (auth()->id() === $usuario->id) {
            return redirect()
                ->route('usuarios.index')
                ->with('error', 'No puedes eliminar tu propio usuario.');
        }

        $usuario->delete();

        return redirect()
            ->route('usuarios.index')
            ->with('success', 'Usuario eliminado correctamente');
    }
}

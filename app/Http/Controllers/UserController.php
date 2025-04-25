<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;

class UserController extends Controller
{
    use AuthorizesRequests, ValidatesRequests;

    // Em vez de usar o middleware, vamos verificar as permissões diretamente em cada método
    public function index()
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Acesso não autorizado. Apenas administradores podem gerenciar usuários.');
        }
        
        $users = User::with('role')->paginate(10);
        return view('users.index', compact('users'));
    }

    public function create()
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Acesso não autorizado. Apenas administradores podem gerenciar usuários.');
        }
        
        $roles = Role::all();
        return view('users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Acesso não autorizado. Apenas administradores podem gerenciar usuários.');
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role_id' => $validated['role_id'],
        ]);

        return redirect()->route('users.index')->with('success', 'Usuário criado com sucesso!');
    }

    public function edit(User $user)
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Acesso não autorizado. Apenas administradores podem gerenciar usuários.');
        }
        
        $roles = Role::all();
        return view('users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Acesso não autorizado. Apenas administradores podem gerenciar usuários.');
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
        ]);

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role_id' => $validated['role_id'],
        ];

        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $user->update($data);

        return redirect()->route('users.index')->with('success', 'Usuário atualizado com sucesso!');
    }

    public function destroy(User $user)
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Acesso não autorizado. Apenas administradores podem gerenciar usuários.');
        }
        
        $user->delete();
        return redirect()->route('users.index')->with('success', 'Usuário excluído com sucesso!');
    }
}
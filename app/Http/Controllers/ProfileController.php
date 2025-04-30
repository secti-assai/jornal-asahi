<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserInteraction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Exibir perfil do usuário logado
     */
    public function show()
    {
        $user = Auth::user();
        $interactions = $user->receivedInteractions()->latest()->get();
        $likesCount = $user->receivedInteractions()->where('like', true)->count();
        
        return view('profile.show', compact('user', 'interactions', 'likesCount'));
    }
    
    /**
     * Exibir formulário para editar perfil
     */
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }
    
    /**
     * Atualizar perfil do usuário
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => [
                'required',
                'string',
                'alpha_dash',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'description' => 'nullable|string|max:1000',
            'education_level' => 'required|in:Ensino Fundamental,Ensino Médio,Ensino Superior',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        $data = [
            'name' => $validated['name'],
            'username' => $validated['username'],
            'description' => $validated['description'],
            'education_level' => $validated['education_level'],
        ];
        
        // Processar e salvar a imagem de perfil, se fornecida
        if ($request->hasFile('profile_image') && $request->file('profile_image')->isValid()) {
            // Se já existir uma imagem anterior, deletá-la
            if ($user->profile_image) {
                Storage::disk('public')->delete($user->profile_image);
            }
            
            $imagePath = $request->file('profile_image')->store('profile_images', 'public');
            $data['profile_image'] = $imagePath;
        }
        
        $user->update($data);
        
        return redirect()->route('profile.show')->with('success', 'Perfil atualizado com sucesso!');
    }
    
    /**
     * Exibir perfil público de um repórter específico
     */
    public function showPublic($username)
    {
        $user = User::where('username', $username)
                    ->whereIn('role_id', [1, 2, 3]) // Permitir qualquer papel (Reporter, Autorizador, Admin)
                    ->firstOrFail();
        
        $interactions = $user->receivedInteractions()->latest()->get();
        $likesCount = $user->receivedInteractions()->where('like', true)->count();
        
        return view('profile.public', compact('user', 'interactions', 'likesCount'));
    }
    
    /**
     * Exibir lista de todos os repórteres
     */
    public function listReporters()
    {
        // Buscamos usuários com qualquer um dos papéis (1=Reporter, 2=Autorizador, 3=Admin)
        $reporters = User::whereIn('role_id', [1, 2, 3])
                    ->where('username', '!=', null) // Garantir que só pegamos usuários com username definido
                    ->orderBy('name')
                    ->get();
        
        return view('profile.team', compact('reporters'));
    }
    
    /**
     * Adicionar uma interação (comentário/like) ao perfil de um usuário
     */
    public function addInteraction(Request $request, $userId)
    {
        $validated = $request->validate([
            'comment' => 'nullable|string|max:500',
            'like' => 'boolean',
            'visitor_name' => 'required_if:comment,!=,null|nullable|string|max:255',
        ]);
        
        // Verificar se o usuário alvo existe
        $targetUser = User::findOrFail($userId);
        
        // Preparar os dados da interação
        $interactionData = [
            'user_id' => $targetUser->id,
            'comment' => $validated['comment'] ?? null,
            'like' => $validated['like'] ?? false,
            'created_at' => now(), // Garantir que a data atual seja usada
            'updated_at' => now()
        ];
        
        // Se o usuário estiver autenticado, registrar quem fez o comentário
        if (Auth::check()) {
            $interactionData['visitor_id'] = Auth::id();
        } else {
            // Para visitantes anônimos
            $interactionData['visitor_name'] = $validated['visitor_name'] ?? 'Anônimo';
        }
        
        // Criar a interação
        UserInteraction::create($interactionData);
        
        return back()->with('success', 'Sua interação foi registrada com sucesso!');
    }
}
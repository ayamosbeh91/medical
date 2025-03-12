<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AuthController extends Controller
{
    // Connexion
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            return response()->json(['message' => 'Connexion réussie', 'user' => $user]);
        }

        return response()->json(['message' => 'Identifiants invalides'], 401);
    }

    // Inscription
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'role' => 'required|in:patient,doctor,lab', // Rôle obligatoire
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Attribuer un rôle à l'utilisateur
        $role = Role::findByName($request->role);
        $user->assignRole($role);

        return response()->json(['message' => 'Utilisateur inscrit avec succès', 'user' => $user], 201);
    }

    // Déconnexion
    public function logout(Request $request)
    {
        Auth::logout();
        return response()->json(['message' => 'Déconnexion réussie']);
    }
}
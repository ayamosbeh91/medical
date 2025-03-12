<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\AuthController;

class UserController extends Controller
{
    // Liste des patients
    public function indexPatients()
    {
        $patients = User::whereHas('roles', function($query) {
            $query->where('name', 'patient');
        })->get();

        return response()->json($patients);
    }

    // Liste des médecins
    public function indexDoctors()
    {
        $doctors = User::whereHas('roles', function($query) {
            $query->where('name', 'doctor');
        })->get();

        return response()->json($doctors);
    }

    // Liste des laboratoires
    public function indexLabs()
    {
        $labs = User::whereHas('roles', function($query) {
            $query->where('name', 'lab');
        })->get();

        return response()->json($labs);
    }

    // Créer un utilisateur (générique, mais tu peux ajuster selon le rôle)
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'role' => 'required|in:patient,doctor,lab', // Choisir un rôle valide
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        // Assigner le rôle en fonction de l'utilisateur
        $role = Role::findByName($request->role);
        $user->assignRole($role);

        return response()->json(['message' => 'Utilisateur créé avec succès', 'user' => $user]);
    }

    // Mettre à jour un utilisateur
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $request->validate([
            'name' => 'sometimes|required',
            'email' => 'sometimes|required|email|unique:users,email,' . $id,
            'password' => 'nullable|min:6',
            'role' => 'sometimes|required|in:patient,doctor,lab',
        ]);

        $user->update([
            'name' => $request->name ?? $user->name,
            'email' => $request->email ?? $user->email,
            'password' => $request->password ? bcrypt($request->password) : $user->password,
        ]);

        if ($request->role) {
            $role = Role::findByName($request->role);
            $user->syncRoles([$role]);
        }

        return response()->json(['message' => 'Utilisateur mis à jour', 'user' => $user]);
    }

    // Supprimer un utilisateur
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['message' => 'Utilisateur supprimé']);
    }
}

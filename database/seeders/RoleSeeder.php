<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run()
    {
        // Création des rôles
        $admin = Role::create(['name' => 'administrateur']);
        $medecin = Role::create(['name' => 'medecin']);
        $patient = Role::create(['name' => 'patient']);
        $laboratoire = Role::create(['name' => 'laboratoire']);

        // Création des permissions
        Permission::create(['name' => 'gerer utilisateurs']);
        Permission::create(['name' => 'valider inscriptions']);
        Permission::create(['name' => 'voir resultats']);
        Permission::create(['name' => 'interpreter analyses']);
        Permission::create(['name' => 'partager resultats']);

        // Attribution des permissions aux rôles
        $admin->givePermissionTo(['gerer utilisateurs', 'valider inscriptions']);
        $medecin->givePermissionTo(['voir resultats', 'interpreter analyses']);
        $patient->givePermissionTo(['partager resultats']);
        $laboratoire->givePermissionTo(['partager resultats']);
    }
}


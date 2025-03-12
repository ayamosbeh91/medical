<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles; // Ajout du trait HasRoles

    protected $fillable = [
        'cin', 'nom', 'prenom', 'email', 'password', 'role',
        'date_naissance', 'specialite', 'nom_laboratoire', 'adresse'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'date_naissance' => 'date',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            // Supprimer les champs non pertinents selon le rôle
            if ($user->role === 'Patient') {
                $user->specialite = null;
                $user->nom_laboratoire = null;
            } elseif ($user->role === 'Medecin') {
                $user->date_naissance = null; 
                $user->nom_laboratoire = null;
            } elseif ($user->role === 'Laboratoire') {
                $user->date_naissance = null;
                $user->specialite = null;
            }
        });
    }

    /**
     * Vérifier si l'utilisateur est un médecin.
     */
    public function isDoctor(): bool
    {
        return $this->hasRole('Medecin');
    }

    /**
     * Vérifier si l'utilisateur est un patient.
     */
    public function isPatient(): bool
    {
        return $this->hasRole('Patient');
    }

    /**
     * Vérifier si l'utilisateur est un laboratoire.
     */
    public function isLaboratory(): bool
    {
        return $this->hasRole('Laboratoire');
    }
}

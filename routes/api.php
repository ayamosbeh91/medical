<?php
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

/*Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);*/
Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);

// Routes protégées par authentification simple (session)
Route::middleware('auth:api')->group(function () {

    // Routes pour les Patients
    Route::get('patients', [UserController::class, 'patientIndex']);
    Route::get('patients/{id}', [UserController::class, 'patientShow']);
    Route::post('patients', [UserController::class, 'store']);
    Route::put('patients/{id}', [UserController::class, 'patientUpdate']);
    Route::delete('patients/{id}', [UserController::class, 'patientDestroy']);

    // Routes pour les Médecins
    Route::get('medecins', [UserController::class, 'medecinIndex']);
    Route::get('medecins/{id}', [UserController::class, 'medecinShow']);
    Route::post('medecins', [UserController::class, 'store']);
    Route::put('medecins/{id}', [UserController::class, 'medecinUpdate']);
    Route::delete('medecins/{id}', [UserController::class, 'medecinDestroy']);

    // Routes pour les Laboratoires
    Route::get('laboratoires', [UserController::class, 'laboratoireIndex']);
    Route::get('laboratoires/{id}', [UserController::class, 'laboratoireShow']);
    Route::post('laboratoires', [UserController::class, 'store']);
    Route::put('laboratoires/{id}', [UserController::class, 'laboratoireUpdate']);
    Route::delete('laboratoires/{id}', [UserController::class, 'laboratoireDestroy']);
});

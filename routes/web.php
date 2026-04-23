<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use App\Http\Controllers\ResearcherController; // Importante para la tabla
use App\Models\Institution;


Route::inertia('/', 'Welcome', [
    'canRegister' => Features::enabled(Features::registration()),
])->name('home');

// Grupo de rutas que requieren estar Logueado y Verificado
Route::middleware(['auth', 'verified'])->group(function () {
    
    // Sub-grupo que requiere además estar APROBADO (is_active)
    Route::middleware(['is_active'])->group(function () {
        
        Route::inertia('dashboard', 'Dashboard')->name('dashboard');
        
        // Aquí irán todas las aplicaciones del CVIS
        Route::get('/researchers', [ResearcherController::class, 'index'])->name('researchers.index');
        
    });
});

// Ruta pública para cargar instituciones hijas dinámicamente en el registro
Route::get('/api/institutions/{parentId}', function ($parentId) {
    return Institution::where('parent_id', $parentId)
        ->orderBy('name')
        ->get(['id', 'name', 'short_name']);
})->name('api.institutions.index');

require __DIR__.'/settings.php';
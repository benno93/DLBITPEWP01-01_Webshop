<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Startseite
Route::get('/', function () {
    return Inertia::render('Home/Index'); // ← Neuer Pfad
})->name('Home');

// Produkt-Übersicht
Route::get('/products', function () {
    return Inertia::render('Products/Index');
});

// Einzelnes Produkt
Route::get('/products/{id}', function ($id) {
    return Inertia::render('Products/Show', ['id' => $id]);
});

// Warenkorb
Route::get('/cart', function () {
    return Inertia::render('Cart/Index');
});


// Default von Phpstorm:
Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Auth
require __DIR__.'/auth.php';

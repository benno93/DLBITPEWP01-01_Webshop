<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Models\Product;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;

// Startseite
Route::get('/', function () {
    return Inertia::render('Home/Index'); // ← Neuer Pfad
})->name('Home');


// Route für ein einzelnes Produkt
Route::get('/products/{product}', function ($id) {
    // Rendert `resources/js/Pages/Product.vue` und lädt die Daten inklusive Kategorie
    $product = \App\Models\Product::with('category')->findOrFail($id);

    return Inertia::render('Product', [
        'product' => $product
    ]);
})->name('product.show');

// Warenkorb über Controller aufrufen
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
Route::patch('/cart/{id}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{id}', [CartController::class, 'destroy'])->name('cart.destroy');

// About -> Hier richtig!!
Route::get('/about', function () {
    return Inertia::render('About');
})->name('About');

// Catalog
Route::get('/catalog', function () {
    return Inertia::render('Catalog', [
        // 'with' lädt die Relation. 'get()' führt die Abfrage dann aus.
        'products' => Product::with('category')->get()
    ]);
})->name('Catalog');


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

// Checkout: erfordert Login, da Bestellungen fest an einen Benutzer gebunden sind
Route::middleware('auth')->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('Checkout');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/checkout/success/{order}', [CheckoutController::class, 'success'])->name('checkout.success');
});

<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

// Controller zur Verwaltung des Warenkorbs (über Laravel-Sessions und Inertia.js)
class CartController extends Controller
{
    /**
     * Zeigt die Warenkorb-Seite an.
     */
    public function index()
    {
        // Liest den Warenkorb aus der Session aus.
        // Falls noch kein Warenkorb existiert, wird ein leeres Array [] verwendet.
        $cart = session()->get('cart', []);

        // Rendert die Frontend-Komponente "Cart"
        // und übergibt die Warenkorb-Daten als Props.
        return Inertia::render('Cart', [
            'cart' => array_values($cart),
        ]);
    }

    /**
     * Fügt ein Produkt zum Session-Warenkorb hinzu oder erhöht dessen Menge.
     */
    public function store(Request $request)
    {
        // Liest die Produkt-ID und die Menge aus den Formular-/Request-Daten aus.
        // Die Menge wird zu Integer konvertiert; Standardwert ist 1.
        $productId = $request->input('product_id');
        $quantity = (int) $request->input('quantity', 1);

        // Prüft, ob das Produkt in der Datenbank existiert.
        // Bricht mit Fehler 404 ab, falls die ID ungültig ist.
        $product = Product::findOrFail($productId);

        // Aktuellen Warenkorb-Zustand aus der Session laden.
        $cart = session()->get('cart', []);

        // Falls das Produkt bereits im Warenkorb existiert:
        if (isset($cart[$productId])) {
            // Nur die Anzahl um die gewünschte Menge erhöhen.
            $cart[$productId]['quantity'] += $quantity;
        } else {
            // Falls neu: Produkt-Details als neues Element im Warenkorb-Array anlegen.
            $cart[$productId] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'image_url' => $product->image_url,
                'quantity' => $quantity,
            ];
        }

        // Den aktualisierten Warenkorb wieder in der Session speichern.
        session()->put('cart', $cart);

        // Leitet den Nutzer auf die vorherige Seite zurück.
        // Inertia verarbeitet diesen Redirect automatisch und aktualisiert die Seite ohne Neuladen.
        return back();
    }

    // Menge eines Produkts im Warenkorb ändern
    public function update(Request $request, $id)
    {
        $quantity = (int) $request->input('quantity');
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            if ($quantity > 0) {
                $cart[$id]['quantity'] = $quantity;
            } else {
                unset($cart[$id]);
            }
            session()->put('cart', $cart);
        }

        return back();
    }

    // Produkt aus dem Warenkorb entfernen
    public function destroy($id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        return back();
    }
}

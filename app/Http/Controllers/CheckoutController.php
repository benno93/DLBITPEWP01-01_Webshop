<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class CheckoutController extends Controller
{
    // Muss mit den Versandoptionen in resources/js/Pages/Checkout.vue übereinstimmen
    private const SHIPPING_COSTS = [
        'standard' => 4.99,
        'express' => 9.99,
        'pickup' => 0,
    ];

    public function index()
    {
        return Inertia::render('Checkout');
    }

    /**
     * Schließt die Bestellung ab: legt Adressen, Bestellung und Zahlung an
     * und verringert dabei den Lagerbestand der bestellten Produkte.
     */
    public function store(Request $request)
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index');
        }

        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],

            'shipping_street' => ['required', 'string', 'max:255'],
            'shipping_house_number' => ['required', 'string', 'max:20'],
            'shipping_zip_code' => ['required', 'string', 'max:20'],
            'shipping_city' => ['required', 'string', 'max:255'],
            'shipping_country' => ['required', 'string', 'max:255'],

            'billing_same_as_shipping' => ['boolean'],
            'billing_street' => ['required_if:billing_same_as_shipping,false', 'nullable', 'string', 'max:255'],
            'billing_house_number' => ['required_if:billing_same_as_shipping,false', 'nullable', 'string', 'max:20'],
            'billing_zip_code' => ['required_if:billing_same_as_shipping,false', 'nullable', 'string', 'max:20'],
            'billing_city' => ['required_if:billing_same_as_shipping,false', 'nullable', 'string', 'max:255'],
            'billing_country' => ['required_if:billing_same_as_shipping,false', 'nullable', 'string', 'max:255'],

            'shipping_method' => ['required', 'in:standard,express,pickup'],
            'payment_method' => ['required', 'in:paypal,credit_card,invoice,cash_on_delivery'],

            'accept_terms' => ['accepted'],
            'accept_withdrawal' => ['accepted'],
        ]);

        $user = $request->user();

        $order = DB::transaction(function () use ($validated, $cart, $user) {
            $shippingAddress = Address::create([
                'user_id' => $user->id,
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'street' => $validated['shipping_street'],
                'house_number' => $validated['shipping_house_number'],
                'zip_code' => $validated['shipping_zip_code'],
                'city' => $validated['shipping_city'],
                'country' => $validated['shipping_country'],
            ]);

            $billingAddress = ($validated['billing_same_as_shipping'] ?? true)
                ? $shippingAddress
                : Address::create([
                    'user_id' => $user->id,
                    'first_name' => $validated['first_name'],
                    'last_name' => $validated['last_name'],
                    'street' => $validated['billing_street'],
                    'house_number' => $validated['billing_house_number'],
                    'zip_code' => $validated['billing_zip_code'],
                    'city' => $validated['billing_city'],
                    'country' => $validated['billing_country'],
                ]);

            $order = Order::create([
                'user_id' => $user->id,
                'shipping_address_id' => $shippingAddress->id,
                'billing_address_id' => $billingAddress->id,
                'status' => 'pending',
                'total_price' => 0,
            ]);

            $total = self::SHIPPING_COSTS[$validated['shipping_method']];

            foreach ($cart as $productId => $item) {
                // Zeile sperren, damit zwei gleichzeitige Bestellungen sich nicht überbieten können
                $product = Product::whereKey($productId)->lockForUpdate()->first();

                if (! $product || $product->stock < $item['quantity']) {
                    throw ValidationException::withMessages([
                        'stock' => 'Für "' . ($item['name'] ?? 'einen Artikel') . '" ist nicht mehr genügend Bestand vorhanden.',
                    ]);
                }

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['price'],
                ]);

                $product->decrement('stock', $item['quantity']);

                $total += $item['price'] * $item['quantity'];
            }

            $order->update(['total_price' => (int) round($total)]);

            Payment::create([
                'order_id' => $order->id,
                'method' => $validated['payment_method'],
                'status' => 'pending',
            ]);

            return $order;
        });

        session()->forget('cart');

        return redirect()->route('checkout.success', $order);
    }

    public function success(Order $order)
    {
        abort_unless($order->user_id === auth()->id(), 403);

        $order->load('items.product', 'shippingAddress', 'payment');

        return Inertia::render('CheckoutSuccess', [
            'order' => $order,
        ]);
    }
}

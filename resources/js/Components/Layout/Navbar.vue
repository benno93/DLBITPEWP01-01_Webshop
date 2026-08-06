<script setup>
import { computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

const page = usePage();

// Cart Object/Array aus den Inertia Shared Props laden
const cartItems = computed(() => {
    const rawCart = page.props.cart || {};
    return Object.values(rawCart);
});

// Gesamtanzahl der Artikel im Warenkorb berechnen
const itemCount = computed(() => {
    return cartItems.value.reduce((total, item) => total + item.quantity, 0);
});

// Gesamtsumme berechnen
const subtotal = computed(() => {
    const sum = cartItems.value.reduce((total, item) => total + (item.price * item.quantity), 0);
    return sum.toFixed(2);
});
</script>

<template>
    <div class="navbar bg-base-100 shadow-sm sticky top-0 z-50">
        <!-- Linker Bereich mit Logo -->
        <div class="flex-1">
            <Link :href="route('Home')" class="btn btn-ghost text-xl">Webshop</Link>
        </div>

        <!-- Mittlerer Bereich mit den Menüpunkten -->
        <div class="flex-none">
            <ul class="menu menu-horizontal px-1">
                <li>
                    <Link :href="route('Home')">Startseite</Link>
                </li>
                <li>
                    <Link :href="route('Catalog')">Katalog</Link>
                </li>
                <li>
                    <Link :href="route('About')">Über uns</Link>
                </li>
            </ul>
        </div>

        <!-- Rechter Bereich (Warenkorb-Dropdown & Profil) -->
        <div class="flex-1 flex justify-end">
            <div class="flex-none flex items-center gap-2">

                <!-- Dynamisches Warenkorb-Dropdown -->
                <div class="dropdown dropdown-end">
                    <div tabindex="0" role="button" class="btn btn-ghost btn-circle">
                        <div class="indicator">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <!-- Badge zeigt die Gesamtanzahl der Produkte an -->
                            <span v-if="itemCount > 0" class="badge badge-sm badge-primary indicator-item">
                                {{ itemCount }}
                            </span>
                        </div>
                    </div>

                    <div
                        tabindex="0"
                        class="card card-sm dropdown-content bg-base-100 z-50 mt-3 w-52 shadow"
                    >
                        <div class="card-body">
                            <span class="text-lg font-bold">{{ itemCount }} {{ itemCount === 1 ? 'Artikel' : 'Artikel' }}</span>
                            <span class="text-info">Gesamt: {{ subtotal }} €</span>
                            <div class="card-actions">
                                <Link :href="route('cart.index')" class="btn btn-primary btn-block">
                                    Warenkorb anzeigen
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Profil Dropdown -->
                <div class="dropdown dropdown-end">
                    <div tabindex="0" role="button" class="btn btn-ghost btn-circle avatar">
                        <div class="w-10 rounded-full">
                            <img
                                alt="User Avatar"
                                src="https://img.daisyui.com/images/stock/photo-1534528741775-53994a69daeb.webp"
                            />
                        </div>
                    </div>
                    <ul
                        tabindex="-1"
                        class="menu menu-sm dropdown-content bg-base-100 rounded-box z-50 mt-3 w-52 p-2 shadow"
                    >
                        <template v-if="$page.props.auth?.user">
                            <li>
                                <Link :href="route('profile.edit')" class="justify-between">
                                    Profil
                                </Link>
                            </li>
                            <li>
                                <Link :href="route('logout')" method="post" as="button" class="w-full text-left">
                                    Abmelden
                                </Link>
                            </li>
                        </template>
                        <template v-else>
                            <li>
                                <Link :href="route('login')">Anmelden</Link>
                            </li>
                            <li>
                                <Link :href="route('register')">Registrieren</Link>
                            </li>
                        </template>
                    </ul>
                </div>

            </div>
        </div>
    </div>
</template>

<style scoped>
</style>

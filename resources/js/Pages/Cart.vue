<script setup>
import { computed } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import ShopLayout from '@/Layouts/ShopLayout.vue';

const props = defineProps({
    cart: {
        type: [Array, Object],
        default: () => [],
    },
});

// Stellt sicher, dass immer ein Array verarbeitet wird (egal ob Array oder Objekt übergeben wird)
const normalizedCart = computed(() => {
    return Array.isArray(props.cart) ? props.cart : Object.values(props.cart || {});
});

// Gesamtsumme berechnen
const totalPrice = computed(() => {
    return normalizedCart.value.reduce((sum, item) => sum + item.price * item.quantity, 0).toFixed(2);
});

// Gesamtanzahl der Artikel berechnen
const totalItems = computed(() => {
    return normalizedCart.value.reduce((sum, item) => sum + item.quantity, 0);
});

// Menge aktualisieren
function updateQuantity(id, newQuantity) {
    if (newQuantity < 1) return;
    router.patch(route('cart.update', id), {
        quantity: newQuantity,
    }, { preserveScroll: true });
}

// Artikel entfernen
function removeItem(id) {
    router.delete(route('cart.destroy', id), {
        preserveScroll: true,
    });
}
</script>

<template>
    <Head title="Warenkorb" />

    <ShopLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dein Warenkorb
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

                <!-- Fallback: Leerer Warenkorb -->
                <div v-if="normalizedCart.length === 0" class="bg-white p-8 rounded-lg shadow text-center space-y-4">
                    <p class="text-gray-500 text-lg">Dein Warenkorb ist zurzeit leer.</p>
                    <Link :href="route('Catalog')" class="btn btn-primary">
                        Zum Katalog
                    </Link>
                </div>

                <!-- Warenkorb Übersicht -->
                <div v-else class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                    <!-- Artikelliste (2 Spalten) -->
                    <div class="lg:col-span-2 bg-white rounded-lg shadow p-6 space-y-4">
                        <div
                            v-for="item in normalizedCart"
                            :key="item.id"
                            class="flex flex-col sm:flex-row items-center justify-between border-b pb-4 gap-4"
                        >
                            <div class="flex items-center gap-4 w-full sm:w-auto">
                                <img
                                    :src="item.image_url ?? '/images/placeholder.png'"
                                    :alt="item.name"
                                    class="w-16 h-16 object-cover rounded"
                                    @error="$event.target.src = '/images/placeholder.png'"
                                />
                                <div>
                                    <h3 class="font-bold text-gray-800">{{ item.name }}</h3>
                                    <p class="text-sm text-gray-500">{{ item.price }} € pro Stück</p>
                                </div>
                            </div>

                            <div class="flex items-center gap-6 w-full sm:w-auto justify-between sm:justify-end">
                                <!-- Mengenschalter -->
                                <div class="flex items-center gap-2">
                                    <button
                                        @click="updateQuantity(item.id, item.quantity - 1)"
                                        class="btn btn-xs btn-outline"
                                        :disabled="item.quantity <= 1"
                                    >
                                        -
                                    </button>
                                    <span class="w-8 text-center font-semibold">{{ item.quantity }}</span>
                                    <button
                                        @click="updateQuantity(item.id, item.quantity + 1)"
                                        class="btn btn-xs btn-outline"
                                    >
                                        +
                                    </button>
                                </div>

                                <!-- Zwischensumme -->
                                <span class="font-bold w-20 text-right">
                                    {{ (item.price * item.quantity).toFixed(2) }} €
                                </span>

                                <!-- Löschen Button -->
                                <button
                                    @click="removeItem(item.id)"
                                    class="btn btn-ghost btn-xs text-error"
                                    title="Artikel entfernen"
                                >
                                    ✕
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Zusammenfassung (1 Spalte) -->
                    <div class="bg-white rounded-lg shadow p-6 h-fit space-y-4">
                        <h3 class="font-bold text-lg border-b pb-2">Bestellübersicht</h3>

                        <div class="flex justify-between text-sm">
                            <span>Anzahl Artikel:</span>
                            <span class="font-medium">{{ totalItems }}</span>
                        </div>

                        <div class="flex justify-between text-lg font-bold border-t pt-2">
                            <span>Gesamtsumme:</span>
                            <span class="text-primary">{{ totalPrice }} €</span>
                        </div>

                        <Link :href="route('Checkout')" class="btn btn-success w-full mt-4">
                            Zur Kasse gehen
                        </Link>
                    </div>

                </div>

            </div>
        </div>
    </ShopLayout>
</template>

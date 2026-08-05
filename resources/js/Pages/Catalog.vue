<script setup>
import { ref, computed } from 'vue';
import { Head } from '@inertiajs/vue3';
import ShopLayout from '@/Layouts/ShopLayout.vue';
import DaisyCardProduct from '@/Components/DaisyUI/DaisyCardProduct.vue';

const props = defineProps({
    products: {
        type: Array,
        required: true,
        default: () => [],
    },
});

const selectedCategory = ref('');
const maxPrice = ref(null);
const onlyInStock = ref(false);

// Extrahiert nur die Namen der Kategorien als Strings
const categories = computed(() => {
    const names = props.products
        .map(p => p.category?.name)
        .filter(Boolean); // Entfernt undefined / null
    return Array.from(new Set(names)); // Duplikate entfernen
});

const highestProductPrice = computed(() => {
    if (props.products.length === 0) return 1000;
    return Math.ceil(Math.max(...props.products.map(p => p.price)));
});

// Gefilterte Produkte
const filteredProducts = computed(() => {
    return props.products.filter(product => {
        // 1. Kategorie-Filter (Vergleich per Name)
        if (selectedCategory.value && product.category?.name !== selectedCategory.value) {
            return false;
        }

        // 2. Preis-Filter
        if (maxPrice.value !== null && product.price > maxPrice.value) {
            return false;
        }

        // 3. Lagerbestand-Filter (Integer > 0)
        // Falls dein DB-Feld z.B. `stock_quantity` heißt, passe `product.stock` entsprechend an
        if (onlyInStock.value) {
            const stockCount = product.stock ?? product.stock_quantity ?? product.quantity ?? 0;
            if (stockCount <= 0) {
                return false;
            }
        }

        return true;
    });
});

function resetFilters() {
    selectedCategory.value = '';
    maxPrice.value = null;
    onlyInStock.value = false;
}
</script>

<template>
    <Head title="Produktkatalog" />

    <ShopLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Katalog
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

                <!-- Layout-Grid: Links Filter (1 Spalte), Rechts Produkte (3 Spalten) -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 items-start">

                    <!-- Sidebar: Filterleiste -->
                    <aside class="bg-white p-4 rounded-lg shadow space-y-6 border border-gray-100">
                        <div class="flex justify-between items-center">
                            <h3 class="font-bold text-lg text-gray-700">Filter</h3>
                            <button
                                @click="resetFilters"
                                class="text-xs text-blue-600 hover:underline"
                            >
                                Zurücksetzen
                            </button>
                        </div>

                        <!-- 1. Kategorie Filter -->
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Kategorie</label>
                            <select
                                v-model="selectedCategory"
                                class="select select-bordered select-sm w-full"
                            >
                                <option value="">Alle Kategorien</option>
                                <option v-for="cat in categories" :key="cat" :value="cat">
                                    {{ cat }}
                                </option>
                            </select>
                        </div>

                        <!-- 2. Preis Filter -->
                        <div class="space-y-2">
                            <div class="flex justify-between text-sm font-medium text-gray-700">
                                <span>Max. Preis:</span>
                                <span>{{ maxPrice ?? highestProductPrice }} €</span>
                            </div>
                            <input
                                type="range"
                                min="0"
                                :max="highestProductPrice"
                                step="1"
                                v-model.number="maxPrice"
                                class="range range-xs range-primary"
                            />
                        </div>

                        <!-- 3. Auf Lager Filter -->
                        <div class="form-control">
                            <label class="cursor-pointer label justify-start gap-3 p-0">
                                <input
                                    type="checkbox"
                                    v-model="onlyInStock"
                                    class="checkbox checkbox-sm checkbox-primary"
                                />
                                <span class="label-text font-medium text-gray-700">Nur auf Lager</span>
                            </label>
                        </div>
                    </aside>

                    <!-- Hauptbereich: Produkt-Grid -->
                    <main class="md:col-span-3">
                        <!-- Fallback wenn gar keine Produkte geladen wurden -->
                        <div v-if="products.length === 0" class="text-center text-gray-500 py-8 bg-white rounded-lg shadow">
                            Aktuell sind keine Produkte verfügbar.
                        </div>

                        <!-- Fallback wenn der Filter 0 Treffer liefert -->
                        <div v-else-if="filteredProducts.length === 0" class="text-center text-gray-500 py-8 bg-white rounded-lg shadow">
                            Keine Produkte entsprechen deinen Filterkriterien.
                        </div>

                        <!-- Produktliste -->
                        <div v-else class="flex flex-wrap justify-center sm:justify-start gap-6">
                            <DaisyCardProduct
                                v-for="product in filteredProducts"
                                :key="product.id"
                                :product="product"
                                class="w-48 sm:w-56"
                            />
                        </div>
                    </main>

                </div>

            </div>
        </div>
    </ShopLayout>
</template>

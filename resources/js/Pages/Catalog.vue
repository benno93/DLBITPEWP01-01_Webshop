<script setup>
import { Head } from '@inertiajs/vue3';
import ShopLayout from '@/Layouts/ShopLayout.vue';
import DaisyCardProduct from '@/Components/DaisyUI/DaisyCardProduct.vue';

// Definiere die Props, die vom Laravel Controller übergeben werden
defineProps({
    products: {
        type: Array,
        required: true,
        default: () => [],
    },
});
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

                <!-- Fallback, falls keine Produkte aus der Datenbank kommen -->
                <div v-if="products.length === 0" class="text-center text-gray-500 py-8">
                    Aktuell sind keine Produkte verfügbar.
                </div>

                <!-- Responsive Grid für die Produktkarten -->
                <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 justify-items-center">

                    <!-- Du übergibst jetzt einfach das ganze Objekt -->
                    <DaisyCardProduct
                        v-for="product in products"
                        :key="product.id"
                        :product="product"
                    />

                </div>

            </div>
        </div>
    </ShopLayout>
</template>

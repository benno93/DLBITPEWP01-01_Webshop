<script setup>
import { Head } from '@inertiajs/vue3';
import ShopLayout from '@/Layouts/ShopLayout.vue';
import DaisyCardProduct from '@/Components/DaisyUI/DaisyCardProduct.vue';

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

                <!-- Fallback -->
                <div v-if="products.length === 0" class="text-center text-gray-500 py-8">
                    Aktuell sind keine Produkte verfügbar.
                </div>

                <!-- Flexbox: Zentriert die Karten flexibel -->
                <div
                    v-else
                    class="flex flex-wrap justify-center gap-6 max-w-5xl mx-auto"
                >
                    <DaisyCardProduct
                        v-for="product in products"
                        :key="product.id"
                        :product="product"
                        class="w-48 sm:w-56"
                    />
                </div>

            </div>
        </div>
    </ShopLayout>
</template>

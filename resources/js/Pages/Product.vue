<script setup>
import { Head, Link } from '@inertiajs/vue3';
import ShopLayout from '@/Layouts/ShopLayout.vue';

defineProps({
    product: {
        type: Object,
        required: true,
    },
});
</script>

<template>
    <Head :title="product.name" />

    <ShopLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ product.name }}
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

                <!-- Zurück-Button zum Katalog -->
                <Link :href="route('Catalog')" class="btn btn-ghost btn-sm mb-6">
                    &larr; Zurück zum Katalog
                </Link>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                        <!-- Produktbild -->
                        <div>
                            <img
                                :src="product.image_url ?? '/images/placeholder.png'"
                                :alt="product.name"
                                class="rounded-lg shadow-md w-full object-cover max-h-96"
                            />
                        </div>

                        <!-- Produktdetails -->
                        <div class="flex flex-col justify-between">
                            <div>
                                <span v-if="product.category" class="badge badge-secondary mb-2">
                                    {{ product.category.name }}
                                </span>
                                <h1 class="text-3xl font-bold mb-4">{{ product.name }}</h1>
                                <p class="text-2xl font-bold text-primary mb-4">{{ product.price }} €</p>
                                <p class="text-gray-600 mb-6">{{ product.description }}</p>
                            </div>

                            <div class="space-y-4">
                                <div class="text-sm text-gray-500">
                                    Lagerbestand:
                                    <span :class="product.stock > 0 ? 'text-success font-semibold' : 'text-error font-semibold'">
                                        {{ product.stock > 0 ? `${product.stock} Stück auf Lager` : 'Nicht verfügbar' }}
                                    </span>
                                </div>
                                <button class="btn btn-primary w-full" :disabled="product.stock <= 0">
                                    In den Warenkorb
                                </button>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </ShopLayout>
</template>

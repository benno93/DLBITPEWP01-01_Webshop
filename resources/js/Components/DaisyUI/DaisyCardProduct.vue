<script setup>
import { Link } from '@inertiajs/vue3';
import AddToCartButton from '@/Components/AddToCartButton.vue';

defineProps({
    product: {
        type: Object,
        required: true
    }
})
</script>

<template>
    <!-- Karte: Feste Breite (w-96) entfernt, Hover-Effekte und sanfte Schatten hinzugefügt -->
    <div class="card bg-base-100 shadow-md border border-base-200 transition-all duration-300 hover:shadow-xl hover:-translate-y-1">

        <!-- Bild verlinken -->
        <figure class="px-4 pt-4">
            <Link :href="route('product.show', product.id)">
                <img
                    :src="product.image_url ?? 'images/placeholder.png'"
                    :alt="product.name"
                    class="rounded-xl object-cover h-48 w-full hover:opacity-90 transition-opacity"
                />
            </Link>
        </figure>

        <div class="card-body p-5">
            <!-- Titel und Badge in einer Zeile (flex) -->
            <div class="flex justify-between items-start gap-2 mb-2">
                <h2 class="card-title text-lg font-bold leading-tight m-0">
                    {{ product.name }}
                </h2>
                <span v-if="product.category?.name" class="badge badge-primary badge-sm whitespace-nowrap">
                    {{ product.category.name }}
                </span>
            </div>

            <!-- Preis und Bestand optisch getrennt -->
            <div class="flex flex-col mt-auto pt-4 gap-1">
                <span class="text-2xl font-black text-base-content">
                    {{ Number(product.price).toFixed(2).replace('.', ',') }} €
                </span>

                <!-- Bestand farblich codieren (Grün = Vorhanden, Rot = Ausverkauft) -->
                <span :class="['text-sm font-medium', product.stock > 0 ? 'text-success' : 'text-error']">
                    {{ product.stock > 0 ? `Auf Lager (${product.stock})` : 'Ausverkauft' }}
                </span>
            </div>

            <!-- Button nimmt jetzt die volle Breite ein oder ordnet sich elegant an -->
            <div class="card-actions justify-end mt-4">
                <AddToCartButton
                    :product-id="product.id"
                    :stock="product.stock"
                    custom-class="btn-primary btn-sm"
                />
            </div>
        </div>
    </div>
</template>

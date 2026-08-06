<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
    productId: {
        type: [Number, String],
        required: true,
    },
    stock: {
        type: Number,
        default: 0,
    },
    quantity: {
        type: Number,
        default: 1,
    },
    // Erlaubt das Anpassen von CSS-Klassen (z. B. 'btn-sm' für Katalog vs. 'btn-lg' für Detailseite)
    customClass: {
        type: String,
        default: 'btn-primary btn-sm',
    },
});

const isAdding = ref(false);

function addToCart() {
    if (props.stock <= 0 || isAdding.value) return;

    isAdding.value = true;

    router.post(
        route('cart.store'),
        {
            product_id: props.productId,
            quantity: props.quantity,
        },
        {
            preserveScroll: true, // Verhindert das Hochscrollen der Seite beim Klicken
            onFinish: () => {
                isAdding.value = false;
            },
        }
    );
}
</script>

<template>
    <button
        @click.stop.prevent="addToCart"
        class="btn"
        :class="[customClass, { 'btn-disabled': stock <= 0 }]"
        :disabled="stock <= 0 || isAdding"
    >
        <span v-if="isAdding" class="loading loading-spinner loading-xs"></span>
        <template v-else>
            <span v-if="stock <= 0">Ausverkauft</span>
            <slot v-else>In den Warenkorb</slot>
        </template>
    </button>
</template>

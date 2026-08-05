<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    product: {
        type: Object,
        required: true
    }
})
</script>

<template>
    <div class="card card-border bg-base-100 w-96">
        <div class="card-body">
            <h2 class="card-title"><strong> {{ product.name }} </strong></h2>

            <span class="badge badge-outline">
                {{ product.category?.name }}
            </span>

            <p>
                Preis {{ product.price }} € <br>
                Noch verfügbar: {{ product.stock }}
            </p>
            <div class="card-actions justify-end">
                <button class="btn btn-primary">In den Warenkorb</button>
            </div>
        </div>
    </div>
</template>

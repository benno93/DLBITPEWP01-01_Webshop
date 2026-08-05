<script setup>
import { Link } from '@inertiajs/vue3';
defineProps({
    name: {type: String, required: true},
    price: {type: Number, required: true},
    stock: {type: Number, required: true},
    category: {type: String, required: true},
})
</script>

<template>
    <div class="card card-border bg-base-100 w-96">
        <div class="card-body">
            <h2 class="card-title">{{ name }}</h2>
            <p>
                Preis: {{ price }} <br>
                Noch verfügbar: {{ stock }}
            </p>
            <div class="card-actions justify-end">
                <button class="btn btn-primary">In den Warenkorb</button>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>

<script setup>
import { Head, Link } from '@inertiajs/vue3';
import ShopLayout from '@/Layouts/ShopLayout.vue';

const props = defineProps({
    order: {
        type: Object,
        required: true,
    },
});

function formatPrice(value) {
    return Number(value).toFixed(2) + ' €';
}
</script>

<template>
    <Head title="Bestellung bestätigt" />

    <ShopLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Bestellung bestätigt
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="bg-white p-8 rounded-lg shadow text-center space-y-2">
                    <div class="text-5xl">✓</div>
                    <h1 class="text-2xl font-bold">Vielen Dank für deine Bestellung!</h1>
                    <p class="text-gray-600">
                        Deine Bestellung <span class="font-semibold">#{{ order.id }}</span> wurde erfolgreich aufgenommen.
                    </p>
                </div>

                <div class="bg-white rounded-lg shadow p-6 space-y-4">
                    <h3 class="font-bold text-lg border-b pb-2">Bestellte Artikel</h3>

                    <div v-for="item in order.items" :key="item.id" class="flex justify-between text-sm">
                        <span>{{ item.quantity }} × {{ item.product?.name ?? 'Produkt' }}</span>
                        <span class="font-medium">{{ formatPrice(item.quantity * item.unit_price) }}</span>
                    </div>

                    <div class="flex justify-between text-lg font-bold border-t pt-4">
                        <span>Gesamtsumme</span>
                        <span class="text-primary">{{ formatPrice(order.total_price) }}</span>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6 space-y-1 text-sm text-gray-600">
                    <p>Lieferadresse: {{ order.shipping_address?.street }} {{ order.shipping_address?.house_number }}, {{ order.shipping_address?.zip_code }} {{ order.shipping_address?.city }}</p>
                    <p>Zahlungsart: {{ order.payment?.method }}</p>
                    <p>Status: {{ order.status }}</p>
                </div>

                <div class="text-center">
                    <Link :href="route('Catalog')" class="btn btn-primary">
                        Weiter einkaufen
                    </Link>
                </div>
            </div>
        </div>
    </ShopLayout>
</template>

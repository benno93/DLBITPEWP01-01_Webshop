<script setup>
import { computed } from 'vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import ShopLayout from '@/Layouts/ShopLayout.vue';
import InputError from '@/Components/InputError.vue';

const page = usePage();

// Warenkorb aus den global geteilten Inertia-Props lesen (siehe HandleInertiaRequests)
const normalizedCart = computed(() => {
    const rawCart = page.props.cart || {};
    return Array.isArray(rawCart) ? rawCart : Object.values(rawCart);
});

const cartIsEmpty = computed(() => normalizedCart.value.length === 0);

const shippingMethods = [
    { value: 'standard', label: 'Standardversand', description: 'Lieferung in 3-5 Werktagen', price: 4.99 },
    { value: 'express', label: 'Expressversand', description: 'Lieferung in 1-2 Werktagen', price: 9.99 },
    { value: 'pickup', label: 'Abholung im Markt', description: 'Ab morgen abholbereit', price: 0 },
];

const paymentMethods = [
    { value: 'paypal', label: 'PayPal', description: 'Bezahlung über dein PayPal-Konto' },
    { value: 'credit_card', label: 'Kreditkarte', description: 'Visa, Mastercard, American Express' },
    { value: 'invoice', label: 'Rechnungskauf', description: 'Zahlbar innerhalb von 14 Tagen' },
    { value: 'cash_on_delivery', label: 'Nachnahme', description: 'Bezahlung bei Lieferung' },
];

const countries = ['Deutschland', 'Österreich', 'Schweiz'];

const form = useForm({
    // Kundendaten
    first_name: '',
    last_name: '',
    email: page.props.auth?.user?.email ?? '',
    phone: '',

    // Lieferadresse
    shipping_street: '',
    shipping_house_number: '',
    shipping_zip_code: '',
    shipping_city: '',
    shipping_country: 'Deutschland',

    // Rechnungsadresse
    billing_same_as_shipping: true,
    billing_street: '',
    billing_house_number: '',
    billing_zip_code: '',
    billing_city: '',
    billing_country: 'Deutschland',

    // Versand & Zahlung
    shipping_method: 'standard',
    payment_method: 'paypal',

    // Rechtliches
    accept_terms: false,
    accept_withdrawal: false,
});

// Preisberechnung
const subtotal = computed(() => {
    return normalizedCart.value.reduce((sum, item) => sum + item.price * item.quantity, 0);
});

const shippingCost = computed(() => {
    return shippingMethods.find((method) => method.value === form.shipping_method)?.price ?? 0;
});

const total = computed(() => subtotal.value + shippingCost.value);

function formatPrice(value) {
    return value.toFixed(2) + ' €';
}

function submit() {
    if (cartIsEmpty.value) {
        return;
    }

    form.post(route('checkout.store'));
}
</script>

<template>
    <Head title="Kasse" />

    <ShopLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Kasse
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

                <Link :href="route('cart.index')" class="btn btn-ghost btn-sm mb-6">
                    &larr; Zurück zum Warenkorb
                </Link>

                <!-- Leerer Warenkorb -->
                <div v-if="cartIsEmpty" class="bg-white p-8 rounded-lg shadow text-center space-y-4">
                    <p class="text-gray-500 text-lg">Dein Warenkorb ist zurzeit leer, es gibt nichts zu bestellen.</p>
                    <Link :href="route('Catalog')" class="btn btn-primary">
                        Zum Katalog
                    </Link>
                </div>

                <form v-else @submit.prevent="submit" class="space-y-8">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-start">

                        <!-- Linke Spalte -->
                        <div class="space-y-6">

                            <!-- 1. Kundendaten -->
                            <section class="bg-white rounded-lg shadow p-6 space-y-4">
                                <h3 class="font-bold text-lg border-b pb-2 flex items-center gap-2">
                                    <span class="badge badge-primary">1</span> Kundendaten
                                </h3>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="label"><span class="label-text">Vorname</span></label>
                                        <input v-model="form.first_name" type="text" required class="input input-bordered w-full" />
                                        <InputError class="mt-1" :message="form.errors.first_name" />
                                    </div>
                                    <div>
                                        <label class="label"><span class="label-text">Nachname</span></label>
                                        <input v-model="form.last_name" type="text" required class="input input-bordered w-full" />
                                        <InputError class="mt-1" :message="form.errors.last_name" />
                                    </div>
                                    <div>
                                        <label class="label"><span class="label-text">E-Mail</span></label>
                                        <input v-model="form.email" type="email" required class="input input-bordered w-full" />
                                        <InputError class="mt-1" :message="form.errors.email" />
                                    </div>
                                    <div>
                                        <label class="label"><span class="label-text">Telefon <span class="text-gray-400">(optional)</span></span></label>
                                        <input v-model="form.phone" type="tel" class="input input-bordered w-full" />
                                        <InputError class="mt-1" :message="form.errors.phone" />
                                    </div>
                                </div>
                            </section>

                            <!-- 2. Lieferadresse & Rechnungsadresse -->
                            <section class="bg-white rounded-lg shadow p-6 space-y-4">
                                <h3 class="font-bold text-lg border-b pb-2 flex items-center gap-2">
                                    <span class="badge badge-primary">2</span> Lieferadresse
                                </h3>

                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                    <div class="sm:col-span-2">
                                        <label class="label"><span class="label-text">Straße</span></label>
                                        <input v-model="form.shipping_street" type="text" required class="input input-bordered w-full" />
                                        <InputError class="mt-1" :message="form.errors.shipping_street" />
                                    </div>
                                    <div>
                                        <label class="label"><span class="label-text">Hausnummer</span></label>
                                        <input v-model="form.shipping_house_number" type="text" required class="input input-bordered w-full" />
                                        <InputError class="mt-1" :message="form.errors.shipping_house_number" />
                                    </div>
                                    <div>
                                        <label class="label"><span class="label-text">PLZ</span></label>
                                        <input v-model="form.shipping_zip_code" type="text" required class="input input-bordered w-full" />
                                        <InputError class="mt-1" :message="form.errors.shipping_zip_code" />
                                    </div>
                                    <div class="sm:col-span-2">
                                        <label class="label"><span class="label-text">Stadt</span></label>
                                        <input v-model="form.shipping_city" type="text" required class="input input-bordered w-full" />
                                        <InputError class="mt-1" :message="form.errors.shipping_city" />
                                    </div>
                                    <div class="sm:col-span-3">
                                        <label class="label"><span class="label-text">Land</span></label>
                                        <select v-model="form.shipping_country" required class="select select-bordered w-full">
                                            <option v-for="country in countries" :key="country" :value="country">{{ country }}</option>
                                        </select>
                                        <InputError class="mt-1" :message="form.errors.shipping_country" />
                                    </div>
                                </div>

                                <label class="flex items-center gap-3 pt-2 cursor-pointer">
                                    <input v-model="form.billing_same_as_shipping" type="checkbox" class="checkbox checkbox-primary" />
                                    <span class="label-text">Rechnungsadresse entspricht der Lieferadresse</span>
                                </label>

                                <div v-if="!form.billing_same_as_shipping" class="pt-4 mt-4 border-t space-y-4">
                                    <h4 class="font-semibold text-gray-700">Rechnungsadresse</h4>

                                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                        <div class="sm:col-span-2">
                                            <label class="label"><span class="label-text">Straße</span></label>
                                            <input v-model="form.billing_street" type="text" required class="input input-bordered w-full" />
                                            <InputError class="mt-1" :message="form.errors.billing_street" />
                                        </div>
                                        <div>
                                            <label class="label"><span class="label-text">Hausnummer</span></label>
                                            <input v-model="form.billing_house_number" type="text" required class="input input-bordered w-full" />
                                            <InputError class="mt-1" :message="form.errors.billing_house_number" />
                                        </div>
                                        <div>
                                            <label class="label"><span class="label-text">PLZ</span></label>
                                            <input v-model="form.billing_zip_code" type="text" required class="input input-bordered w-full" />
                                            <InputError class="mt-1" :message="form.errors.billing_zip_code" />
                                        </div>
                                        <div class="sm:col-span-2">
                                            <label class="label"><span class="label-text">Stadt</span></label>
                                            <input v-model="form.billing_city" type="text" required class="input input-bordered w-full" />
                                            <InputError class="mt-1" :message="form.errors.billing_city" />
                                        </div>
                                        <div class="sm:col-span-3">
                                            <label class="label"><span class="label-text">Land</span></label>
                                            <select v-model="form.billing_country" required class="select select-bordered w-full">
                                                <option v-for="country in countries" :key="country" :value="country">{{ country }}</option>
                                            </select>
                                            <InputError class="mt-1" :message="form.errors.billing_country" />
                                        </div>
                                    </div>
                                </div>
                            </section>

                            <!-- 3. Versandart -->
                            <section class="bg-white rounded-lg shadow p-6 space-y-4">
                                <h3 class="font-bold text-lg border-b pb-2 flex items-center gap-2">
                                    <span class="badge badge-primary">3</span> Versandart
                                </h3>

                                <label
                                    v-for="method in shippingMethods"
                                    :key="method.value"
                                    class="flex items-start gap-3 border rounded-lg p-4 cursor-pointer transition-colors"
                                    :class="form.shipping_method === method.value ? 'border-primary bg-primary/5' : 'border-gray-200'"
                                >
                                    <input v-model="form.shipping_method" type="radio" :value="method.value" required class="radio radio-primary mt-1" />
                                    <span class="flex-1">
                                        <span class="block font-semibold text-gray-800">{{ method.label }}</span>
                                        <span class="block text-sm text-gray-500">{{ method.description }}</span>
                                    </span>
                                    <span class="font-semibold text-gray-800">
                                        {{ method.price > 0 ? formatPrice(method.price) : 'kostenlos' }}
                                    </span>
                                </label>
                                <InputError :message="form.errors.shipping_method" />
                            </section>

                            <!-- 4. Zahlungsart -->
                            <section class="bg-white rounded-lg shadow p-6 space-y-4">
                                <h3 class="font-bold text-lg border-b pb-2 flex items-center gap-2">
                                    <span class="badge badge-primary">4</span> Zahlungsart
                                </h3>

                                <label
                                    v-for="method in paymentMethods"
                                    :key="method.value"
                                    class="flex items-start gap-3 border rounded-lg p-4 cursor-pointer transition-colors"
                                    :class="form.payment_method === method.value ? 'border-primary bg-primary/5' : 'border-gray-200'"
                                >
                                    <input v-model="form.payment_method" type="radio" :value="method.value" required class="radio radio-primary mt-1" />
                                    <span class="flex-1">
                                        <span class="block font-semibold text-gray-800">{{ method.label }}</span>
                                        <span class="block text-sm text-gray-500">{{ method.description }}</span>
                                    </span>
                                </label>
                                <InputError :message="form.errors.payment_method" />
                            </section>
                        </div>

                        <!-- Rechte Spalte: Bestellübersicht -->
                        <div class="lg:sticky lg:top-24">
                            <div class="bg-white rounded-lg shadow p-6 space-y-4">
                                <h3 class="font-bold text-lg border-b pb-2">Bestellübersicht</h3>

                                <div class="space-y-3 max-h-80 overflow-y-auto pr-1">
                                    <div
                                        v-for="item in normalizedCart"
                                        :key="item.id"
                                        class="flex items-center gap-3"
                                    >
                                        <img
                                            :src="item.image_url ?? '/images/placeholder.png'"
                                            :alt="item.name"
                                            class="w-12 h-12 object-cover rounded shrink-0"
                                            @error="$event.target.src = '/images/placeholder.png'"
                                        />
                                        <div class="flex-1 min-w-0">
                                            <p class="font-medium text-gray-800 truncate">{{ item.name }}</p>
                                            <p class="text-sm text-gray-500">{{ item.quantity }} × {{ formatPrice(item.price) }}</p>
                                        </div>
                                        <span class="font-semibold text-gray-800 shrink-0">
                                            {{ formatPrice(item.price * item.quantity) }}
                                        </span>
                                    </div>
                                </div>

                                <div class="border-t pt-4 space-y-2">
                                    <div class="flex justify-between text-sm">
                                        <span>Zwischensumme</span>
                                        <span class="font-medium">{{ formatPrice(subtotal) }}</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span>Versandkosten</span>
                                        <span class="font-medium">{{ shippingCost > 0 ? formatPrice(shippingCost) : 'kostenlos' }}</span>
                                    </div>
                                    <div class="flex justify-between text-lg font-bold border-t pt-2">
                                        <span>Gesamtsumme</span>
                                        <span class="text-primary">{{ formatPrice(total) }}</span>
                                    </div>
                                    <p class="text-xs text-gray-400">inkl. MwSt., zzgl. eventueller Zölle</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Call-to-Action: Rechtliches & Bestellbutton -->
                    <section class="bg-white rounded-lg shadow-lg border-2 border-primary p-6 md:p-8 space-y-6">
                        <div v-if="form.errors.stock" class="alert alert-error text-sm">
                            {{ form.errors.stock }}
                        </div>

                        <div class="space-y-3">
                            <label class="flex items-start gap-3 cursor-pointer">
                                <input v-model="form.accept_terms" type="checkbox" required class="checkbox checkbox-primary mt-0.5" />
                                <span class="label-text">
                                    Ich habe die <a href="#" class="link link-primary">AGB</a> gelesen und akzeptiere sie.
                                </span>
                            </label>
                            <InputError :message="form.errors.accept_terms" />

                            <label class="flex items-start gap-3 cursor-pointer">
                                <input v-model="form.accept_withdrawal" type="checkbox" required class="checkbox checkbox-primary mt-0.5" />
                                <span class="label-text">
                                    Ich habe die <a href="#" class="link link-primary">Widerrufsbelehrung</a> zur Kenntnis genommen.
                                </span>
                            </label>
                            <InputError :message="form.errors.accept_withdrawal" />
                        </div>

                        <button
                            type="submit"
                            class="btn btn-primary btn-lg w-full"
                            :disabled="form.processing || !form.accept_terms || !form.accept_withdrawal"
                        >
                            <span v-if="form.processing" class="loading loading-spinner"></span>
                            Zahlungspflichtig bestellen &middot; {{ formatPrice(total) }}
                        </button>
                    </section>
                </form>
            </div>
        </div>
    </ShopLayout>
</template>

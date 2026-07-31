<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    title: { type: String, required: true },
    description: { type: String, required: true },
    imageSrc: { type: String, required: true },
    imageAlt: { type: String, default: 'Produktbild' },
    buttonText: { type: String, default: 'Ansehen' },
    buttonHref: { type: String, required: true },
});
</script>

<template>
    <div class="card bg-base-100 border border-gray-100 shadow-md hover:shadow-xl transition-all duration-300 flex flex-col justify-between">
        <!-- Bildbereich -->
        <figure class="px-6 pt-6 h-52 bg-gray-50 flex items-center justify-center">
            <img
                :src="imageSrc"
                :alt="imageAlt"
                class="max-h-full max-w-full object-contain hover:scale-105 transition-transform duration-300"
            />
        </figure>

        <!-- Content & Footer -->
        <div class="card-body items-center text-center p-6 flex flex-col justify-between flex-1">
            <div>
                <h2 class="card-title text-xl font-bold text-gray-800 justify-center mb-2">
                    {{ title }}
                </h2>
                <p class="text-sm text-gray-500">
                    {{ description }}
                </p>
            </div>

            <div class="card-actions mt-6 w-full">
                <Link
                    :href="buttonHref"
                    class="btn btn-primary btn-block text-white"
                >
                    {{ buttonText }}
                </Link>
            </div>
        </div>
    </div>
</template>

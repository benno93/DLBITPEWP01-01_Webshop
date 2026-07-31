<script setup>
defineProps({
    title: { type: String, required: true },
    subtitle: { type: String, required: true },
    buttonText: { type: String, default: 'Mehr erfahren' },
    buttonHref: { type: String, default: '#' },
    bgImage: { type: String, default: '' },
});
</script>

<template>
    <div
        class="hero min-h-[70vh] relative bg-cover bg-center bg-gray-900 text-white"
        :style="bgImage ? { backgroundImage: `url(${bgImage})` } : {}"
    >
        <!-- Dunkles Overlay für bessere Lesbarkeit -->
        <div class="hero-overlay bg-opacity-60 bg-black"></div>

        <div class="hero-content text-center text-neutral-content z-10 py-16">
            <div class="max-w-2xl">
                <h1 class="mb-5 text-4xl md:text-6xl font-extrabold tracking-tight">
                    {{ title }}
                </h1>
                <p class="mb-8 text-lg md:text-xl font-light">
                    {{ subtitle }}
                </p>
                <a
                    :href="buttonHref"
                    class="btn btn-primary px-8 text-white text-base shadow-lg"
                >
                    {{ buttonText }}
                </a>
            </div>
        </div>
    </div>
</template>

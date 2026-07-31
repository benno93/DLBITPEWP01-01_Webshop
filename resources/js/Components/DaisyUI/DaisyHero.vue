<script setup>
defineProps({
    title: {
        type: String,
        required: true,
    },
    text: {
        type: String,
        required: true,
    },
    backgroundImage: {
        type: String,
        default: 'https://img.daisyui.com/images/stock/photo-1507358522600-9f71e620c44e.webp',
    },
    buttonText: {
        type: String,
        required: true,
        default: 'Hallo, Welt!',
    },
    buttonTo: {
        type: String,
        default: '/',
    }
})
</script>

<template>
    <div
        class="hero min-h-screen"
        :style="{ backgroundImage: `url(${backgroundImage})` }"
    >
        <div class="hero-overlay"></div>
        <div class="hero-content text-neutral-content text-center">
            <div class="max-w-md">
                <h1 class="mb-5 text-5xl font-bold">{{ title }}</h1>
                <p class="mb-5">
                    {{ text }}
                </p>
                <RouterLink :to="buttonTo" class="btn btn-primary">
                    {{ buttonText }}
                </RouterLink>
            </div>
        </div>
    </div>
</template>

<style scoped>
p,h1 {
    -webkit-text-stroke: 1px rgba(0, 0, 0, 0.1);
    paint-order: stroke fill;
}

.hero-overlay {
    /* Erzeugt einen Verlauf von Schwarz (unten/links) nach Transparent */
    background: linear-gradient(
        to right,
        rgba(0, 0, 0, 0.7) 0%,
        rgba(0, 0, 0, 0) 100%
    ),
    url('dein-bild.jpg');
    background-size: cover;
}
</style>

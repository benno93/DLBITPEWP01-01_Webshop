<script setup>
import Navbar from '@/Components/Layout/Navbar.vue';
import Footer from '@/Components/Layout/Footer.vue';
</script>

<template>
    <div class="min-h-screen flex flex-col">
        <!-- Fixierter Header Container -->
        <header class="sticky top-0 z-50 w-full">
            <Navbar />
        </header>

        <main class="flex-1 flex items-center justify-center">
            <div class="w-full max-w-7xl mx-auto px-4 py-8 flex flex-col items-center text-center">
                <slot />
            </div>
        </main>

        <Footer />
    </div>
</template>

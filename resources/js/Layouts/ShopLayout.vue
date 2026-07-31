<script setup>
import Navbar from '@/Components/Layout/Navbar.vue';
import Footer from '@/Components/Layout/Footer.vue';
</script>

<template>
    <div class="min-h-screen flex flex-col bg-gray-50 text-gray-900 antialiased">
        <!-- Top Navigation -->
        <Navbar />

        <!-- Main Content Area: Flex-1 sorgt dafür, dass der Footer immer unten klebt -->
        <main class="flex-1 w-full">
            <slot />
        </main>

        <!-- Footer -->
        <Footer />
    </div>
</template>

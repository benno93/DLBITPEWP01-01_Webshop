<script setup>
import ShopLayout from "@/Layouts/ShopLayout.vue";
import { Head } from '@inertiajs/vue3'
import DaisyHero from "@/Components/DaisyUI/DaisyHero.vue";
</script>

<template>
    <ShopLayout>
        <div class="flex flex-col gap-12 px-6 py-12 max-w-5xl mx-auto">
            <DaisyHero
                title="Willkommen im Shop"
                text="Entdecken sie profesionell aufbereitete Laptops aller namenhaften Firmen."
                buttonText="Zu den Daten"
                buttonTo="/Pages/Products/Index"
            />
        </div>
    </ShopLayout>
</template>

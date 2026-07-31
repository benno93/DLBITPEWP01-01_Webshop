<script setup>
import ShopLayout from "@/Layouts/ShopLayout.vue";
import { Head } from '@inertiajs/vue3'
import DaisyHero from "@/Components/DaisyUI/DaisyHero.vue";
import DaisyCard from "@/Components/DaisyUI/DaisyCard.vue";
</script>

<template>
    <ShopLayout>

            <DaisyHero class="min-h-[25vh]"
                title="Willkommen im Shop"
                text="Entdecken sie profesionell aufbereitete Laptops aller namenhaften Hersteller."
                buttonText="Zu den Produkten"
                buttonTo="/Pages/Products/Index"
                background-image="images/hero-bg.png"
            />

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-stretch">
                <div class="flex-1">
                    <DaisyCard
                        title="Entdecke unsere MacBooks"
                        text=""
                        btnText="Weiter"
                        imgurl="https://www.selfdirection.org/wp-content/uploads/2023/11/macbook-air-m1.jpeg"
                    />
                </div>

                <div class="flex-1">
                    <DaisyCard
                        title="Entdecke unsere ThinkPads"
                        text=""
                        btnText="Weiter"
                        imgurl="https://static1.xdaimages.com/wordpress/wp-content/uploads/2023/03/lenovo-thinkpad-t14s-gen-4-deep-black.png"
                    />
                </div>
            </div>


    </ShopLayout>
</template>

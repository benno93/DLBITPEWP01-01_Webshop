<script setup>
import ShopLayout from "@/Layouts/ShopLayout.vue";
import { Head } from '@inertiajs/vue3'
</script>

<template>
    <ShopLayout>
        <!-- Haupt-Container – Hero-Section -->
        <div class="w-full rounded-2xl shadow-lg overflow-hidden">

            <!-- Hero-Bereich (große Fläche oben) -->
            <div
                class="hero hero min-h-[500px] max-h-[90vh] h-[70vh]"
                style="background-image: url(https://img.daisyui.com/images/stock/photo-1507358522600-9f71e620c44e.webp);"
            >
                <div class="hero-overlay"></div>
                <div class="hero-content text-neutral-content text-center">
                    <div class="max-w-md">
                        <h1 class="mb-5 text-5xl font-bold">Hello there</h1>
                        <p class="mb-5">
                            Provident cupiditate voluptatem et in. Quaerat fugiat ut assumenda excepturi exercitationem
                            quasi. In deleniti eaque aut repudiandae et a id nisi.
                        </p>
                        <button class="btn btn-primary">Get Started</button>
                    </div>
                </div>
            </div>

            <!-- Beispielprodukt-Bereich -->
            <div class="px-6 py-8 md:px-12 border-b border-gray-200">
                <div class="card bg-base-100 w-96 shadow-sm">
                    <figure class="px-10 pt-10">
                        <img
                            src="https://img.daisyui.com/images/stock/photo-1606107557195-0e29a4b5b4aa.webp"
                            alt="Shoes"
                            class="rounded-xl" />
                    </figure>
                    <div class="card-body items-center text-center">
                        <h2 class="card-title">Card Title</h2>
                        <p>A card component has a figure, a body part, and inside body there are title and actions parts</p>
                        <div class="card-actions">
                            <button class="btn btn-primary">Buy Now</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Zwei Info-Cards (Nebeneinander) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6 md:p-10">
                <!-- Info-Card 1 -->
                <div class="rounded-xl p-6 shadow-sm border border-gray-200">
                    <div class="w-12 h-12 bg-blue-500 rounded-full mb-4"></div>
                    <h3 class="text-lg font-semibold text-gray-800">Info-Card 1</h3>
                    <p class="text-gray-600 text-sm mt-1">Hier steht ein informativer Text zur ersten Karte.</p>
                </div>

                <!-- Info-Card 2 -->
                <div class="rounded-xl p-6 shadow-sm border border-gray-200">
                    <div class="w-12 h-12 bg-green-500 rounded-full mb-4"></div>
                    <h3 class="text-lg font-semibold text-gray-800">Info-Card 2</h3>
                    <p class="text-gray-600 text-sm mt-1">Hier steht ein informativer Text zur zweiten Karte.</p>
                </div>
            </div>

        </div>
    </ShopLayout>
</template>

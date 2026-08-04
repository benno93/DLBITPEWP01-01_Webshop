<script setup>
import ShopLayout from '@/Layouts/ShopLayout.vue';
</script>

<template>
    <ShopLayout>
        <!-- Hauptcontainer mit Padding -->
        <div class="container mx-auto px-4 py-8 max-w-7xl">

            <!-- Flexbox-Layout für Sidebar und Hauptbereich -->
            <div class="flex flex-col md:flex-row gap-8">

                <!-- Linke Sidebar: Filter-Einstellungen -->
                <aside class="w-full md:w-1/3 lg:w-1/4 flex-shrink-0">
                    <div class="border-2 border-gray-800 rounded-3xl h-full min-h-[600px] flex items-center justify-center p-6 bg-white">
                        <span class="text-xl font-semibold text-gray-800">Filter-Einstellungen</span>
                    </div>
                </aside>

                <!-- Rechter Bereich: Produkte und Pagination -->
                <main class="w-full md:w-2/3 lg:w-3/4 flex flex-col gap-8">

                    <!-- Produkt-Grid (2 Spalten wie im Wireframe) -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-8 flex-grow">

                        <!-- Dummy-Produkte (6 Stück zur Veranschaulichung) -->
                        <div
                            v-for="i in 6"
                            :key="i"
                            class="border-2 border-gray-800 rounded-3xl aspect-square flex items-center justify-center bg-white"
                        >
                            <span class="text-2xl font-semibold text-gray-800">Produkt</span>
                        </div>

                    </div>

                    <!-- Unten: Navigation/Pagination -->
                    <div class="border-2 border-gray-800 rounded-xl py-4 flex items-center justify-center bg-white mt-auto w-3/4 mx-auto md:w-full">
                        <span class="text-lg font-semibold text-gray-800">Navigation/Pagination</span>
                    </div>

                </main>
            </div>

        </div>
    </ShopLayout>
</template>

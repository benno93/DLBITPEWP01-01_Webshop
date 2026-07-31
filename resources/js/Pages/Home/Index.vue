<script setup>
import ShopLayout from '@/Layouts/ShopLayout.vue';
import DaisyHero from '@/Components/DaisyUI/DaisyHero.vue';
import DaisyCard from '@/Components/DaisyUI/DaisyCard.vue';
</script>

<template>
    <ShopLayout>
        <!-- 1. HERO BANNER -->
        <DaisyHero
            title="Willkommen im Shop"
            subtitle="Entdecken Sie professionell aufbereitete Laptops aller namhaften Hersteller."
            buttonText="Zu den Produkten"
            buttonHref="#categories"
            bgImage="/images/hero-bg.png"
        />

        <!-- 2. KATEGORIEN / HIGHLIGHTS -->
        <section id="categories" class="container mx-auto px-6 py-16">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-800">Unsere Kategorien</h2>
                <p class="text-gray-500 mt-2">Finden Sie das passende Gerät für Ihren Anspruch</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">
                <!-- MacBook Card -->
                <DaisyCard
                    title="Entdecke unsere MacBooks"
                    description="Leistungsstarke Apple Laptops für kreative & professionelle Workflow-Anforderungen."
                    imageSrc="/storage/macbook.png"
                    imageAlt="MacBooks"
                    buttonText="Kategorie ansehen"
                    buttonHref="/produkte?category=macbook"
                />

                <!-- ThinkPad Card -->
                <DaisyCard
                    title="Entdecke unsere ThinkPads"
                    description="Zuverlässige Business-Klassiker von Lenovo für mobiles & robustes Arbeiten."
                    imageSrc="/storage/thinkpad.png"
                    imageAlt="ThinkPads"
                    buttonText="Kategorie ansehen"
                    buttonHref="/produkte?category=thinkpad"
                />

                <!-- HP Card -->
                <DaisyCard
                    title="Entdecke unsere HP-Laptops"
                    description="Zuverlässige Business-Klassiker von HP für mobiles & robustes Arbeiten."
                    imageSrc="/storage/thinkpad.png"
                    imageAlt="HP"
                    buttonText="Kategorie ansehen"
                    buttonHref="/produkte?category=hp"
                />
            </div>
        </section>
    </ShopLayout>
</template>

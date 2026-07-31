<script setup>
defineProps({
    title: {
        type: String,
        default: 'Titel',
    },
    text: {
        type: String,
        default: 'Lorem Ipsum',
    },
    imgurl: {
        type: String,
    },
    alt: {
        type: String,
        default: 'card',
    },
    btnText: {
        type: String,
        default: 'Button',
    },
    ButtonTo: {
        type: String,
        default: '/',
    }
})
</script>

<template>
    <div class="card bg-base-100 shadow-xl h-100 flex flex-col justify-between">
        <figure class="h-48 w-full shrink-0 overflow-hidden">
            <img
                :src="imgurl"
                :alt="alt"
                class="w-full h-full object-cover"
            />
        </figure>
        <div class="card-body flex flex-col justify-between flex-grow">
            <h2 class="card-title">{{ title }}</h2>
            <p>{{ text }}</p>
            <div class="card-actions justify-end">
                <router-link :to="ButtonTo"><button class="btn btn-primary">{{ btnText }}</button></router-link>
            </div>
        </div>

    </div>
</template>

<style scoped>

</style>

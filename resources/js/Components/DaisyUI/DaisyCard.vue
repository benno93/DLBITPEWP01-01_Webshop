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
    url: {
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
    <div class="card lg:card-side bg-base-100 shadow-sm">
        <figure>
            <img
                :src="url"
                :alt="alt" />
        </figure>
        <div class="card-body">
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

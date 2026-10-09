<script setup>
import { ref } from 'vue';

const message = ref('LOSIS V2 is ready!');
</script>

<template>
    <main>
        <h1>{{ message }}</h1>
        <p>Laravel + Vue 3</p>
    </main>
</template>

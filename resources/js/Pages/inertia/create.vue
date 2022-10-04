<script setup>
import { Inertia } from '@inertiajs/inertia';
import { reactive } from 'vue';

defineProps({
    errors: Object
})

const form = reactive({
    title: null,
    content: null
})

const submitZFunction = () => {
    Inertia.post('/inertia', form)
}
</script>

<template>
    <form @submit.prevent="submitZFunction()">
            <input type="text" name="title" v-model="form.title"><br>
            <span v-if="errors.title">{{ errors.title }}</span><br>
            <span v-if="errors.content">{{ errors.content }}</span><br>
            <input type="text" name="content" v-model="form.content">
            <button>送信</button>
    </form>
</template>
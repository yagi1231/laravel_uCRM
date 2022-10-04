<script setup lang="ts">
import labelFrom from '@/Components/Label.vue'
import Input from '@/Components/Input.vue'
import GuestLayout from '@/Layouts/GuestLayout.vue';

const emitTest = (e) => {
    console.log(e)
}

</script>

<template>

    <GuestLayout>
        <Input 
        modelValue="初期値"
        @update:modelValue="emitTest"
        ></Input>
        <labelFrom value="件名">タイトル</labelFrom>
    </GuestLayout>
</template>
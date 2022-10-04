<script setup lang="ts">
import { Link } from '@inertiajs/inertia-vue3';
import { ref } from 'vue';

const newTitle = ref<string>('')
const newContent = ref('')

</script>

<template>
inertiatestです<br>
<a href="/">aタグ経由</a><br>
<Link href="/">link経由</Link><br>
<Link :href="route('inertia.index')">名前付きルート</Link><br>
<Link :href="route('inertia.show', { id: 1 } )">ルートパラメーター</Link><br>

<div class="m-8">
    <input type="text" name="newTitle" v-model="newTitle">{{ newTitle }}<br>
    <input type="text" name="newContent" v-model="newContent"><br>
    <Link as="button" method="post" :href="route('inertia.store')" 
    :data="{
        title: newTitle,
        content: newContent
    }">
    DNB保存テスト</Link>

</div>
</template>

<style  scorp>

</style>
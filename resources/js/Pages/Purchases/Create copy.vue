<script setup>
import { getToday } from '@/common.js'
import { Inertia } from '@inertiajs/inertia';
import { computed } from '@vue/reactivity';
import { onMounted, reactive, ref } from 'vue';

onMounted(() => {
    form.date = getToday()

    props.items.forEach(item => {
        itemList.value.push({
            id: item.id, name: item.name, price: item.price, quantity: 0
        })
    })
})

const form = reactive({
    date: null,
    customer_id: null,
    status: true,
    items: []
})

const props = defineProps({
    customers: Array,
    items: Array
})

const itemList = ref([])

const quantity = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9']

const totalPrice = computed(() => {
    let total = 0
    itemList.value.forEach(item => {
        total += item.price * item.quantity
    })
    return total
})

const storePurcahse = () => {
    itemList.value.forEach(item => {
        if (item.quantity > 0) {
            form.items.push({
                id: item.id,
                quantity: item.quantity
            })
        }
    })
    Inertia.post(route('purchases.store'), form)
}

</script>

<template>
    <form @submit.prevent="storePurcahse()">
        日付<br>
        <input type="date" name="date" v-model="form.date"><br>
        <select name="customer" v-model="form.customer_id">
            <option v-for="customer in customers" :value="customer.id" :key="customer.id">
                {{ customer.id }} : {{ customer.name }}
            </option>
        </select>
        <table>
            <thead>
                <th>id</th>
                <th>名前</th>
                <th>金額</th>
                <th>数量</th>
                <th>合計金額</th>
            </thead>
            <tbody>
                <tr v-for="item in itemList">
                    <td>{{ item.id }}</td>
                    <td>{{ item.name }}</td>
                    <td>{{ item.price }}</td>
                    <td>
                        <select name="quantity" v-model="item.quantity">
                            <option v-for="q in quantity" :value="q">{{ q }}</option>
                        </select>
                    </td>
                    <td>
                        {{ item.price * item.quantity }}
                    </td>
                </tr>
            </tbody>
            合計: {{ totalPrice }}
        </table>
        <button>登録する</button>
    </form>
</template>
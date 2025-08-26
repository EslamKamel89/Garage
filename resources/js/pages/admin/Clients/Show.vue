<script setup lang="ts">
import Skeleton from '@/components/ui/skeleton/Skeleton.vue';
import { CarModel, Client } from '@/types/app';
import { onMounted, ref } from 'vue';
import Form from './Form.vue';

const client = ref<Client>();
const car_models = ref<CarModel[]>();
const isLoading = ref(false);
const props = defineProps<{ id: number }>();

onMounted(async () => {
    isLoading.value = true;
    try {
        const res = await fetch(route('clients.show', { id: props.id }));
        const json = await res.json();
        client.value = json.client;
        car_models.value = json.car_models;
    } catch (error) {
        console.error(error);
    } finally {
        isLoading.value = false;
    }
});
</script>

<template>
    <Form v-if="client" :action="'show'" :client="client" :car_models="car_models" />
    <div v-else class="flex flex-col space-y-2">
        <Skeleton class="h-5 w-[250px]" />
        <Skeleton class="h-5 w-full" />
    </div>
</template>

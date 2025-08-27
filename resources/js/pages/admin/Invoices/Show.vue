<script setup lang="ts">
import Skeleton from '@/components/ui/skeleton/Skeleton.vue';
import { Invoice } from '@/types/app';
import { onMounted, ref } from 'vue';
import Form from './Form.vue';

const invoice = ref<Invoice>();
const isLoading = ref(false);
const props = defineProps<{ id: number }>();

onMounted(async () => {
    isLoading.value = true;
    try {
        const res = await fetch(route('invoices.show', { id: props.id }));
        invoice.value = await res.json();
    } catch (error) {
        console.error(error);
    } finally {
        isLoading.value = false;
    }
});
</script>

<template>
    <Form v-if="invoice" :action="'show'" :invoice="invoice" />
    <div v-else class="flex flex-col space-y-2">
        <Skeleton class="h-5 w-[250px]" />
        <Skeleton class="h-5 w-full" />
    </div>
</template>

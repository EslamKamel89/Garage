<script setup lang="ts">
import Skeleton from '@/components/ui/skeleton/Skeleton.vue';
import { Labour } from '@/types/app';
import { onMounted, ref } from 'vue';
import Form from './Form.vue';

const labour = ref<Labour>();
const isLoading = ref(false);
const props = defineProps<{ id: number }>();

onMounted(async () => {
    isLoading.value = true;
    try {
        const res = await fetch(route('labours.show', { id: props.id }));
        labour.value = await res.json();
    } catch (error) {
        console.error(error);
    } finally {
        isLoading.value = false;
    }
});
</script>

<template>
    <Form v-if="labour" :action="'show'" :labour="labour" />
    <div v-else class="flex flex-col space-y-2">
        <Skeleton class="h-5 w-[250px]" />
        <Skeleton class="w-full h-5" />
    </div>
</template>

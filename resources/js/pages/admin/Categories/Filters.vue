<script setup lang="ts">
import { Button } from '@/components/ui/button';
import Input from '@/components/ui/input/Input.vue';
import { router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps<{
    filters: {
        search: string | null;
    };
}>();

const filters = ref({
    search: props.filters.search ?? '',
});

const applyFilters = () => {
    router.get(
        route('categories.index'),
        {
            'filter[search]': filters.value.search || undefined,
        },
        { preserveState: true, replace: true },
    );
};

const resetFilters = () => {
    filters.value = { search: '' };
    router.get(route('categories.index'), {}, { preserveState: true, replace: true });
};
</script>

<template>
    <div class="mb-4 space-y-4">
        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
            <div class="grid w-full items-center gap-1.5">
                <label for="filter-search" class="text-sm font-medium">البحث</label>
                <Input id="filter-search" v-model="filters.search" type="text" placeholder="اسم الفئة..." />
            </div>
            <div class="flex items-end space-x-2">
                <Button type="button" variant="default" size="sm" @click="applyFilters"> تطبيق </Button>
                <Button type="button" variant="secondary" size="sm" @click="resetFilters"> إلغاء </Button>
            </div>
        </div>
    </div>
</template>

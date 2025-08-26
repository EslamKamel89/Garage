<script setup lang="ts">
import { Button } from '@/components/ui/button';
import Input from '@/components/ui/input/Input.vue';
import { router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps<{
    filters: {
        search: string | null;
        category_id: string | null;
    };
    categories: {
        id: number;
        name: string;
    }[];
}>();

const filters = ref({
    search: props.filters.search ?? undefined,
    category_id: props.filters.category_id ?? undefined,
});
const applyFilters = () => {
    router.get(
        route('products.index'),
        {
            'filter[search]': filters.value.search || undefined,
            'filter[category_id]': filters.value.category_id || undefined,
        },
        { preserveState: true, replace: true },
    );
};

const resetFilters = () => {
    filters.value = { search: undefined, category_id: undefined };
    router.get(route('products.index'), {}, { preserveState: true, replace: true });
};
</script>

<template>
    <div class="mb-4 space-y-4">
        <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
            <!-- Search -->
            <div class="grid w-full items-center gap-1.5">
                <label for="filter-search" class="text-sm font-medium">البحث</label>
                <Input id="filter-search" v-model="filters.search" type="text" placeholder="الاسم أو الكود..." />
            </div>
            <!-- Category -->
            <div class="grid w-full items-center gap-1.5">
                <label for="filter-category" class="text-sm font-medium">الفئة</label>
                <select id="filter-category" v-model="filters.category_id" class="rounded-lg border border-gray-600 px-3 py-2">
                    <option value="">جميع الفئات</option>
                    <option v-for="category in categories" :key="category.id" :value="category.id">{{ category.name }}</option>
                </select>
            </div>
            <!-- Actions -->
            <div class="flex items-end space-x-2 md:col-span-2">
                <Button type="button" variant="default" size="sm" @click="applyFilters"> تطبيق </Button>
                <Button type="button" variant="secondary" size="sm" @click="resetFilters"> إلغاء </Button>
            </div>
        </div>
    </div>
</template>

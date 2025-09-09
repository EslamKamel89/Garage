<script setup lang="ts">
import { Button } from '@/components/ui/button';
import Input from '@/components/ui/input/Input.vue';
import { ref } from 'vue';

const props = defineProps<{
    filters: {
        search: string | null;
        is_active: string | null;
    };
}>();

const filters = ref({
    search: props.filters.search ?? '',
    is_active: props.filters.is_active ?? '',
});
</script>

<template>
    <div class="mb-4 space-y-4">
        <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
            <!-- Search -->
            <div class="grid w-full items-center gap-1.5">
                <label for="filter-search" class="text-sm font-medium">البحث</label>
                <Input id="filter-search" v-model="filters.search" type="text" placeholder="اسم الخدمة..." />
            </div>
            <!-- Status -->
            <div class="grid w-full items-center gap-1.5">
                <label for="filter-status" class="text-sm font-medium">الحالة</label>
                <select id="filter-status" v-model="filters.is_active" class="px-3 py-2 border border-gray-600 rounded-lg">
                    <option value="">جميع الحالات</option>
                    <option value="1">مفعل</option>
                    <option value="0">معطل</option>
                </select>
            </div>
            <!-- Actions -->
            <div class="flex items-end space-x-2 md:col-span-2">
                <Button type="button" variant="default" size="sm"> تطبيق </Button>
                <Button type="button" variant="secondary" size="sm"> إلغاء </Button>
            </div>
        </div>
    </div>
</template>

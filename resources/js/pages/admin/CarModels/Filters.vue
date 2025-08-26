<script setup lang="ts">
import { Button } from '@/components/ui/button';
import Input from '@/components/ui/input/Input.vue';
import { router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps<{
    filters: {
        year_from: string | null;
        year_to: string | null;
    };
}>();

const filters = ref({
    year_from: props.filters.year_from ?? '',
    year_to: props.filters.year_to ?? '',
});

const errors = ref({
    year_range: '',
});

const validate = (): boolean => {
    const from = filters.value.year_from ? parseInt(filters.value.year_from) : null;
    const to = filters.value.year_to ? parseInt(filters.value.year_to) : null;
    if (from !== null && to !== null && from > to) {
        errors.value.year_range = 'يجب أن تكون "من سنة" أصغر من أو تساوي "إلى سنة"';
        return false;
    }
    errors.value.year_range = '';
    return true;
};

const applyFilters = () => {
    if (!validate()) return;
    router.get(
        route('car-models.index'),
        {
            'filter[year_from]': filters.value.year_from || undefined,
            'filter[year_to]': filters.value.year_to || undefined,
        },
        { preserveState: true, replace: true },
    );
};

const resetFilters = () => {
    filters.value = { year_from: '', year_to: '' };
    errors.value.year_range = '';
    router.get(route('car-models.index'), {}, { preserveState: true, replace: true });
};
</script>

<template>
    <div class="mb-4 space-y-4">
        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
            <!-- Year From -->
            <div class="grid w-full items-center gap-1.5">
                <label for="filter-year-from" class="text-sm font-medium">من سنة</label>
                <Input id="filter-year-from" v-model="filters.year_from" type="number" />
            </div>
            <!-- Year To -->
            <div class="grid w-full items-center gap-1.5">
                <label for="filter-year-to" class="text-sm font-medium">إلى سنة</label>
                <Input id="filter-year-to" v-model="filters.year_to" type="number" />
            </div>
            <!-- Actions -->
            <div class="flex items-end space-x-2">
                <Button type="button" variant="default" size="sm" @click="applyFilters"> تطبيق </Button>
                <Button type="button" variant="secondary" size="sm" @click="resetFilters"> إلغاء </Button>
            </div>
        </div>
        <!-- Validation Error -->
        <div v-if="errors.year_range" class="mt-1 text-sm text-destructive">
            {{ errors.year_range }}
        </div>
    </div>
</template>

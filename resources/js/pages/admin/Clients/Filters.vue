<script setup lang="ts">
import { Button } from '@/components/ui/button';
import Input from '@/components/ui/input/Input.vue';
import { Select, SelectContent, SelectGroup, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { AppPageProps } from '@/types';
import { CarModel } from '@/types/app';
import { router, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps<{
    filters: {
        search: string | null;
    };
}>();
const page = usePage<AppPageProps & { car_models: CarModel[] }>();

const filters = ref({
    search: props.filters.search ?? '',
    car_model_id: undefined as number | undefined,
});

const applyFilters = () => {
    router.get(
        route('clients.index'),
        {
            'filter[search]': filters.value.search || undefined,
            'filter[car_model_id]': filters.value.car_model_id || undefined,
        },
        { preserveState: true, replace: true },
    );
};

const resetFilters = () => {
    filters.value = { search: '', car_model_id: undefined };
    router.get(route('clients.index'), {}, { preserveState: true, replace: true });
};
</script>

<template>
    <div class="mb-4 space-y-4">
        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
            <div class="grid w-full items-center gap-1.5">
                <label for="filter-search" class="text-sm font-medium">البحث</label>
                <Input id="filter-search" v-model="filters.search" type="text" placeholder="الاسم، الجوال، البريد..." />
            </div>
            <div class="grid w-full items-start gap-1.5">
                <Label>موديل السيارة</Label>
                <Select v-model="filters.car_model_id" class="w-full text-start">
                    <SelectTrigger class="w-full text-start">
                        <SelectValue class="text-start" placeholder="اختر موديل السيارة" />
                    </SelectTrigger>
                    <SelectContent>
                        <SelectGroup>
                            <SelectItem
                                class="flex w-full !items-start !text-right"
                                v-for="car_model in page.props.car_models"
                                :key="car_model.id"
                                :value="car_model.id"
                            >
                                <div class="w-full text-right">
                                    {{ car_model.name }}
                                </div>
                            </SelectItem>
                        </SelectGroup>
                    </SelectContent>
                </Select>
            </div>
            <div class="flex items-end space-x-2">
                <Button type="button" variant="default" size="sm" @click="applyFilters"> تطبيق </Button>
                <Button type="button" variant="secondary" size="sm" @click="resetFilters"> إلغاء </Button>
            </div>
        </div>
    </div>
</template>

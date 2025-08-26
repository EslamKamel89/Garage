<!-- Edit.vue -->
<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import { CarModel } from '@/types/app';
import { Head, usePage } from '@inertiajs/vue3';
import Form from './Form.vue';

defineProps<{
    car_model: CarModel;
}>();
const page = usePage();
const breadcrumbs: BreadcrumbItem[] = [
    { title: 'موديلات السيارات', href: '/car-models' },
    { title: 'تحديث', href: page.url },
];
</script>

<template>
    <Head title="تحديث موديل سيارة" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <Form action="edit" :car_model="car_model" />
    </AppLayout>
</template>

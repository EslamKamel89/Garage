<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import { Category } from '@/types/app';
import { Head } from '@inertiajs/vue3';
import Form from './Form.vue';
defineProps<{ categories: Category[] }>();
const breadcrumbs: BreadcrumbItem[] = [
    { title: 'المنتجات', href: '/products' },
    { title: 'إنشاء', href: '/products/create' },
];
</script>

<template>
    <Head title="إنشاء منتج" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <Form action="create" :categories="categories" />
    </AppLayout>
</template>

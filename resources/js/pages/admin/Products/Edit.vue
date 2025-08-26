<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import { Category, Product } from '@/types/app';
import { Head, usePage } from '@inertiajs/vue3';
import Form from './Form.vue';

defineProps<{ product: Product; categories: Category[] }>();
const page = usePage();
const breadcrumbs: BreadcrumbItem[] = [
    { title: 'المنتجات', href: '/products' },
    { title: 'تحديث', href: page.url },
];
</script>

<template>
    <Head title="تحديث منتج" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <Form action="edit" :product="product" :categories="categories" />
    </AppLayout>
</template>

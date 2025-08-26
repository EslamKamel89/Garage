<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import { Category } from '@/types/app';
import { Head, usePage } from '@inertiajs/vue3';
import Form from './Form.vue';

defineProps<{ category: Category }>();
const page = usePage();
const breadcrumbs: BreadcrumbItem[] = [
    { title: 'الفئات', href: '/categories' },
    { title: 'تحديث', href: page.url },
];
</script>

<template>
    <Head title="تحديث فئة" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <Form action="edit" :category="category" />
    </AppLayout>
</template>

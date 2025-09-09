<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import Form from './Form.vue';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'الخدمات', href: '/labours' },
    { title: 'إنشاء', href: '/labours/create' },
];
</script>

<template>
    <Head title="إنشاء خدمة" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <Form action="create" />
    </AppLayout>
</template>

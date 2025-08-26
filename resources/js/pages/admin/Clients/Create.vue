<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import Form from './Form.vue';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'العملاء', href: '/clients' },
    { title: 'إنشاء', href: '/clients/create' },
];
</script>

<template>
    <Head title="إنشاء عميل" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <Form action="create" />
    </AppLayout>
</template>

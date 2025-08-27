<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import Form from './Form.vue';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'الفواتير', href: '/invoices' },
    { title: 'إنشاء', href: '/invoices/create' },
];
</script>

<template>
    <Head title="إنشاء فاتورة" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <Form />
    </AppLayout>
</template>

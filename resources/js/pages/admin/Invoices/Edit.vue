<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import { Invoice } from '@/types/app';
import { Head, usePage } from '@inertiajs/vue3';
import Form from './Form.vue';

defineProps<{
    invoice: Invoice;
    clients: any[];
    products: any[];
    labours: any[];
}>();

const page = usePage();
const breadcrumbs: BreadcrumbItem[] = [
    { title: 'الفواتير', href: '/invoices' },
    { title: 'تحديث', href: page.url },
];
</script>

<template>
    <Head title="تحديث فاتورة" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <Form :invoice="invoice" :clients="clients" :products="products" :labours="labours" action="edit" />
    </AppLayout>
</template>

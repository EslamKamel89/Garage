<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import { Client } from '@/types/app';
import { Head, usePage } from '@inertiajs/vue3';
import Form from './Form.vue';

defineProps<{ client: Client }>();
const page = usePage();
const breadcrumbs: BreadcrumbItem[] = [
    { title: 'العملاء', href: '/clients' },
    { title: 'تحديث', href: page.url },
];
</script>

<template>
    <Head title="تحديث عميل" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <Form action="edit" :client="client" />
    </AppLayout>
</template>

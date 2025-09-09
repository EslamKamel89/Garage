<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import { Labour } from '@/types/app';
import { Head, usePage } from '@inertiajs/vue3';
import Form from './Form.vue';

defineProps<{ labour: Labour }>();
const page = usePage();
const breadcrumbs: BreadcrumbItem[] = [
    { title: 'الخدمات', href: '/labours' },
    { title: 'تحديث', href: page.url },
];
</script>

<template>
    <Head title="تحديث خدمة" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <Form action="edit" :labour="labour" />
    </AppLayout>
</template>

<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import { Invoice } from '@/types/app';
import { Head, Link } from '@inertiajs/vue3';
import { useBreakpoints } from '@vueuse/core';
import { Filter, Pen, Plus, Trash2 } from 'lucide-vue-next';
import Filters from './Filters.vue';

defineProps<{
    invoices: Invoice[];
}>();

const breakpoints = useBreakpoints({ mobile: 640 });
const isMobile = breakpoints.smaller('mobile');

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'لوحة التحكم', href: '/dashboard' },
    { title: 'الفواتير', href: '/invoices' },
];

const statusLabels = {
    unpaid: 'غير مدفوعة',
    paid: 'مدفوعة',
    partial: 'جزئي',
    cancelled: 'ملغاة',
};

const statusColors = {
    unpaid: 'text-yellow-600',
    paid: 'text-green-600',
    partial: 'text-blue-600',
    cancelled: 'text-red-600',
};
const deleteInvoice = (invoice: Invoice) => {};
</script>

<template>
    <Head title="جدول الفواتير" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <Card>
                <CardHeader>
                    <CardTitle class="flex items-center text-base">
                        <Filter class="ml-2 h-4 w-4" />
                        تصفية النتائج
                    </CardTitle>
                </CardHeader>
                <CardContent>
                    <Filters :filters="{ search: null, status: null }" />
                </CardContent>
            </Card>

            <div class="flex w-full justify-end">
                <Link :href="route('invoices.create')">
                    <Button type="button">
                        <Plus class="ml-1" />
                        <span>فاتورة جديدة</span>
                    </Button>
                </Link>
            </div>

            <!-- Desktop Table -->
            <Table class="hidden md:table">
                <TableHeader>
                    <TableRow>
                        <TableHead class="text-start"> رقم </TableHead>
                        <TableHead class="text-start"> العميل </TableHead>
                        <TableHead class="text-start"> الإجمالي </TableHead>
                        <TableHead class="text-start"> المدفوع </TableHead>
                        <TableHead class="text-start"> الحالة </TableHead>
                        <TableHead class="text-end"> الإجراءات </TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow v-for="invoice in invoices" :key="invoice.id">
                        <TableCell class="font-mono">#{{ invoice.id }}</TableCell>
                        <TableCell>{{ invoice.client?.name }}</TableCell>
                        <TableCell>{{ invoice.actual_total }} ج</TableCell>
                        <TableCell>{{ invoice.actual_paid_amount }} ج</TableCell>
                        <TableCell>
                            <span :class="statusColors[invoice.status]">{{ statusLabels[invoice.status] }}</span>
                        </TableCell>
                        <TableCell class="text-end">
                            <div class="flex items-center justify-end space-x-2">
                                <!--
                                <CustomDialog title="عرض الفاتورة" description="">
                                    <template #trigger>
                                        <Button variant="default" size="sm"><Eye /></Button>
                                    </template>
                                    <template #content>
                                        <Show :id="invoice.id" />
                                    </template>
                                </CustomDialog>
                                -->
                                <Link :href="route('invoices.edit', { invoice: invoice.id })">
                                    <Button variant="secondary" size="sm"><Pen /></Button>
                                </Link>
                                <Button @click="deleteInvoice(invoice)" variant="destructive" size="sm">
                                    <Trash2 />
                                </Button>
                            </div>
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>

            <!-- Mobile Cards -->
            <div class="space-y-2 md:hidden">
                <Card v-for="invoice in invoices" :key="invoice.id">
                    <div class="p-4">
                        <div class="flex justify-between">
                            <span class="font-mono">#{{ invoice.id }}</span>
                            <span :class="statusColors[invoice.status]">{{ statusLabels[invoice.status] }}</span>
                        </div>
                        <p class="font-medium">{{ invoice.client?.name }}</p>
                        <div class="mt-2 grid grid-cols-2">
                            <p class="text-sm">الإجمالي: {{ invoice.actual_total }} ج</p>
                            <p class="text-sm">المدفوع: {{ invoice.actual_paid_amount }} ج</p>
                        </div>
                        <div class="mt-3 flex justify-end">
                            <div class="flex items-center justify-end space-x-2">
                                <!--
                                <CustomDialog title="عرض الفاتورة" description="">
                                    <template #trigger>
                                        <Button variant="default" size="sm"><Eye /></Button>
                                    </template>
                                    <template #content>
                                        <Show :id="invoice.id" />
                                    </template>
                                </CustomDialog>
                                -->
                                <Link :href="route('invoices.edit', { invoice: invoice.id })">
                                    <Button variant="secondary" size="sm"><Pen /></Button>
                                </Link>
                                <Button @click="deleteInvoice(invoice)" variant="destructive" size="sm">
                                    <Trash2 />
                                </Button>
                            </div>
                        </div>
                    </div>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>

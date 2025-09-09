<script setup lang="ts">
import CustomDialog from '@/components/shared/CustomDialog.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Collapsible, CollapsibleContent, CollapsibleTrigger } from '@/components/ui/collapsible';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import { Labour } from '@/types/app';
import { Head, Link, router } from '@inertiajs/vue3';
import { useBreakpoints } from '@vueuse/core';
import { ChevronDown, Eye, Filter, Pen, Plus, Trash2 } from 'lucide-vue-next';
import Filters from './Filters.vue';
import Show from './Show.vue';

defineProps<{
    labours: Labour[];
}>();

const breakpoints = useBreakpoints({ mobile: 640 });
const isMobile = breakpoints.smaller('mobile');

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'لوحة التحكم', href: '/dashboard' },
    { title: 'الخدمات', href: '/labours' },
];

const deleteLabour = (labour: Labour) => {
    if (confirm('هل أنت متأكد أنك تريد حذف هذه الخدمة؟')) {
        router.delete(route('labours.destroy', { labour: labour.id }));
    }
};
</script>

<template>
    <Head title="جدول الخدمات" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col flex-1 h-full gap-4 p-4 rounded-xl">
            <Card>
                <CardHeader>
                    <CardTitle class="flex items-center text-base">
                        <Filter class="w-4 h-4 ml-2" />
                        تصفية النتائج
                    </CardTitle>
                </CardHeader>
                <CardContent>
                    <Filters :filters="{ search: null, is_active: null }" />
                </CardContent>
            </Card>

            <div class="flex justify-end w-full">
                <Link :href="route('labours.create')">
                    <Button type="button">
                        <Plus class="ml-1" />
                        <span>خدمة جديدة</span>
                    </Button>
                </Link>
            </div>

            <!-- Desktop Table -->
            <Table class="hidden md:table">
                <TableHeader>
                    <TableRow>
                        <TableHead class="text-start"> الاسم (عربي) </TableHead>
                        <TableHead class="text-start"> السعر </TableHead>
                        <TableHead class="text-start"> الحالة </TableHead>
                        <TableHead class="text-end"> الإجراءات </TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow v-for="labour in labours" :key="labour.id">
                        <TableCell class="font-medium">{{ labour.name_ar }}</TableCell>
                        <TableCell>{{ labour.base_fee }}ج.م</TableCell>
                        <TableCell>
                            <span :class="labour.is_active ? 'text-green-600' : 'text-red-600'">
                                {{ labour.is_active ? 'مفعل' : 'معطل' }}
                            </span>
                        </TableCell>
                        <TableCell class="text-end">
                            <div class="flex items-center justify-end space-x-2">
                                <CustomDialog title="عرض الخدمة" description="">
                                    <template #trigger>
                                        <Button variant="default" size="sm"><Eye /></Button>
                                    </template>
                                    <template #content>
                                        <Show :id="labour.id" />
                                    </template>
                                </CustomDialog>
                                <Link :href="route('labours.edit', { labour: labour.id })">
                                    <Button variant="secondary" size="sm"><Pen /></Button>
                                </Link>
                                <Button @click="deleteLabour(labour)" variant="destructive" size="sm">
                                    <Trash2 />
                                </Button>
                            </div>
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>

            <!-- Mobile Cards -->
            <div class="space-y-2 md:hidden">
                <Card v-for="labour in labours" :key="labour.id">
                    <Collapsible>
                        <CollapsibleTrigger as-child>
                            <CardHeader class="flex flex-row items-center justify-between p-4 cursor-pointer">
                                <div>
                                    <CardTitle class="text-base">{{ labour.name_ar }}</CardTitle>
                                </div>
                                <ChevronDown class="h-4 w-4 transition-transform duration-200 group-data-[state=open]:rotate-180" />
                            </CardHeader>
                        </CollapsibleTrigger>
                        <CollapsibleContent>
                            <CardContent class="pt-0 space-y-3">
                                <div class="grid grid-cols-2">
                                    <p class="text-sm font-medium text-muted-foreground">السعر</p>
                                    <p class="text-sm">{{ labour.base_fee }}ج.م</p>
                                </div>
                                <div class="grid grid-cols-2">
                                    <p class="text-sm font-medium text-muted-foreground">الحالة</p>
                                    <p class="text-sm" :class="labour.is_active ? 'text-green-600' : 'text-red-600'">
                                        {{ labour.is_active ? 'مفعل' : 'معطل' }}
                                    </p>
                                </div>
                                <div class="flex justify-end pt-2 space-x-2">
                                    <CustomDialog title="عرض الخدمة" description="">
                                        <template #trigger>
                                            <Button variant="default" size="sm"><Eye /></Button>
                                        </template>
                                        <template #content>
                                            <Show :id="labour.id" />
                                        </template>
                                    </CustomDialog>
                                    <Link :href="route('labours.edit', { labour: labour.id })">
                                        <Button variant="secondary" size="sm"><Pen /></Button>
                                    </Link>
                                    <Button @click="deleteLabour(labour)" variant="destructive" size="sm">
                                        <Trash2 />
                                    </Button>
                                </div>
                            </CardContent>
                        </CollapsibleContent>
                    </Collapsible>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>

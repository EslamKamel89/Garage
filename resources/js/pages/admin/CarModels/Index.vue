<script setup lang="ts">
import CustomDialog from '@/components/shared/CustomDialog.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Collapsible, CollapsibleContent, CollapsibleTrigger } from '@/components/ui/collapsible';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import { CarModel } from '@/types/app';
import { Head, Link, router } from '@inertiajs/vue3';
import { useBreakpoints } from '@vueuse/core';
import { ChevronDown, Eye, Pen, Plus, Trash2 } from 'lucide-vue-next';
import Show from './Show.vue';

defineProps<{
    car_models: CarModel[];
}>();

const breakpoints = useBreakpoints({ mobile: 640 });
const isMobile = breakpoints.smaller('mobile');

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'لوحة التحكم', href: '/dashboard' },
    { title: 'موديلات السيارات', href: '/car-models' },
];

const deleteCarModel = (carModel: CarModel) => {
    if (confirm('هل أنت متأكد أنك تريد حذف هذا الموديل؟')) {
        router.delete(route('car-models.destroy', { car_model: carModel.id }));
    }
};
</script>

<template>
    <Head title="جدول موديلات السيارات" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <!--
            <Card>
                <CardHeader>
                    <CardTitle class="flex items-center text-base">
                        <Filter class="w-4 h-4 ml-2" />
                        تصفية النتائج
                    </CardTitle>
                </CardHeader>
                <CardContent>
                    <Filters :filters="{ year_from: null, year_to: null }" />
                </CardContent>
            </Card>
            -->

            <div class="flex w-full justify-end">
                <Link :href="route('car-models.create')">
                    <Button type="button">
                        <Plus class="ml-1" />
                        <span>موديل جديد</span>
                    </Button>
                </Link>
            </div>

            <!-- Desktop Table -->
            <Table class="hidden md:table">
                <TableHeader>
                    <TableRow>
                        <TableHead class="text-start"> الاسم </TableHead>
                        <TableHead class="text-start"> نطاق السنوات </TableHead>
                        <TableHead class="text-start"> تاريخ الإنشاء </TableHead>
                        <TableHead class="text-end"> الإجراءات </TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow v-for="car_model in car_models" :key="car_model.id">
                        <TableCell class="font-medium">{{ car_model.name }}</TableCell>
                        <TableCell>{{ car_model.year_range_formatted }}</TableCell>
                        <TableCell>{{ car_model.created_at }}</TableCell>
                        <TableCell class="text-end">
                            <div class="flex items-center justify-end space-x-2">
                                <CustomDialog title="عرض الموديل" description="">
                                    <template #trigger>
                                        <Button variant="default" size="sm"><Eye /></Button>
                                    </template>
                                    <template #content>
                                        <Show :id="car_model.id" />
                                    </template>
                                </CustomDialog>
                                <Link :href="route('car-models.edit', { car_model: car_model.id })">
                                    <Button variant="secondary" size="sm"><Pen /></Button>
                                </Link>
                                <Button @click="deleteCarModel(car_model)" variant="destructive" size="sm">
                                    <Trash2 />
                                </Button>
                            </div>
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>

            <!-- Mobile Cards -->
            <div class="space-y-2 md:hidden">
                <Card v-for="car_model in car_models" :key="car_model.id">
                    <Collapsible>
                        <CollapsibleTrigger as-child>
                            <CardHeader class="flex cursor-pointer flex-row items-center justify-between p-4">
                                <div>
                                    <CardTitle class="text-base">{{ car_model.name }}</CardTitle>
                                    <p class="text-sm text-muted-foreground">{{ car_model.year_range_formatted }}</p>
                                </div>
                                <ChevronDown class="h-4 w-4 transition-transform duration-200 group-data-[state=open]:rotate-180" />
                            </CardHeader>
                        </CollapsibleTrigger>
                        <CollapsibleContent>
                            <CardContent class="space-y-3 pt-0">
                                <div>
                                    <p class="text-sm font-medium text-muted-foreground">نطاق السنوات</p>
                                    <p class="text-sm">{{ car_model.year_range_formatted }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-muted-foreground">تاريخ الإنشاء</p>
                                    <p class="text-sm">{{ car_model.created_at }}</p>
                                </div>
                                <div class="flex justify-end space-x-2 pt-2">
                                    <CustomDialog title="عرض الموديل" description="">
                                        <template #trigger>
                                            <Button variant="default" size="sm"><Eye /></Button>
                                        </template>
                                        <template #content>
                                            <Show :id="car_model.id" />
                                        </template>
                                    </CustomDialog>
                                    <Link :href="route('car-models.edit', { car_model: car_model.id })">
                                        <Button variant="secondary" size="sm"><Pen /></Button>
                                    </Link>
                                    <Button @click="deleteCarModel(car_model)" variant="destructive" size="sm">
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

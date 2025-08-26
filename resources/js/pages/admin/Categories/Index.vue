<script setup lang="ts">
import CustomDialog from '@/components/shared/CustomDialog.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Collapsible, CollapsibleContent, CollapsibleTrigger } from '@/components/ui/collapsible';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import { Category } from '@/types/app';
import { Head, Link, router } from '@inertiajs/vue3';
import { useBreakpoints } from '@vueuse/core';
import { ChevronDown, Eye, Filter, Pen, Plus, Trash2 } from 'lucide-vue-next';
import Filters from './Filters.vue';
import Show from './Show.vue';

defineProps<{
    categories: Category[];
}>();

const breakpoints = useBreakpoints({ mobile: 640 });
const isMobile = breakpoints.smaller('mobile');

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'لوحة التحكم', href: '/dashboard' },
    { title: 'الفئات', href: '/categories' },
];

const deleteCategory = (category: Category) => {
    if (confirm('هل أنت متأكد أنك تريد حذف هذه الفئة؟')) {
        router.delete(route('categories.destroy', { category: category.id }));
    }
};
</script>

<template>
    <Head title="جدول الفئات" />
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
                    <Filters :filters="{ search: null }" />
                </CardContent>
            </Card>

            <div class="flex w-full justify-end">
                <Link :href="route('categories.create')" v-if="true">
                    <Button type="button">
                        <Plus class="ml-1" />
                        <span>فئة جديدة</span>
                    </Button>
                </Link>
            </div>

            <!-- Desktop Table -->
            <Table class="hidden md:table">
                <TableHeader>
                    <TableRow>
                        <TableHead class="text-start"> الاسم </TableHead>
                        <TableHead class="text-start"> الوصف </TableHead>
                        <TableHead class="text-start"> تاريخ الإنشاء </TableHead>
                        <TableHead class="text-end"> الإجراءات </TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow v-for="category in categories" :key="category.id">
                        <TableCell class="font-medium">{{ category.name }}</TableCell>
                        <TableCell>{{ category.description }}</TableCell>
                        <TableCell>{{ category.created_at.split('T')[0] }}</TableCell>
                        <TableCell class="text-end">
                            <div class="flex items-center justify-end space-x-2">
                                <CustomDialog title="عرض الفئة" description="">
                                    <template #trigger>
                                        <Button variant="default" size="sm"><Eye /></Button>
                                    </template>
                                    <template #content>
                                        <Show :id="category.id" />
                                    </template>
                                </CustomDialog>
                                <Link :href="route('categories.edit', { category: category.id })" v-if="true">
                                    <Button variant="secondary" size="sm"><Pen /></Button>
                                </Link>
                                <Button @click="deleteCategory(category)" variant="destructive" size="sm" v-if="true">
                                    <Trash2 />
                                </Button>
                            </div>
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>

            <!-- Mobile Cards -->
            <div class="space-y-2 md:hidden">
                <Card v-for="category in categories" :key="category.id">
                    <Collapsible>
                        <CollapsibleTrigger as-child>
                            <CardHeader class="flex cursor-pointer flex-row items-center justify-between p-4">
                                <div>
                                    <CardTitle class="text-base">{{ category.name }}</CardTitle>
                                    <p class="text-sm text-muted-foreground">{{ category.description }}</p>
                                </div>
                                <ChevronDown class="h-4 w-4 transition-transform duration-200 group-data-[state=open]:rotate-180" />
                            </CardHeader>
                        </CollapsibleTrigger>
                        <CollapsibleContent>
                            <CardContent class="space-y-3 pt-0">
                                <div>
                                    <p class="text-sm font-medium text-muted-foreground">الوصف</p>
                                    <p class="text-sm">{{ category.description }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-muted-foreground">تاريخ الإنشاء</p>
                                    <p class="text-sm">{{ category.created_at.split('T')[0] }}</p>
                                </div>
                                <div class="flex justify-end space-x-2 pt-2">
                                    <CustomDialog title="عرض الفئة" description="">
                                        <template #trigger>
                                            <Button variant="default" size="sm"><Eye /></Button>
                                        </template>
                                        <template #content>
                                            <Show :id="category.id" />
                                        </template>
                                    </CustomDialog>
                                    <Link :href="route('categories.edit', { category: category.id })" v-if="true">
                                        <Button variant="secondary" size="sm"><Pen /></Button>
                                    </Link>
                                    <Button @click="deleteCategory(category)" variant="destructive" size="sm" v-if="true">
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

<script setup lang="ts">
import CustomDialog from '@/components/shared/CustomDialog.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Collapsible, CollapsibleContent, CollapsibleTrigger } from '@/components/ui/collapsible';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import { Client } from '@/types/app';
import { Head, Link, router } from '@inertiajs/vue3';
import { useBreakpoints } from '@vueuse/core';
import { ChevronDown, Eye, Filter, Pen, Plus, Trash2 } from 'lucide-vue-next';
import Filters from './Filters.vue';
import Show from './Show.vue';

defineProps<{
    clients: Client[];
}>();

const breakpoints = useBreakpoints({ mobile: 640 });
const isMobile = breakpoints.smaller('mobile');

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'لوحة التحكم', href: '/dashboard' },
    { title: 'العملاء', href: '/clients' },
];

const deleteClient = (client: Client) => {
    if (confirm('هل أنت متأكد أنك تريد حذف هذا العميل؟')) {
        router.delete(route('clients.destroy', { client: client.id }));
    }
};
</script>

<template>
    <Head title="جدول العملاء" />
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
                    <Filters :filters="{ search: null }" />
                </CardContent>
            </Card>

            <div class="flex justify-end w-full">
                <Link :href="route('clients.create')" v-if="true">
                    <Button type="button">
                        <Plus class="ml-1" />
                        <span>عميل جديد</span>
                    </Button>
                </Link>
            </div>

            <!-- Desktop Table -->
            <Table class="hidden md:table">
                <TableHeader>
                    <TableRow>
                        <TableHead class="text-start"> الاسم </TableHead>
                        <TableHead class="text-start"> الجوال </TableHead>
                        <TableHead class="text-start">موديل السيارة </TableHead>
                        <TableHead class="text-start"> البريد </TableHead>
                        <TableHead class="text-start"> رقم الشاسية </TableHead>
                        <TableHead class="text-start"> عداد السيارة </TableHead>
                        <TableHead class="text-start"> رقم لوحة السيارة </TableHead>
                        <TableHead class="text-start"> العنوان </TableHead>
                        <TableHead class="text-start"> ملاحظات </TableHead>
                        <TableHead class="text-end"> الإجراءات </TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow v-for="client in clients" :key="client.id">
                        <TableCell class="font-medium">{{ client.name }}</TableCell>
                        <TableCell>{{ client.mobile }}</TableCell>
                        <TableCell>{{ client.car_model.name }}</TableCell>
                        <TableCell>{{ client.email }}</TableCell>
                        <TableCell>{{ client.chassis_nu }}</TableCell>
                        <TableCell>{{ client.odometer }}</TableCell>
                        <TableCell>{{ client.plate_nu }}</TableCell>
                        <TableCell>{{ client.address }}</TableCell>
                        <TableCell>{{ client.notes }}</TableCell>
                        <TableCell class="text-end">
                            <div class="flex items-center justify-end space-x-2">
                                <CustomDialog title="عرض العميل" description="">
                                    <template #trigger>
                                        <Button variant="default" size="sm"><Eye /></Button>
                                    </template>
                                    <template #content>
                                        <Show :id="client.id" />
                                    </template>
                                </CustomDialog>
                                <Link :href="route('clients.edit', { client: client.id })" v-if="true">
                                    <Button variant="secondary" size="sm"><Pen /></Button>
                                </Link>
                                <Button @click="deleteClient(client)" variant="destructive" size="sm" v-if="true">
                                    <Trash2 />
                                </Button>
                            </div>
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>

            <!-- Mobile Cards -->
            <div class="space-y-2 md:hidden">
                <Card v-for="client in clients" :key="client.id">
                    <Collapsible>
                        <CollapsibleTrigger as-child>
                            <CardHeader class="flex flex-row items-center justify-between p-4 cursor-pointer">
                                <div>
                                    <CardTitle class="text-base">{{ client.name }}</CardTitle>
                                    <p class="text-sm text-muted-foreground">{{ client.mobile }}</p>
                                </div>
                                <ChevronDown class="h-4 w-4 transition-transform duration-200 group-data-[state=open]:rotate-180" />
                            </CardHeader>
                        </CollapsibleTrigger>
                        <CollapsibleContent>
                            <CardContent class="pt-0 space-y-3">
                                <div>
                                    <p class="text-sm font-medium text-muted-foreground">الجوال</p>
                                    <p class="text-sm">{{ client.mobile }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-muted-foreground">موديل السيارة</p>
                                    <p class="text-sm">{{ client.car_model.name }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-muted-foreground">البريد</p>
                                    <p class="text-sm">{{ client.email }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-muted-foreground">رقم الشاسية</p>
                                    <p class="text-sm">{{ client.chassis_nu }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-muted-foreground">عداد السيارة</p>
                                    <p class="text-sm">{{ client.odometer }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-muted-foreground">رقم لوحة السيارة</p>
                                    <p class="text-sm">{{ client.plate_nu }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-muted-foreground">العنوان</p>
                                    <p class="text-sm">{{ client.address }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-muted-foreground">ملاحظات</p>
                                    <p class="text-sm">{{ client.notes }}</p>
                                </div>
                                <div class="flex justify-end pt-2 space-x-2">
                                    <CustomDialog title="عرض العميل" description="">
                                        <template #trigger>
                                            <Button variant="default" size="sm"><Eye /></Button>
                                        </template>
                                        <template #content>
                                            <Show :id="client.id" />
                                        </template>
                                    </CustomDialog>
                                    <Link :href="route('clients.edit', { client: client.id })" v-if="true">
                                        <Button variant="secondary" size="sm"><Pen /></Button>
                                    </Link>
                                    <Button @click="deleteClient(client)" variant="destructive" size="sm" v-if="true">
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

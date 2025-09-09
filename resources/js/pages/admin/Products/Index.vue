<script setup lang="ts">
import CustomDialog from '@/components/shared/CustomDialog.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Collapsible, CollapsibleContent, CollapsibleTrigger } from '@/components/ui/collapsible';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import { Product } from '@/types/app';
import { Head, Link, router } from '@inertiajs/vue3';
import { useBreakpoints } from '@vueuse/core';
import { ChevronDown, Eye, Filter, Pen, Plus, Trash2 } from 'lucide-vue-next';
import Filters from './Filters.vue';
import Show from './Show.vue';

defineProps<{
    products: Product[];
    categories: { id: number; name: string }[];
}>();

const breakpoints = useBreakpoints({ mobile: 640 });
const isMobile = breakpoints.smaller('mobile');

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'لوحة التحكم', href: '/dashboard' },
    { title: 'المنتجات', href: '/products' },
];

const deleteProduct = (product: Product) => {
    if (confirm('هل أنت متأكد أنك تريد حذف هذا المنتج؟')) {
        router.delete(route('products.destroy', { product: product.id }));
    }
};
</script>

<template>
    <Head title="جدول المنتجات" />
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
                    <Filters :filters="{ search: null, category_id: null }" :categories="categories" />
                </CardContent>
            </Card>

            <div class="flex w-full justify-end">
                <Link :href="route('products.create')" v-if="true">
                    <Button type="button">
                        <Plus class="ml-1" />
                        <span>منتج جديد</span>
                    </Button>
                </Link>
            </div>

            <!-- Desktop Table -->
            <Table class="hidden md:table">
                <TableHeader>
                    <TableRow>
                        <TableHead class="text-start"> الكود </TableHead>
                        <TableHead class="text-start"> الاسم </TableHead>
                        <TableHead class="text-start"> الفئة </TableHead>
                        <TableHead class="text-start"> السعر </TableHead>
                        <TableHead class="text-start"> الكمية </TableHead>
                        <TableHead class="text-end"> الإجراءات </TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow v-for="product in products" :key="product.id">
                        <TableCell class="font-mono text-sm">{{ product.code }}</TableCell>
                        <TableCell class="font-medium">{{ product.name }}</TableCell>
                        <TableCell>{{ product.category?.name }}</TableCell>
                        <TableCell>{{ product.sell_price }}ج.م</TableCell>
                        <TableCell>
                            <span
                                :class="{
                                    'text-red-600': product.quantity <= product.min_stock_quantity,
                                    'text-yellow-600':
                                        product.quantity > product.min_stock_quantity && product.quantity <= product.min_stock_quantity + 5,
                                    'text-green-600': product.quantity > product.min_stock_quantity + 5,
                                }"
                            >
                                {{ product.quantity }}
                            </span>
                        </TableCell>
                        <TableCell class="text-end">
                            <div class="flex items-center justify-end space-x-2">
                                <CustomDialog title="عرض المنتج" description="">
                                    <template #trigger>
                                        <Button variant="default" size="sm"><Eye /></Button>
                                    </template>
                                    <template #content>
                                        <Show :id="product.id" />
                                    </template>
                                </CustomDialog>
                                <Link :href="route('products.edit', { product: product.id })" v-if="true">
                                    <Button variant="secondary" size="sm"><Pen /></Button>
                                </Link>
                                <Button @click="deleteProduct(product)" variant="destructive" size="sm" v-if="true">
                                    <Trash2 />
                                </Button>
                            </div>
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>

            <!-- Mobile Cards -->
            <div class="space-y-2 md:hidden">
                <Card v-for="product in products" :key="product.id">
                    <Collapsible>
                        <CollapsibleTrigger as-child>
                            <CardHeader class="flex cursor-pointer flex-row items-center justify-between p-4">
                                <div>
                                    <CardTitle class="text-base">{{ product.name }}</CardTitle>
                                    <p class="text-sm text-muted-foreground">{{ product.code }} | {{ product.category?.name }}</p>
                                </div>
                                <ChevronDown class="h-4 w-4 transition-transform duration-200 group-data-[state=open]:rotate-180" />
                            </CardHeader>
                        </CollapsibleTrigger>
                        <CollapsibleContent>
                            <CardContent class="space-y-3 pt-0">
                                <div class="grid grid-cols-2">
                                    <p class="text-sm font-medium text-muted-foreground">السعر</p>
                                    <p class="text-sm">{{ product.sell_price }}ج.م</p>
                                </div>
                                <div class="grid grid-cols-2">
                                    <p class="text-sm font-medium text-muted-foreground">الكمية</p>
                                    <p
                                        class="text-sm"
                                        :class="{
                                            'text-red-600': product.quantity <= product.min_stock_quantity,
                                            'text-yellow-600':
                                                product.quantity > product.min_stock_quantity && product.quantity <= product.min_stock_quantity + 5,
                                            'text-green-600': product.quantity > product.min_stock_quantity + 5,
                                        }"
                                    >
                                        {{ product.quantity }}
                                    </p>
                                </div>
                                <div class="grid grid-cols-2">
                                    <p class="text-sm font-medium text-muted-foreground">الحد الأدنى</p>
                                    <p class="text-sm">{{ product.min_stock_quantity }}</p>
                                </div>
                                <div v-if="product.description" class="grid grid-cols-1">
                                    <p class="text-sm font-medium text-muted-foreground">الوصف</p>
                                    <p class="text-sm">{{ product.description }}</p>
                                </div>
                                <div class="flex justify-end space-x-2 pt-2">
                                    <CustomDialog title="عرض المنتج" description="">
                                        <template #trigger>
                                            <Button variant="default" size="sm"><Eye /></Button>
                                        </template>
                                        <template #content>
                                            <Show :id="product.id" />
                                        </template>
                                    </CustomDialog>
                                    <Link :href="route('products.edit', { product: product.id })" v-if="true">
                                        <Button variant="secondary" size="sm"><Pen /></Button>
                                    </Link>
                                    <Button @click="deleteProduct(product)" variant="destructive" size="sm" v-if="true">
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

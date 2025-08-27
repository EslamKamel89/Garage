<script setup lang="ts">
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { AlertTriangle, DollarSign, Package, Users, Wrench } from 'lucide-vue-next';
import { computed } from 'vue';

defineProps<{
    stats: {
        totalRevenue: number;
        totalInvoices: number;
        paidInvoices: number;
        unpaidInvoices: number;
        partialInvoices: number;
        revenueChange: number;
    };
    topProducts: {
        name: string;
        category_name: string;
        total_quantity: number;
        total_revenue: number;
    }[];
    topLabors: Record<string, { count: number; total_fee: number }>;
    lowStockProducts: {
        id: number;
        name: string;
        quantity: number;
        min_stock_quantity: number;
    }[];
    recentInvoices: {
        id: number;
        client: { name: string };
        actual_paid_amount: number;
        status: string;
        created_at: string;
    }[];
    clientStats: {
        total: number;
        newThisMonth: number;
        topClient: { name: string; invoice_count: number } | null;
    };
    monthlyRevenue: { label: string; revenue: number }[];
}>();
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'الأحصائيات',
        href: '/dashboard',
    },
];

const statusLabels = {
    unpaid: 'غير مدفوعة',
    paid: 'مدفوعة',
    partial: 'جزئي',
    cancelled: 'ملغاة',
};

const statusColors = {
    unpaid: 'text-red-600 bg-red-100',
    paid: 'text-green-600 bg-green-100',
    partial: 'text-yellow-600 bg-yellow-100',
    cancelled: 'text-gray-600 bg-gray-100',
};

// Format number as currency
const formatCurrency = (value: number) => {
    return new Intl.NumberFormat('ar-SA', { style: 'currency', currency: 'SAR' }).format(value);
};

// Calculate percentage
const percentage = computed(() => {
    const total = stats.totalInvoices;
    if (total === 0) return { paid: 0, unpaid: 0, partial: 0 };
    return {
        paid: Math.round((stats.paidInvoices / total) * 100),
        unpaid: Math.round((stats.unpaidInvoices / total) * 100),
        partial: Math.round((stats.partialInvoices / total) * 100),
    };
});
</script>

<template>
    <Head title="الأحصائيات" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 p-4">
            <h1 class="text-2xl font-bold">لوحة الإحصائيات</h1>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <!-- Total Revenue -->
                <Card>
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium">الإجمالي المكتسب</CardTitle>
                        <DollarSign class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">{{ formatCurrency(stats?.totalRevenue) }}</div>
                        <p class="text-xs text-muted-foreground">
                            <span :class="stats.revenueChange >= 0 ? 'text-green-600' : 'text-red-600'">
                                {{ stats.revenueChange >= 0 ? '+' : '' }}{{ stats.revenueChange.toFixed(1) }}%
                            </span>
                            من الشهر الماضي
                        </p>
                    </CardContent>
                </Card>

                <!-- Total Invoices -->
                <Card>
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium">إجمالي الفواتير</CardTitle>
                        <Package class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">{{ stats.totalInvoices }}</div>
                        <p class="text-xs text-muted-foreground">{{ stats.paidInvoices }} مدفوعة، {{ stats.unpaidInvoices }} غير مدفوعة</p>
                    </CardContent>
                </Card>

                <!-- Total Clients -->
                <Card>
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium">إجمالي العملاء</CardTitle>
                        <Users class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">{{ clientStats.total }}</div>
                        <p class="text-xs text-muted-foreground">{{ clientStats.newThisMonth }} عميل جديد هذا الشهر</p>
                    </CardContent>
                </Card>

                <!-- Low Stock Alert -->
                <Card>
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium">تحذير المخزون</CardTitle>
                        <AlertTriangle class="h-4 w-4 text-red-600" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold text-red-600">{{ lowStockProducts.length }}</div>
                        <p class="text-xs text-muted-foreground">منتجات متوفرة بكمية قليلة</p>
                    </CardContent>
                </Card>
            </div>

            <!-- Charts & Lists -->
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                <!-- Monthly Revenue Chart -->
                <Card class="lg:col-span-1">
                    <CardHeader>
                        <CardTitle>الإيرادات الشهرية</CardTitle>
                        <CardDescription>آخر 6 أشهر</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div class="space-y-4">
                            <div v-for="item in monthlyRevenue" :key="item.label" class="flex items-center">
                                <span class="w-16 text-sm">{{ item.label }}</span>
                                <div class="mx-2 h-2 flex-1 rounded-full bg-gray-200">
                                    <div
                                        class="h-2 rounded-full bg-blue-600"
                                        :style="{ width: `${Math.max(5, (item.revenue / monthlyRevenue[5]?.revenue) * 100)}%` }"
                                    ></div>
                                </div>
                                <span class="w-20 text-left text-sm">{{ formatCurrency(item.revenue) }}</span>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <!-- Top Products -->
                <Card class="lg:col-span-1">
                    <CardHeader>
                        <CardTitle>أفضل المنتجات مبيعًا</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div class="space-y-3">
                            <div v-for="product in topProducts" :key="product.name" class="flex justify-between text-sm">
                                <div>
                                    <p class="font-medium">{{ product.name }}</p>
                                    <p class="text-xs text-muted-foreground">{{ product.category_name }}</p>
                                </div>
                                <div class="text-right">
                                    <p>{{ product.total_quantity }} وحدة</p>
                                    <p class="font-medium">{{ formatCurrency(product.total_revenue) }}</p>
                                </div>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <!-- Top Labors -->
                <Card class="lg:col-span-1">
                    <CardHeader>
                        <CardTitle>الأعمال الشائعة</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div class="space-y-3">
                            <div v-for="(data, name) in topLabors" :key="name" class="flex justify-between text-sm">
                                <div class="flex items-center">
                                    <Wrench class="ml-1 h-4 w-4 text-muted-foreground" />
                                    <span>{{ name }}</span>
                                </div>
                                <div class="text-right">
                                    <p>{{ data.count }} مرة</p>
                                    <p class="font-medium">{{ formatCurrency(data.total_fee) }}</p>
                                </div>
                            </div>
                        </div>
                    </CardContent>
                </Card>
            </div>

            <!-- Bottom Grid -->
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <!-- Low Stock Products -->
                <Card>
                    <CardHeader>
                        <CardTitle>تحذير: منخفض المخزون</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div v-if="lowStockProducts.length === 0" class="py-4 text-center text-sm text-muted-foreground">
                            لا توجد منتجات منخفضة المخزون
                        </div>
                        <div v-else class="space-y-2">
                            <div v-for="product in lowStockProducts" :key="product.id" class="flex justify-between border-b py-1">
                                <span>{{ product.name }}</span>
                                <span class="font-medium"> {{ product.quantity }} / {{ product.min_stock_quantity }} </span>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <!-- Recent Invoices -->
                <Card>
                    <CardHeader>
                        <CardTitle>الفواتير الأخيرة</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div class="space-y-2">
                            <div v-for="invoice in recentInvoices" :key="invoice.id" class="flex justify-between border-b py-1 text-sm">
                                <div>
                                    <p class="font-medium">{{ invoice.client?.name }}</p>
                                    <p class="text-xs text-muted-foreground">{{ invoice.created_at }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-medium">{{ formatCurrency(invoice.actual_paid_amount) }}</p>
                                    <span :class="`rounded-full px-2 py-0.5 text-xs ${statusColors[invoice.status]}`">
                                        {{ statusLabels[invoice.status] }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import Input from '@/components/ui/input/Input.vue';
import Label from '@/components/ui/label/Label.vue';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Client, Invoice, Labour, Product } from '@/types/app';
import { router } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';

const props = defineProps<{
    clients?: Client[];
    products?: Product[];
    labours?: Labour[];
    invoice?: Invoice;
    action?: 'create' | 'edit';
}>();

// const { toast } = useToast();

// Form state
const form = ref({
    client_id: props.invoice?.client_id ?? '',
    items:
        props.invoice?.items?.map((item) => ({
            product_id: item.product_id,
            quantity: item.quantity,
            unit_price: item.unit_price,
            total: item.total_price,
        })) ?? [],
    labor_info: { items: props.invoice?.labor_info?.items ?? [] },
    actual_total: props.invoice?.actual_total ?? 0,
    actual_paid_amount: props.invoice?.actual_paid_amount ?? 0,
    status: props.invoice?.status ?? 'unpaid',
    notes: props.invoice?.notes ?? '',
});

// Search states
const productSearch = ref('');
const laborSearch = ref('');
const filteredProducts = ref<Product[]>([]);
const filteredLabours = ref<Labour[]>([]);

// Computed
const calculatedTotal = computed(() => {
    const productTotal = form.value.items.reduce((sum, item) => sum + item.total, 0);
    const laborTotal = form.value.labor_info.items.reduce((sum, item) => sum + item.fee, 0);
    return productTotal + laborTotal;
});

// Watchers
watch(calculatedTotal, (val) => {
    if (!props.invoice) {
        form.value.actual_total = val;
    }
});

// Methods
const addProduct = (product: Product) => {
    const item = {
        product_id: product.id,
        quantity: 1,
        unit_price: product.sell_price,
        total: product.sell_price,
    };
    form.value.items.push(item);
    productSearch.value = '';
    filteredProducts.value = [];
};

const removeProduct = (index: number) => {
    form.value.items.splice(index, 1);
};

const updateProductQuantity = (index: number) => {
    const item = form.value.items[index];
    item.total = item.quantity * item.unit_price;
};

const addLabor = (labour: Labour) => {
    const item = { name: labour.name_ar, fee: labour.base_fee };
    form.value.labor_info.items.push(item);
    laborSearch.value = '';
    filteredLabours.value = [];
};

const removeLabor = (index: number) => {
    form.value.labor_info.items.splice(index, 1);
};

const saveInvoice = () => {
    if (props.action === 'edit') {
        router.put(route('invoices.update', { invoice: props.invoice?.id }), {
            client_id: form.value.client_id,
            items: form.value.items,
            labor_info: form.value.labor_info,
            actual_total: form.value.actual_total,
            actual_paid_amount: form.value.actual_paid_amount,
            status: form.value.status,
            notes: form.value.notes,
        });
    } else {
        router.post(route('invoices.store'), {
            client_id: form.value.client_id,
            items: form.value.items,
            labor_info: form.value.labor_info,
            actual_total: form.value.actual_total,
            actual_paid_amount: form.value.actual_paid_amount,
            status: form.value.status,
            notes: form.value.notes,
        });
    }
};

// Search filtering
watch(productSearch, () => {
    if (productSearch.value.length > 0) {
        filteredProducts.value = (props.products || []).filter((p) => p.name.includes(productSearch.value) || p.code.includes(productSearch.value));
    } else {
        filteredProducts.value = [];
    }
});

watch(laborSearch, () => {
    if (laborSearch.value.length > 0) {
        filteredLabours.value = (props.labours || []).filter((l) => l.name_ar.includes(laborSearch.value));
    } else {
        filteredLabours.value = [];
    }
});
</script>

<template>
    <Card class="mx-auto max-w-4xl">
        <CardHeader>
            <CardTitle>إنشاء فاتورة</CardTitle>
        </CardHeader>
        <CardContent class="space-y-6">
            <!-- Client -->
            <div class="grid w-full items-center gap-1.5">
                <Label>العميل</Label>
                <Select v-model="form.client_id" required>
                    <SelectTrigger>
                        <SelectValue placeholder="اختر العميل" />
                    </SelectTrigger>
                    <SelectContent>
                        <SelectItem v-for="client in clients" :key="client.id" :value="client.id">
                            {{ client.name }} - {{ client.mobile }}
                        </SelectItem>
                    </SelectContent>
                </Select>
            </div>

            <!-- Products -->
            <div>
                <Label>المنتجات</Label>
                <div class="mt-2">
                    <Input v-model="productSearch" placeholder="ابحث عن منتج..." />
                    <div v-if="filteredProducts.length" class="mt-2 max-h-40 overflow-auto rounded-md border p-2">
                        <div
                            v-for="product in filteredProducts"
                            :key="product.id"
                            @click="addProduct(product)"
                            class="cursor-pointer p-2 hover:bg-gray-100"
                        >
                            {{ product.name }} ({{ product.code }}) - {{ product.sell_price }} ج
                        </div>
                    </div>
                </div>
                <div class="mt-4 space-y-2">
                    <div v-for="(item, index) in form.items" :key="index" class="flex items-center gap-2">
                        <span class="flex-1">{{ products.find((p) => p.id === item.product_id)?.name }}</span>
                        <Input v-model.number="item.quantity" @input="updateProductQuantity(index)" type="number" class="w-20" />
                        <Input v-model.number="item.unit_price" @input="updateProductQuantity(index)" type="number" class="w-24" />
                        <span class="w-20">{{ item.total }} ج</span>
                        <Button @click="removeProduct(index)" variant="destructive" size="sm">حذف</Button>
                    </div>
                </div>
            </div>

            <!-- Labors -->
            <div>
                <Label>الأعمال (العمالة)</Label>
                <div class="mt-2">
                    <Input v-model="laborSearch" placeholder="ابحث عن عمل..." />
                    <div v-if="filteredLabours.length" class="mt-2 max-h-40 overflow-auto rounded-md border p-2">
                        <div
                            v-for="labour in filteredLabours"
                            :key="labour.id"
                            @click="addLabor(labour)"
                            class="cursor-pointer p-2 hover:bg-gray-100"
                        >
                            {{ labour.name_ar }} - {{ labour.base_fee }} ج
                        </div>
                    </div>
                </div>
                <div class="mt-4 space-y-2">
                    <div v-for="(item, index) in form.labor_info.items" :key="index" class="flex items-center gap-2">
                        <Input v-model="item.name" class="flex-1" />
                        <Input v-model.number="item.fee" type="number" class="w-24" />
                        <Button @click="removeLabor(index)" variant="destructive" size="sm">حذف</Button>
                    </div>
                </div>
            </div>

            <!-- Totals -->
            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                <div class="grid w-full items-center gap-1.5">
                    <Label>الإجمالي المحسوب</Label>
                    <Input :value="calculatedTotal" readonly />
                </div>
                <div class="grid w-full items-center gap-1.5">
                    <Label>الإجمالي الفعلي</Label>
                    <Input v-model.number="form.actual_total" type="number" />
                </div>
                <div class="grid w-full items-center gap-1.5">
                    <Label>المدفوع</Label>
                    <Input v-model.number="form.actual_paid_amount" type="number" />
                </div>
            </div>

            <!-- Status & Notes -->
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div class="grid w-full items-center gap-1.5">
                    <Label>الحالة</Label>
                    <Select v-model="form.status">
                        <SelectTrigger>
                            <SelectValue />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem value="unpaid">غير مدفوعة</SelectItem>
                            <SelectItem value="paid">مدفوعة</SelectItem>
                            <SelectItem value="partial">جزئي</SelectItem>
                            <SelectItem value="cancelled">ملغاة</SelectItem>
                        </SelectContent>
                    </Select>
                </div>
                <div class="grid w-full items-center gap-1.5">
                    <Label>ملاحظات</Label>
                    <Input v-model="form.notes" type="text" />
                </div>
            </div>
        </CardContent>
        <CardFooter>
            <Button @click="saveInvoice">حفظ الفاتورة</Button>
        </CardFooter>
    </Card>
</template>

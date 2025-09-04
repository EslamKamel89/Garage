<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import Input from '@/components/ui/input/Input.vue';
import Label from '@/components/ui/label/Label.vue';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Client, Invoice, InvoiceItem, Item, Labour, Product } from '@/types/app';
import { router } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';

const props = defineProps<{
    clients?: Client[];
    products?: Product[];
    labours?: Labour[];
    invoice?: Invoice;
    action?: 'create' | 'edit' | 'show';
}>();

// const { toast } = useToast();

// Form state
const form = ref({
    client_id: props.invoice?.client_id ?? null,
    // items: (props.invoice?.items?.map((item) => ({
    //     product_id: item.product_id,
    //     quantity: item.quantity,
    //     unit_price: item.unit_price,
    //     total: item.total_price,
    // })) ?? []) as InvoiceItem[],
    items: [] as InvoiceItem[],
    labor_info: { items: props.invoice?.labor_info?.items ?? [] },
    actual_total: props.invoice?.actual_total ?? 0,
    actual_paid_amount: props.invoice?.actual_paid_amount ?? 0,
    status: props.invoice?.status ?? 'unpaid',
    notes: props.invoice?.notes ?? '',
});

// Search states
const productSearch = ref('');
const clientSearch = ref('');
const selectedClient = ref<Client>();
const laborSearch = ref('');
const filteredClients = ref<Client[]>([]);
const filteredProducts = ref<Product[]>([]);
const filteredLabours = ref<Labour[]>([]);

// Computed
const calculatedTotal = computed(() => {
    const productTotal: number = form.value.items.reduce((sum: number, item) => sum + Number(item.total), 0);
    const laborTotal: number = form.value.labor_info.items.reduce((sum, item) => sum + Number(item.fee), 0);
    return productTotal + laborTotal;
});

// Watchers
watch(calculatedTotal, (val) => {
    if (!props.invoice) {
        form.value.actual_total = val;
    }
});

// Methods
const addClient = (client: Client) => {
    form.value.client_id = client.id;
    selectedClient.value = client;
    clientSearch.value = '';
};

const removeClient = () => {
    form.value.client_id = null;
    selectedClient.value = undefined;
};

const addProduct = (product: Product) => {
    const item = {
        product_id: product.id,
        quantity: 1,
        unit_price: product.sell_price,
        total: product.sell_price,
    };
    form.value.items.push(item);
    productSearch.value = '';
    // filteredProducts.value = [];
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
    // filteredLabours.value = [];
};
const customLabour = ref<Item>({
    fee: 0,
    name: '',
});
const addCustomLabor = () => {
    if (customLabour.value.name !== '') form.value.labor_info.items.push(customLabour.value);
    customLabour.value = { fee: 0, name: '' };
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
watch(
    clientSearch,
    () => {
        filteredClients.value = (props.clients || []).filter((p) => p.name.includes(clientSearch.value) || p.mobile.includes(clientSearch.value));
        // console.log(filterd);
    },
    { immediate: true },
);
watch(
    productSearch,
    () => {
        filteredProducts.value = (props.products || []).filter((p) => p.name.includes(productSearch.value) || p.code.includes(productSearch.value));
    },
    { immediate: true },
);

watch(
    laborSearch,
    () => {
        filteredLabours.value = (props.labours || []).filter((l) => l.name_ar.includes(laborSearch.value));
    },
    { immediate: true },
);
</script>

<template>
    <Card class="mx-auto max-w-4xl">
        <CardHeader>
            <CardTitle>إنشاء فاتورة</CardTitle>
        </CardHeader>
        <CardContent class="space-y-6">
            <!-- Clients -->
            <div>
                <Label>العميل</Label>
                <div class="mt-2">
                    <Input v-model="clientSearch" placeholder="ابحث عن عميل..." />
                    <div v-if="filteredClients.length" class="mt-2 max-h-40 overflow-auto rounded-md border p-2">
                        <div
                            v-for="client in filteredClients"
                            :key="client.id"
                            @click="addClient(client)"
                            class="cursor-pointer p-2 hover:bg-gray-100"
                        >
                            {{ client.name }} ({{ client.mobile }})
                        </div>
                    </div>
                </div>
                <div class="mt-4 space-y-2">
                    <div v-if="selectedClient" class="space-x-4">
                        <span class="w-20">{{ selectedClient.name }} ({{ selectedClient.mobile }})</span>
                        <Button @click="removeClient()" variant="destructive" size="sm">حذف</Button>
                    </div>
                </div>
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
                        <span class="flex-1">{{ products?.find((p) => p.id === item.product_id)?.name }}</span>
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
                    <div class="flex items-center gap-2">
                        <Input v-model="customLabour.name" class="flex-1" />
                        <Input v-model.number="customLabour.fee" type="number" class="w-24" />
                        <Button @click="addCustomLabor()" variant="default" size="sm">اضافة</Button>
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

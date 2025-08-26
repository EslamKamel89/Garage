<script setup lang="ts">
import Button from '@/components/ui/button/Button.vue';
import Input from '@/components/ui/input/Input.vue';
import Label from '@/components/ui/label/Label.vue';
import { Product } from '@/types/app';
import { useForm } from '@inertiajs/vue3';

const props = defineProps<{
    action: 'create' | 'edit' | 'show';
    product?: Product;
    categories?: { id: number; name: string }[];
}>();

const form = useForm({
    category_id: props.product?.category_id ?? '',
    code: props.product?.code ?? '',
    name: props.product?.name ?? '',
    description: props.product?.description ?? '',
    buy_price: props.product?.buy_price ?? '',
    sell_price: props.product?.sell_price ?? '',
    quantity: props.product?.quantity ?? '',
    min_stock_quantity: props.product?.min_stock_quantity ?? '',
    notes: props.product?.notes ?? '',
});

const submit = async () => {
    const formTransformed = form.transform((data) => ({
        category_id: data.category_id,
        code: data.code,
        name: data.name,
        description: data.description,
        buy_price: data.buy_price,
        sell_price: data.sell_price,
        quantity: data.quantity,
        min_stock_quantity: data.min_stock_quantity,
        notes: data.notes,
    }));

    if (props.action === 'create') {
        formTransformed.post(route('products.store'));
    } else if (props.action === 'edit') {
        formTransformed.put(route('products.update', { product: props.product?.id }));
    }
};
</script>

<template>
    <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
        <form @submit.prevent="submit" class="flex w-full flex-col items-start space-y-3">
            <div class="grid w-full items-center gap-1.5">
                <Label for="category_id">الفئة</Label>
                <select
                    v-if="action !== 'show'"
                    v-model="form.category_id"
                    id="category_id"
                    class="w-full rounded-lg border border-gray-600 px-3 py-2"
                >
                    <option disabled value="">اختر الفئة</option>
                    <option v-for="category in categories" :key="category.id" :value="category.id">{{ category.name }}</option>
                </select>
                <div v-else>{{ product?.category?.name }}</div>
                <div class="text-xs font-thin text-red-700" v-if="form.errors.category_id">
                    {{ form.errors.category_id }}
                </div>
            </div>

            <div class="grid w-full items-center gap-1.5">
                <Label for="code">الكود</Label>
                <Input :disabled="action === 'show'" v-model="form.code" id="code" type="text" />
                <div class="text-xs font-thin text-red-700" v-if="form.errors.code">
                    {{ form.errors.code }}
                </div>
            </div>

            <div class="grid w-full items-center gap-1.5">
                <Label for="name">الاسم</Label>
                <Input :disabled="action === 'show'" v-model="form.name" id="name" type="text" />
                <div class="text-xs font-thin text-red-700" v-if="form.errors.name">
                    {{ form.errors.name }}
                </div>
            </div>

            <div class="grid w-full items-center gap-1.5">
                <Label for="description">الوصف</Label>
                <Input :disabled="action === 'show'" v-model="form.description" id="description" type="text" />
                <div class="text-xs font-thin text-red-700" v-if="form.errors.description">
                    {{ form.errors.description }}
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div class="grid w-full items-center gap-1.5">
                    <Label for="buy_price">سعر الشراء</Label>
                    <Input :disabled="action === 'show'" v-model="form.buy_price" id="buy_price" type="number" step="0.01" />
                    <div class="text-xs font-thin text-red-700" v-if="form.errors.buy_price">
                        {{ form.errors.buy_price }}
                    </div>
                </div>

                <div class="grid w-full items-center gap-1.5">
                    <Label for="sell_price">سعر البيع</Label>
                    <Input :disabled="action === 'show'" v-model="form.sell_price" id="sell_price" type="number" step="0.01" />
                    <div class="text-xs font-thin text-red-700" v-if="form.errors.sell_price">
                        {{ form.errors.sell_price }}
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div class="grid w-full items-center gap-1.5">
                    <Label for="quantity">الكمية الحالية</Label>
                    <Input :disabled="action === 'show'" v-model.number="form.quantity" id="quantity" type="number" />
                    <div class="text-xs font-thin text-red-700" v-if="form.errors.quantity">
                        {{ form.errors.quantity }}
                    </div>
                </div>

                <div class="grid w-full items-center gap-1.5">
                    <Label for="min_stock_quantity">الحد الأدنى</Label>
                    <Input :disabled="action === 'show'" v-model.number="form.min_stock_quantity" id="min_stock_quantity" type="number" />
                    <div class="text-xs font-thin text-red-700" v-if="form.errors.min_stock_quantity">
                        {{ form.errors.min_stock_quantity }}
                    </div>
                </div>
            </div>

            <div class="grid w-full items-center gap-1.5">
                <Label for="notes">ملاحظات</Label>
                <Input :disabled="action === 'show'" v-model="form.notes" id="notes" type="text" />
                <div class="text-xs font-thin text-red-700" v-if="form.errors.notes">
                    {{ form.errors.notes }}
                </div>
            </div>

            <Button type="submit" class="max-w-sm" v-if="action !== 'show'">
                {{ action === 'create' ? 'إنشاء' : 'تحديث' }}
            </Button>
        </form>
    </div>
</template>

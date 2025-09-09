<script setup lang="ts">
import Button from '@/components/ui/button/Button.vue';
import Input from '@/components/ui/input/Input.vue';
import Label from '@/components/ui/label/Label.vue';
import { Labour } from '@/types/app';
import { useForm } from '@inertiajs/vue3';

const props = defineProps<{
    action: 'create' | 'edit' | 'show';
    labour?: Labour;
}>();

const form = useForm({
    name_ar: props.labour?.name_ar ?? '',
    base_fee: props.labour?.base_fee ?? '',
    is_active: props.labour?.is_active ?? true,
});

const submit = async () => {
    const formTransformed = form.transform((data) => ({
        name_ar: data.name_ar,
        base_fee: data.base_fee,
        is_active: data.is_active,
    }));

    if (props.action === 'create') {
        formTransformed.post(route('labours.store'));
    } else if (props.action === 'edit') {
        formTransformed.put(route('labours.update', { labour: props.labour?.id }));
    }
};
</script>

<template>
    <div class="flex flex-col flex-1 h-full gap-4 p-4 rounded-xl">
        <form @submit.prevent="submit" class="flex flex-col items-start w-full space-y-3">
            <div class="grid w-full items-center gap-1.5">
                <Label for="name_ar">الاسم </Label>
                <Input :disabled="action === 'show'" v-model="form.name_ar" id="name_ar" type="text" />
                <div class="text-xs font-thin text-red-700" v-if="form.errors.name_ar">
                    {{ form.errors.name_ar }}
                </div>
            </div>

            <div class="grid w-full items-center gap-1.5">
                <Label for="base_fee">السعر الأساسي</Label>
                <Input :disabled="action === 'show'" v-model.number="form.base_fee" id="base_fee" type="number" step="0.01" />
                <div class="text-xs font-thin text-red-700" v-if="form.errors.base_fee">
                    {{ form.errors.base_fee }}
                </div>
            </div>

            <div class="grid w-full items-center gap-1.5">
                <Label for="is_active">الحالة</Label>
                <select v-if="action !== 'show'" v-model="form.is_active" id="is_active" class="px-3 py-2 border border-gray-600 rounded-lg">
                    <option :value="true">مفعل</option>
                    <option :value="false">معطل</option>
                </select>
                <div v-else>{{ labour?.is_active ? 'مفعل' : 'معطل' }}</div>
            </div>

            <Button type="submit" class="max-w-sm" v-if="action !== 'show'">
                {{ action === 'create' ? 'إنشاء' : 'تحديث' }}
            </Button>
        </form>
    </div>
</template>

<script setup lang="ts">
import Button from '@/components/ui/button/Button.vue';
import Input from '@/components/ui/input/Input.vue';
import Label from '@/components/ui/label/Label.vue';
import { Select, SelectContent, SelectGroup, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { AppPageProps } from '@/types';
import { CarModel, Client } from '@/types/app';
import { useForm, usePage } from '@inertiajs/vue3';
import { onMounted } from 'vue';
const props = defineProps<{
    action: 'create' | 'edit' | 'show';
    client?: Client;
}>();
const page = usePage<AppPageProps & { car_models: CarModel[] }>();
const form = useForm({
    car_model_id: props.client?.car_model?.id,
    name: props.client?.name ?? '',
    mobile: props.client?.mobile ?? '',
    email: props.client?.email ?? '',
    address: props.client?.address ?? '',
    odometer: props.client?.odometer ?? 0,
    chassis_nu: props.client?.chassis_nu ?? '',
    plate_nu: props.client?.plate_nu ?? '',
    notes: props.client?.notes ?? '',
});

const submit = async () => {
    if (props.action === 'create') {
        form.post(route('clients.store'));
    } else if (props.action === 'edit') {
        form.put(route('clients.update', { client: props.client?.id }));
    }
};
onMounted(() => {
    if (props.action === 'show' && props.client?.car_model) {
        page.props.car_models = [props.client?.car_model];
    }
});
</script>

<template>
    <div class="flex flex-col flex-1 h-full gap-4 p-4 rounded-xl">
        <form @submit.prevent="submit" class="flex flex-col items-start w-full space-y-3">
            <div class="grid w-full items-center gap-1.5">
                <Label for="name">الاسم</Label>
                <Input :disabled="action === 'show'" v-model="form.name" id="name" type="text" />
                <div class="text-xs font-thin text-red-700" v-if="form.errors.name">
                    {{ form.errors.name }}
                </div>
            </div>

            <div class="grid w-full items-center gap-1.5">
                <Label for="mobile">الجوال</Label>
                <Input :disabled="action === 'show'" v-model="form.mobile" id="mobile" type="text" />
                <div class="text-xs font-thin text-red-700" v-if="form.errors.mobile">
                    {{ form.errors.mobile }}
                </div>
            </div>
            <div class="grid w-full items-start gap-1.5">
                <Label>موديل السيارة</Label>
                <Select v-model="form.car_model_id" class="w-full text-start">
                    <SelectTrigger class="w-full text-start">
                        <SelectValue class="text-start" placeholder="اختر موديل السيارة" />
                    </SelectTrigger>
                    <SelectContent>
                        <SelectGroup>
                            <SelectItem
                                class="flex w-full !items-start !text-right"
                                v-for="car_model in page.props.car_models"
                                :key="car_model.id"
                                :value="car_model.id"
                            >
                                <div class="w-full text-right">
                                    {{ car_model.name }}
                                </div>
                            </SelectItem>
                        </SelectGroup>
                    </SelectContent>
                </Select>
                <div class="text-xs font-thin text-red-700" v-if="form.errors.car_model_id">
                    {{ form.errors.car_model_id }}
                </div>
            </div>

            <div class="grid w-full items-center gap-1.5">
                <Label for="email">البريد الإلكتروني</Label>
                <Input :disabled="action === 'show'" v-model="form.email" id="email" type="email" />
                <div class="text-xs font-thin text-red-700" v-if="form.errors.email">
                    {{ form.errors.email }}
                </div>
            </div>

            <div class="grid w-full items-center gap-1.5">
                <Label for="address">العنوان</Label>
                <Input :disabled="action === 'show'" v-model="form.address" id="address" type="text" />
                <div class="text-xs font-thin text-red-700" v-if="form.errors.address">
                    {{ form.errors.address }}
                </div>
            </div>

            <div class="grid w-full items-center gap-1.5">
                <Label for="chassis">رقم الشاسية</Label>
                <Input :disabled="action === 'show'" v-model="form.chassis_nu" id="chassis" type="text" />
                <div class="text-xs font-thin text-red-700" v-if="form.errors.chassis_nu">
                    {{ form.errors.chassis_nu }}
                </div>
            </div>
            <div class="grid w-full items-center gap-1.5">
                <Label for="odometer"> عداد السيارة </Label>
                <Input :disabled="action === 'show'" v-model.number="form.odometer" id="odometer" type="number" />
                <div class="text-xs font-thin text-red-700" v-if="form.errors.odometer">
                    {{ form.errors.odometer }}
                </div>
            </div>
            <div class="grid w-full items-center gap-1.5">
                <Label for="plate_nu"> رقم لوحة السيارة</Label>
                <Input :disabled="action === 'show'" v-model="form.plate_nu" id="plate_nu" type="text" />
                <div class="text-xs font-thin text-red-700" v-if="form.errors.plate_nu">
                    {{ form.errors.plate_nu }}
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

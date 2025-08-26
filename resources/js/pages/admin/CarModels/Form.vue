<script setup lang="ts">
import Button from '@/components/ui/button/Button.vue';
import Input from '@/components/ui/input/Input.vue';
import Label from '@/components/ui/label/Label.vue';
import { CarModel } from '@/types/app';
import { useForm } from '@inertiajs/vue3';

const props = defineProps<{
    action: 'create' | 'edit' | 'show';
    car_model?: CarModel;
}>();

const form = useForm({
    name: props.car_model?.name ?? '',
    from: props.car_model?.year_range?.from,
    to: props.car_model?.year_range?.to,
    // year_range: props.car_model?.year_range ?? { from: undefined, to: undefined },
});

const submit = async () => {
    const formTransformed = form.transform((data) => {
        return {
            name: data.name,
            year_range: {
                from: data.from,
                to: data.to,
            },
        };
    });
    if (props.action === 'create') {
        formTransformed.post(route('car-models.store'));
    } else if (props.action === 'edit') {
        formTransformed.put(route('car-models.update', { car_model: props.car_model?.id }));
    }
};
</script>

<template>
    <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
        <form @submit.prevent="submit" class="flex w-full flex-col items-start space-y-3">
            <div class="grid w-full items-center gap-1.5">
                <Label for="name">الاسم</Label>
                <Input :disabled="action === 'show'" v-model="form.name" id="name" type="text" />
                <div class="text-xs font-thin text-red-700" v-if="form.errors.name">
                    {{ form.errors.name }}
                </div>
            </div>

            <div class="grid w-full items-center gap-1.5">
                <Label>نطاق السنوات</Label>
                <div class="flex space-x-2 space-x-reverse">
                    <div class="flex w-full flex-col">
                        <Input :disabled="action === 'show'" v-model.number="form.from" type="number" placeholder="من" />
                        <div class="text-xs font-thin text-red-700">
                            {{ (form.errors as any)['year_range.from'] ?? null }}
                        </div>
                    </div>
                    <div class="flex w-full flex-col">
                        <Input :disabled="action === 'show'" v-model.number="form.to" type="number" placeholder="إلى" />
                        <div class="text-xs font-thin text-red-700">
                            {{ (form.errors as any)['year_range.to'] ?? null }}
                        </div>
                    </div>
                </div>
            </div>

            <Button type="submit" class="max-w-sm" v-if="action !== 'show'">
                {{ action === 'create' ? 'إنشاء' : 'تحديث' }}
            </Button>
        </form>
    </div>
</template>

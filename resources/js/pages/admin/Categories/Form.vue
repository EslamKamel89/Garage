<script setup lang="ts">
import Button from '@/components/ui/button/Button.vue';
import Input from '@/components/ui/input/Input.vue';
import Label from '@/components/ui/label/Label.vue';
import { Category } from '@/types/app';
import { useForm } from '@inertiajs/vue3';

const props = defineProps<{
    action: 'create' | 'edit' | 'show';
    category?: Category;
}>();

const form = useForm({
    name: props.category?.name ?? '',
    description: props.category?.description ?? '',
});

const submit = async () => {
    const formTransformed = form.transform((data) => ({
        name: data.name,
        description: data.description,
    }));

    if (props.action === 'create') {
        formTransformed.post(route('categories.store'));
    } else if (props.action === 'edit') {
        formTransformed.put(route('categories.update', { category: props.category?.id }));
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
                <Label for="description">الوصف</Label>
                <Input :disabled="action === 'show'" v-model="form.description" id="description" type="text" />
                <div class="text-xs font-thin text-red-700" v-if="form.errors.description">
                    {{ form.errors.description }}
                </div>
            </div>

            <Button type="submit" class="max-w-sm" v-if="action !== 'show'">
                {{ action === 'create' ? 'إنشاء' : 'تحديث' }}
            </Button>
        </form>
    </div>
</template>

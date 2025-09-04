<script setup lang="ts">
import { Alert, AlertDescription, AlertTitle } from '@/components/ui/alert';
import type { AppPageProps } from '@/types';
import { usePage } from '@inertiajs/vue3';
const page = usePage<AppPageProps>();
</script>
<template>
    <div class="flex w-full items-center justify-center">
        <div class="mx-4 my-2 w-full max-w-2xl">
            <Alert v-if="page.props.session.success" variant="default">
                <Terminal class="h-4 w-4" />
                <AlertTitle>نجاح</AlertTitle>
                <AlertDescription> {{ page.props.session.success }} </AlertDescription>
            </Alert>
            <Alert v-if="page.props.session.error" variant="destructive">
                <Terminal class="h-4 w-4" />
                <AlertTitle>فشل</AlertTitle>
                <AlertDescription> {{ page.props.session.error }} </AlertDescription>
            </Alert>
        </div>
    </div>
</template>

<script setup>
import ComplexComponent from '@/Components/ComplexComponent.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    complexes: {
        type: Array,
        required: true
    }
});
</script>

<template>
    <Head title="Complexes" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between">
                <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                    Complexes
                </h2>
                <div>
                    <Link 
                        :href="`/create-complexes`" 
                        class="px-4 py-2 text-sm font-semibold text-white bg-indigo-600 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    >
                        Add
                    </Link>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div style="display: flex; flex-wrap: wrap; gap: 16px;">
                    <ComplexComponent v-for="complex in complexes" :key="complex.id" :complex="complex" />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

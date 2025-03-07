<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    complex: {
        type: Object,
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
                    Complex Details
                </h2>
                <div>
                    <Link :href="`/complexes/${complex.id}/courts`"
                        class="px-4 py-2 text-sm font-semibold text-white bg-indigo-600 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        View Courts
                    </Link>
                </div>
            </div>
        </template>

        <div class="min-h-screen bg-gray-100 dark:bg-gray-900 py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200">
                        {{ complex.name }}
                    </h2>
                </div>

                <!-- Complex Details -->
                <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
                    <p class="mb-4 text-gray-600 dark:text-gray-400">{{ complex.description }}</p>
                    <ul class="space-y-2 text-sm text-gray-700 dark:text-gray-300">
                        <li><strong>Address Line 1:</strong> {{ complex.line1 }}</li>
                        <li><strong>Address Line 2:</strong> {{ complex.line2 || 'N/A' }}</li>
                        <li><strong>City:</strong> {{ complex.city }}</li>
                        <li><strong>Country:</strong> {{ complex.country }}</li>
                        <li><strong>Latitude:</strong> {{ complex.latitude }}</li>
                        <li><strong>Longitude:</strong> {{ complex.longitude }}</li>
                    </ul>
                </div>

            </div>
        </div>

    </AuthenticatedLayout>
</template>
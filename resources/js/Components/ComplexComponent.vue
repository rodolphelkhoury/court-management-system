<script setup>
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    complex: {
        type: Object,
        required: true
    }
});
</script>

<template>
    <Link :href="`/complexes/${complex.id}`">
    <div
        class="relative border border-gray-300 p-6 rounded-lg shadow-md bg-white dark:bg-gray-800 dark:border-gray-700 transition transform hover:scale-105 hover:shadow-lg hover:border-indigo-500">
        <h2 class="text-lg font-bold text-gray-800 dark:text-gray-200">{{ complex.name }}</h2>
        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">{{ complex.description }}</p>
        <ul class="mt-4 space-y-2 text-sm text-gray-700 dark:text-gray-300">
            <li><strong>Address Line 1:</strong> {{ complex.line1 }}</li>
            <li><strong>Address Line 2:</strong> {{ complex.line2 || 'N/A' }}</li>
            <li><strong>City:</strong> {{ complex.city }}</li>
            <li><strong>Country:</strong> {{ complex.country }}</li>
            <li><strong>Latitude:</strong> {{ complex.latitude }}</li>
            <li><strong>Longitude:</strong> {{ complex.longitude }}</li>
        </ul>
    </div>
    </Link>

</template>

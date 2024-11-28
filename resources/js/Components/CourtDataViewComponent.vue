<script setup>
import { Link } from '@inertiajs/vue3'; // Import the Inertia Link component

const props = defineProps({
  court: {
    type: Object,
    required: true
  }
});
</script>

<template>
    <Link :href="`/courts/${court.id}`">
  <div 
    class="relative border border-gray-300 p-6 rounded-lg shadow-md bg-white dark:bg-gray-800 dark:border-gray-700 transition transform hover:scale-105 hover:shadow-lg hover:border-indigo-500 "
  >

    <h2 class="text-lg font-bold text-gray-800 dark:text-gray-200">{{ court.name }}</h2>
    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">{{ court.description }}</p>
    <ul class="mt-4 space-y-2 text-sm text-gray-700 dark:text-gray-300">
      <li><strong>Type:</strong> {{ court.court_type.name }}</li>
      <li><strong>Surface:</strong> {{ court.surface_type.name }}</li>
      <li><strong>Hourly Rate:</strong> ${{ court.hourly_rate }}</li>
      <li><strong>Opening Hours:</strong> {{ court.opening_time }} - {{ court.closing_time }}</li>
      <!-- <li><strong>Divisible:</strong> {{ court.divisible ? 'Yes' : 'No' }}</li> -->
    </ul>
  </div>
</Link>
</template>

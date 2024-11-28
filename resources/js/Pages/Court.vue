<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import Modal from '@/Components/Modal.vue';
import { ref } from 'vue';
import CreateSectionComponent from '@/Components/CreateSectionComponent.vue';


const props = defineProps({
    court: {
        type: Object,
        required: true
    }
});
const sections = ref(props.court.sections)
const isCustomerModalVisible = ref(false);

const sectionCreated = () => {
    isCustomerModalVisible.value = false;
    axios.get(`/courts/${props.court.id}/sections`)
        .then(response => {
            sections.value = response.data;
        })
        .catch(error => {
            console.error('Error fetching customers:', error);
        });
};

</script>

<template>

    <Head title="Court Details" />

    <Modal :show="isCustomerModalVisible" @close="isCustomerModalVisible = false">
        <CreateSectionComponent :court="court" @section-created="sectionCreated()" />
    </Modal>
    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between">
                <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                    Court Details
                </h2>
                <div class="flex space-x-4">
                    <button @click="isCustomerModalVisible = true" type="button"
                        class="px-4 py-2 text-sm font-semibold text-white bg-indigo-600 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        Add Section
                    </button>
                    <Link :href="`/courts/${court.id}/reservations`"
                        class="inline-flex items-center px-4 py-2 text-sm font-semibold text-white bg-indigo-600 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    View Reservations
                    </Link>
                </div>
            </div>
        </template>

        <div class="min-h-screen bg-gray-100 dark:bg-gray-900 py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200">
                        {{ court.name }}
                    </h2>
                </div>

                <!-- Court Details -->
                <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6 mb-6">
                    <p class="mb-4 text-gray-600 dark:text-gray-400">{{ court.description }}</p>
                    <ul class="space-y-2 text-sm text-gray-700 dark:text-gray-300">
                        <li><strong>Hourly Rate:</strong> ${{ court.hourly_rate }}</li>
                        <!-- <li><strong>Divisible:</strong> {{ court.divisible ? 'Yes' : 'No' }}</li> -->
                        <!-- <li><strong>Max Divisions:</strong> {{ court.max_divisions || 'N/A' }}</li> -->
                        <li><strong>Opening Time:</strong> {{ court.opening_time }}</li>
                        <li><strong>Closing Time:</strong> {{ court.closing_time }}</li>
                        <li><strong>Court Type:</strong> {{ court.court_type.name }}</li>
                        <li><strong>Surface Type:</strong> {{ court.surface_type.name }}</li>
                    </ul>
                </div>

                <!-- Court Sections -->
                <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">
                        Sections
                    </h3>
                    <div v-if="sections.length > 0" class="space-y-4">
                        <div v-for="section in sections" :key="section.id"
                            class="bg-gray-50 dark:bg-gray-700 p-4 rounded-md shadow-sm">
                            <h4 class="font-medium text-gray-800 dark:text-gray-200">Section: {{ section.name }}</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400"><strong>Hourly Rate:</strong> ${{
                                section.hourly_rate
                                }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400"><strong>Status:</strong> Available</p>
                        </div>
                    </div>
                    <div v-else>
                        <p class="text-gray-600 dark:text-gray-400">No sections available for this court.</p>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

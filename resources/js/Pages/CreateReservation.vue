<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import Modal from '@/Components/Modal.vue';
import CreateCustomerComponent from '@/Components/CreateCustomerComponent.vue';

const props = defineProps({
    court: {
        type: Object,
        required: true
    },
    sections: {
        type: Array,
        required: true
    },
    customers: {
        type: Array,
        required: true
    },
});

const sections = ref(props.sections);
const customers = ref(props.customers);
const isCustomerModalVisible = ref(false);

const form = useForm({
    section_id: null,
    customer_id: null,
    reservation_date: '',
    start_time: '',
    end_time: '',
    is_canceled: false,
    is_no_show: false,
});

const handleSubmit = () => {
    form.post(route('reservation.store', { court: props.court.id }), {
        onSuccess: () => {
            // Success handler
        },
        onError: (errors) => {
            alert(errors.message);
        }
    });
};

const customerCreated = () => {
    isCustomerModalVisible.value = false;
    axios.get('/customers')
        .then(response => {
            customers.value = response.data; // Update the customers ref with the new data
        })
        .catch(error => {
            console.error('Error fetching customers:', error);
        });
};

</script>

<template>

    <Head title="Create Reservation" />

    <Modal :show="isCustomerModalVisible" @close="isCustomerModalVisible = false">
        <CreateCustomerComponent @customer-created="customerCreated()" />
    </Modal>

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between">
                <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                    Create Reservation
                </h2>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="max-w-4xl mx-auto p-6 bg-white dark:bg-gray-800 shadow-lg rounded-lg">

                    <form @submit.prevent="handleSubmit" class="mt-6 space-y-4">

                        <div>
                            <label for="customer_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Customer
                            </label>
                            <div class="flex items-center gap-4">
                                <select id="customer_id" v-model="form.customer_id"
                                    class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200"
                                    required>
                                    <option disabled value="">Select Customer</option>
                                    <option v-for="customer in customers" :key="customer.id" :value="customer.id">
                                        {{ customer.name }}
                                    </option>
                                </select>
                                <button @click="isCustomerModalVisible = true" type="button"
                                    class="px-4 py-2 text-sm font-semibold text-white bg-indigo-600 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                    New
                                </button>
                            </div>
                        </div>

                        <!-- Reservation Date -->
                        <div>
                            <label for="reservation_date"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Reservation
                                Date</label>
                            <input id="reservation_date" v-model="form.reservation_date" type="date"
                                class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200"
                                required />
                        </div>

                        <!-- Start Time and End Time -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="start_time"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Start
                                    Time</label>
                                <input id="start_time" v-model="form.start_time" type="time"
                                    class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200"
                                    required />
                            </div>
                            <div>
                                <label for="end_time"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">End
                                    Time</label>
                                <input id="end_time" v-model="form.end_time" type="time"
                                    class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200"
                                    required />
                            </div>
                        </div>

                        <!-- Section Selection -->
                        <div>
                            <label for="section_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Section
                            </label>
                            <select id="section_id" v-model="form.section_id"
                                class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">
                                <option disabled value="">Select Section</option>
                                <option v-for="section in sections" :key="section.id" :value="section.id">
                                    {{ section.name }}
                                </option>
                            </select>
                        </div>

                        <!-- Submit Button -->
                        <div class="mt-6">
                            <button type="submit"
                                class="w-full px-4 py-2 text-sm font-semibold text-white bg-indigo-600 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                Create Reservation
                            </button>
                        </div>
                    </form>

                    <div class="mt-4 text-center">
                        <Link :href="`/courts/${court.id}/reservations`"
                            class="text-sm text-indigo-600 hover:underline">Back to
                        Reservation List</Link>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

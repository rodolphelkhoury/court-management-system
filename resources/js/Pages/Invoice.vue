<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import axios from 'axios';
import { ref } from 'vue';

const props = defineProps({
    invoice: {
        type: Object,
        required: true,
    },
});

const invoice = ref(props.invoice)

const markAsPaid = () => {
    let data;
    if (invoice.value.status == 'paid') {
        data = { status: 'unpaid' }
    } else {
        data = { status: 'paid' }
    }
    axios
        .put(`/reservations/${invoice.value.reservation_id}/invoices/${invoice.value.id}/update-status`, {
            status: data.status
        })
        .then(response => {
            invoice.value.status = response.data.status;
            invoice.value.paid_at = response.data.paid_at ? formatDate(response.data.paid_at) : null
        })
        .catch(error => {
            console.error('Error canceling invoice:', error);
        });
};

const generatePDF = () => {
    const url = `/reservations/${invoice.value.reservation_id}/invoices/${invoice.value.id}/generate-pdf`;
    window.open(url, '_blank'); // Open the URL in a new tab
};



// Helper method to format dates
const formatDate = (dateString) => {
    if (!dateString) return "N/A";
    const date = new Date(dateString);
    const day = String(date.getDate()).padStart(2, '0'); // Ensure two-digit day
    const month = String(date.getMonth() + 1).padStart(2, '0'); // Month is 0-indexed
    const year = date.getFullYear();
    return `${month}/${day}/${year}`;
};
</script>

<template>

    <Head title="Invoice Details" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between">
                <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                    Invoice Details
                </h2>
                <div class="flex space-x-4">
                    <button @click="markAsPaid" type="button" :class="[
                        'px-4 py-2 text-sm font-semibold text-white rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2',
                        invoice.status === 'paid'
                            ? 'bg-red-600 hover:bg-red-700 focus:ring-red-500'
                            : 'bg-green-600 hover:bg-green-700 focus:ring-green-500'
                    ]">
                        {{ invoice.status === 'paid' ? 'Mark as UnPaid' : 'Mark as Paid' }}
                    </button>

                    <button @click="generatePDF" type="button"
                        class="px-4 py-2 text-sm font-semibold text-white bg-indigo-600 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        Generate PDF
                    </button>
                </div>
            </div>
        </template>

        <div class="min-h-screen bg-gray-100 dark:bg-gray-900 py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200">
                        Invoice #{{ invoice.id }}
                    </h2>
                </div>

                <!-- Invoice Details -->
                <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6 mb-6">
                    <ul class="space-y-2 text-sm text-gray-700 dark:text-gray-300">
                        <li><strong>Reservation ID:</strong> {{ invoice.reservation_id }}</li>
                        <li><strong>Customer ID:</strong> {{ invoice.customer_id }}</li>
                        <li><strong>Amount:</strong> ${{ invoice.amount }}</li>
                        <li><strong>Status:</strong> {{ invoice.status }}</li>
                        <li><strong>Due Date:</strong> {{ formatDate(invoice.due_date) }}</li>
                        <li v-if="invoice.paid_at"><strong>Paid At:</strong> {{ formatDate(invoice.paid_at) }}</li>
                        <li v-else class="text-red-600"><strong>Paid At:</strong> Not Paid</li>
                    </ul>
                </div>



                <!-- Customer Details -->
                <div v-if="invoice.customer" class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">
                        Customer Details
                    </h3>
                    <ul class="space-y-2 text-sm text-gray-700 dark:text-gray-300">
                        <li><strong>Name:</strong> {{ invoice.customer.name }}</li>
                        <li><strong>Phone:</strong> {{ invoice.customer.customer.phone_number }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

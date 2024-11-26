<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import axios from 'axios';
import { computed, ref } from 'vue';

const props = defineProps({
    reservation: {
        type: Object,
        required: true,
    },
});

const reservation = ref(props.reservation)

const now = new Date();

const isReservationPast = computed(() => {
    const reservationDate = new Date(`${props.reservation.reservation_date}T${props.reservation.start_time}`);
    return reservationDate < now;
});

const markNoShow = () => {
    axios
        .put(`/reservations/${reservation.value.id}/update-is-no-show-status`, {
            is_no_show: !reservation.value.is_no_show
        })
        .then(response => {
            // Update the reactive state without overwriting it completely
            reservation.value.is_no_show = !reservation.value.is_no_show;
        })
        .catch(error => {
            console.error('Error updating no-show status:', error);
        });
};

const cancelReservation = () => {
    axios
        .put(`/reservations/${reservation.value.id}/update-is-canceled-status`, {
            is_canceled: !reservation.value.is_canceled
        })
        .then(response => {
            // Update the reactive state without overwriting it completely
            reservation.value.is_canceled = !reservation.value.is_canceled;
        })
        .catch(error => {
            console.error('Error canceling reservation:', error);
        });
};

const handleInvoiceClick = () => {
    router.visit(route('reservation.invoice', { reservation: reservation.value.id }));
};

</script>

<template>

    <Head title="Reservation Details" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between">
                <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                    Reservation Details
                </h2>
                <div class="flex space-x-4">
                    <!-- Button for No-Show or Attendance -->
                    <button v-if="isReservationPast" @click="markNoShow" type="button"
                        class="px-4 py-2 text-sm font-semibold text-white bg-red-600 rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                        {{ reservation.is_no_show ? "Mark Attendance" : "Flag as No Show" }}
                    </button>
                    <!-- Button for Cancel Reservation or Undo -->
                    <button v-else @click="cancelReservation" type="button"
                        class="px-4 py-2 text-sm font-semibold text-white bg-red-600 rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                        {{ reservation.is_canceled ? "Undo Cancellation" : "Cancel Reservation" }}
                    </button>
                    <!-- Invoice Button -->
                    <button @click="handleInvoiceClick" type="button"
                        class="px-4 py-2 text-sm font-semibold text-white bg-indigo-600 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        Get Invoice
                    </button>
                </div>
            </div>
        </template>

        <div class="min-h-screen bg-gray-100 dark:bg-gray-900 py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200">
                        Reservation for {{ reservation.court.name }}
                    </h2>
                </div>

                <!-- Reservation Details -->
                <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
                    <ul class="space-y-2 text-sm text-gray-700 dark:text-gray-300">
                        <li><strong>Customer Name:</strong> {{ reservation.customer.name }}</li>
                        <li><strong>Reservation Date:</strong> {{ reservation.reservation_date }}</li>
                        <li><strong>Start Time:</strong> {{ reservation.start_time }}</li>
                        <li><strong>End Time:</strong> {{ reservation.end_time }}</li>
                        <li><strong>Total Price:</strong> ${{ reservation.total_price }}</li>
                        <li><strong>Cancellation Status:</strong> {{ reservation.is_canceled ? "Canceled" : "Active" }}
                        </li>
                        <li><strong>No-Show Status:</strong> {{ reservation.is_no_show ? "No Show" : "Attended" }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';

// Emit the event to notify parent components
const emit = defineEmits(['customerCreated']);

// Initial form setup for Customer
const form = useForm({
    name: '',
    email: '',
    phone_number: '',
});

const handleSubmit = () => {
    form.post(route('customer.store'), {
        onSuccess: () => {
            // Emit event when customer is successfully created
            emit('customerCreated', form.data());
            // Optionally clear the form
            form.reset();
        },
        onError: (errors) => {
            // Handle errors
            console.log(errors);
        }
    });
};
</script>

<template>
    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto p-6 bg-white dark:bg-gray-800 shadow-lg rounded-lg">
                <h3 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                    Create Customer
                </h3>

                <form @submit.prevent="handleSubmit" class="mt-6 space-y-4">
                    <div>
                        <label for="name"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                        <input id="name" v-model="form.name" type="text"
                            class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200"
                            required />
                    </div>

                    <div>
                        <label for="email"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                        <input id="email" v-model="form.email" type="email"
                            class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200"
                            required />
                    </div>

                    <div>
                        <label for="phone_number"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Phone Number</label>
                        <input id="phone_number" v-model="form.phone_number" type="tel"
                            class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200"
                            required />
                    </div>

                    <div class="mt-6">
                        <button type="submit"
                            class="w-full px-4 py-2 text-sm font-semibold text-white bg-indigo-600 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            Create Customer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

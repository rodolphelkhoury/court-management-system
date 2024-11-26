<script setup>
import { useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

// Initial form setup
const form = useForm({
    name: '',
    description: '',
    line1: '',
    line2: '',
    city: '',
    country: '',
    latitude: '',
    longitude: '',
});

const handleSubmit = () => {
    form.post(route('complex.store'), {
        onSuccess: () => {
            // Handle success, e.g., redirect or show a success message
        },
        onError: (errors) => {
            // Handle errors
            console.log(errors);
        }
    });
};
</script>

<template>
    <Head title="Create Complex" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between">
                <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                    Create Complex
                </h2>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="max-w-4xl mx-auto p-6 bg-white dark:bg-gray-800 shadow-lg rounded-lg">

                    <form @submit.prevent="handleSubmit" class="mt-6 space-y-4">

                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Name
                            </label>
                            <input id="name" v-model="form.name" type="text"
                                class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200"
                                required />
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Description
                            </label>
                            <textarea id="description" v-model="form.description" rows="3"
                                class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200"
                                required></textarea>
                        </div>

                        <div>
                            <label for="line1" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Address Line 1
                            </label>
                            <input id="line1" v-model="form.line1" type="text"
                                class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200"
                                required />
                        </div>

                        <div>
                            <label for="line2" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Address Line 2 (Optional)
                            </label>
                            <input id="line2" v-model="form.line2" type="text"
                                class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200" />
                        </div>

                        <div>
                            <label for="city" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                City
                            </label>
                            <input id="city" v-model="form.city" type="text"
                                class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200"
                                required />
                        </div>

                        <div>
                            <label for="country" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Country
                            </label>
                            <input id="country" v-model="form.country" type="text"
                                class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200"
                                required />
                        </div>

                        <div>
                            <label for="latitude" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Latitude
                            </label>
                            <input id="latitude" v-model="form.latitude" type="number" step="0.000001"
                                class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200" />
                        </div>

                        <div>
                            <label for="longitude" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Longitude
                            </label>
                            <input id="longitude" v-model="form.longitude" type="number" step="0.000001"
                                class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200" />
                        </div>

                        <div class="mt-6">
                            <button type="submit"
                                class="w-full px-4 py-2 text-sm font-semibold text-white bg-indigo-600 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                Create Complex
                            </button>
                        </div>
                    </form>

                    <div class="mt-4 text-center">
                        <Link href="/complexes" class="text-sm text-indigo-600 hover:underline">Back to Complex List</Link>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

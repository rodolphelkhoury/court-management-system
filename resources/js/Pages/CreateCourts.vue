<script setup>
import { ref, watch } from 'vue';
import { useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    complexes: {
        type: Array,
        required: true
    },
    court_types: {
        type: Array,
        required: true
    },
    surface_types: {
        type: Array,
        required: true
    },
});

const complexes = ref(props.complexes);
const surfaceTypes = ref(props.surface_types);
const courtTypes = ref(props.court_types);

// Initial form setup
const form = useForm({
    name: '',
    description: '',
    complex_id: null,
    surface_type_id: null,
    court_type_id: null,
    hourly_rate: '',
    opening_time: '',
    closing_time: '',
    divisible: false,
    max_divisions: 0,
});

let selectedCourtTypeId = null;

watch(() => form.court_type_id, (newCourtTypeId) => {
    if (newCourtTypeId != selectedCourtTypeId) {
        const selectedCourtType = courtTypes.value ? courtTypes.value.find(c => c.id === newCourtTypeId) : null;
        surfaceTypes.value = selectedCourtType.surface_types || [];
        selectedCourtTypeId = newCourtTypeId;
    }
});

const handleSubmit = () => {
    form.post(route('court.store'), {
        onSuccess: () => {
            // handle success, maybe redirect or show a success message
        },
        onError: (errors) => {
            // handle errors, show validation feedback if needed
            console.log(errors);
        }
    });
};


</script>



<template>

    <Head title="Create Court" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between">
                <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                    Create Court
                </h2>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="max-w-4xl mx-auto p-6 bg-white dark:bg-gray-800 shadow-lg rounded-lg">

                    <form @submit.prevent="handleSubmit" class="mt-6 space-y-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Court
                                Name</label>
                            <input id="name" v-model="form.name" type="text"
                                class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200"
                                required />
                        </div>

                        <div>
                            <label for="description"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                            <textarea id="description" v-model="form.description" rows="3"
                                class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200"
                                required></textarea>
                        </div>

                        <div>
                            <label for="complex"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Complex</label>
                            <select id="complex" v-model="form.complex_id"
                                class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200"
                                required>
                                <option disabled value="">Select Complex</option>
                                <option v-for="complex in complexes" :key="complex.id" :value="complex.id">
                                    {{ complex.name }}
                                </option>
                            </select>
                        </div>

                        <div>
                            <label for="court_type"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Court
                                Type</label>
                            <select id="court_type" v-model="form.court_type_id"
                                class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200"
                                required>
                                <option disabled value="">Select Court Type</option>
                                <option v-for="courtType in courtTypes" :key="courtType.id" :value="courtType.id">
                                    {{ courtType.name }}
                                </option>
                            </select>
                        </div>

                        <div>
                            <label for="surface_type"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Surface
                                Type</label>
                            <select id="surface_type" v-model="form.surface_type_id"
                                class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200"
                                required>
                                <option disabled value="">Select Surface Type</option>
                                <option v-for="surface in surfaceTypes" :key="surface.id" :value="surface.id">
                                    {{ surface.name }}
                                </option>
                            </select>
                        </div>


                        <div>
                            <label for="hourly_rate"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Hourly
                                Rate</label>
                            <input id="hourly_rate" v-model="form.hourly_rate" type="number" step="0.5" min="0"
                                class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200"
                                required />
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="opening_time"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Opening
                                    Time</label>
                                <input id="opening_time" v-model="form.opening_time" type="time"
                                    class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200"
                                    required />
                            </div>

                            <div>
                                <label for="closing_time"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Closing
                                    Time</label>
                                <input id="closing_time" v-model="form.closing_time" type="time"
                                    class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200"
                                    required />
                            </div>
                        </div>

                        <!-- <div class="flex items-center space-x-3">
                            <input id="divisible" v-model="form.divisible" type="checkbox"
                                class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500" />
                            <label for="divisible" class="text-sm text-gray-700 dark:text-gray-300">Divisible</label>
                        </div> -->

                        <div v-if="form.divisible">
                            <label for="max_divisions" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Max Divisions</label>
                            <input id="max_divisions" v-model="form.max_divisions" type="number" min="0"
                                class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200"
                                required />
                        </div>

                        <div class="mt-6">
                            <button type="submit"
                                class="w-full px-4 py-2 text-sm font-semibold text-white bg-indigo-600 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                Create Court
                            </button>
                        </div>
                    </form>

                    <div class="mt-4 text-center">
                        <Link href="/courts" class="text-sm text-indigo-600 hover:underline">Back to Court List</Link>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
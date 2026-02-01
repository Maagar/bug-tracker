<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
    ticket: Object,
});

const form = useForm({
    title: props.ticket.title,
    description: props.ticket.description,
    status: props.ticket.status,
});

const submit = () => {
    form.patch(route('tickets.update', props.ticket.id));
};
</script>

<template>
    <Head title="Edytuj zgłoszenie" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edytuj zgłoszenie</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    
                    <form @submit.prevent="submit" class="space-y-6">
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tytuł</label>
                            <input v-model="form.title" type="text" 
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <div v-if="form.errors.title" class="text-red-500 text-sm mt-1">{{ form.errors.title }}</div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Status</label>
                            <select v-model="form.status" 
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="open">Otwarty (Open)</option>
                                <option value="in_progress">W trakcie (In Progress)</option>
                                <option value="closed">Zamknięty (Closed)</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Opis</label>
                            <textarea v-model="form.description" rows="4"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                            <div v-if="form.errors.description" class="text-red-500 text-sm mt-1">{{ form.errors.description }}</div>
                        </div>

                        <div class="flex items-center gap-4">
                            <button type="submit" :disabled="form.processing"
                                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                                Zapisz zmiany
                            </button>
                            
                            <Link :href="route('tickets.index')" class="text-gray-600 underline hover:text-gray-900">
                                Anuluj
                            </Link>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
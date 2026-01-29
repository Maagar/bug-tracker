<script setup>
// Importy komponentów UI (dostarczone przez Breeze)
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

// Odbieramy dane z PHP (props = parametry funkcji w Compose)
defineProps({
    tickets: Array
});

// Stan formularza (reaktywny)
const form = useForm({
    title: '',
    description: '',
});

// Funkcja wysyłania (submit)
const submit = () => {
    form.post(route('tickets.store'), {
        onSuccess: () => form.reset(), // Wyczyść po sukcesie
    });
};
</script>

<template>
    <Head title="Zgłoszenia" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Zgłoszenia</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <form @submit.prevent="submit" class="space-y-4">
                        <div>
                            <input v-model="form.title" type="text" placeholder="Tytuł błędu" 
                                class="w-full border-gray-300 rounded-md shadow-sm">
                            <div v-if="form.errors.title" class="text-red-500 text-sm">{{ form.errors.title }}</div>
                        </div>

                        <div>
                            <textarea v-model="form.description" placeholder="Opis..." 
                                class="w-full border-gray-300 rounded-md shadow-sm"></textarea>
                        </div>

                        <button type="submit" :disabled="form.processing"
                            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                            Wyślij
                        </button>
                    </form>
                </div>

                <div class="bg-white shadow sm:rounded-lg p-6">
                    <div v-for="ticket in tickets" :key="ticket.id" class="border-b py-4 last:border-b-0">
                        <h3 class="font-bold text-lg">{{ ticket.title }}</h3>
                        <p class="text-gray-600">{{ ticket.description }}</p>
                        <span class="text-xs bg-gray-200 px-2 py-1 rounded">Status: {{ ticket.status }}</span>
                    </div>
                    <div v-if="tickets.length === 0" class="text-gray-500">Brak zgłoszeń.</div>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>
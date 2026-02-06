<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, Link, router } from '@inertiajs/vue3';
import debounce from 'lodash/debounce'
import { ref, watch } from 'vue';

const props = defineProps({
    tickets: Array,
    filters: Object
});

const form = useForm({
    title: '',
    description: '',
});

const search = ref(props.filters.search || '')
const status = ref(props.filters.status || '')

watch(
    [search, status],
    debounce(() => {
        router.get(
            route('tickets.index'),
            { search: search.value, status: status.value },
            {
                preserveState: true,
                replace: true,
                preserveScroll: true
            }
        )
    }, 500)
)

const submit = () => {
    form.post(route('tickets.store'), {
        onSuccess: () => form.reset(),
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

                <div class="flex gap-4 mb-4">
                    <input v-model="search" type="text" placeholder="Szukaj ticketu..."
                        class="border-gray-300 rounded-md shadow-sm w-full sm:w-64">

                    <select v-model="status" class="border-gray-300 rounded-md shadow-sm w-full sm:w=48">
                        <option value="">Wszystkie statusy</option>
                        <option value="open">Otwarty</option>
                        <option value="in_progress">W trakcie</option>
                        <option value="closed">Zamknięty</option>
                    </select>
                </div>

                <div class="bg-white shadow sm:rounded-lg overflow-hidden">
                    <div v-for="ticket in tickets" :key="ticket.id"
                        class="p-6 border-b border-gray-200 flex justify-between items-center transition hover:bg-gray-50">

                        <div>
                            <h3 class="font-bold text-lg">{{ ticket.title }}</h3>
                            <p class="text-gray-600">{{ ticket.description }}</p>
                            <div class="text-xs text-gray-500 mt-2 flex gap-4">
                                <span>
                                    Autor: <span class="font-semibold text-gray-700">{{ ticket.user.name }}</span>
                                </span>

                                <span v-if="ticket.assignee">
                                    Przypisany do: <span class="font-bold text-blue-600">{{ ticket.assignee.name
                                        }}</span>
                                </span>
                                <span v-else class="text-gray-400 italic">
                                    --Nieprzypisany--
                                </span>
                            </div>
                        </div>

                        <div class="flex items-center space-x-4"> <span
                                class="px-3 py-1 text-xs rounded-full uppercase font-semibold"
                                :class="ticket.status === 'open' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'">
                                {{ ticket.status }}
                            </span>
                            <Link :href="route('tickets.update', ticket.id)" method="patch"
                                :data="{ assignee_id: $page.props.auth.user.id }" as="button" preserve-scroll
                                class="text-teal-600 hover:text-teal-900 font-bold text-sm border border-teal-600 px-2 py-1 rounded hover:bg-teal-50 transition">
                                Przypisz do mnie
                            </Link>
                            <Link :href="route('tickets.edit', ticket.id)"
                                class="text-indigo-600 hover:text-indigo-900 font-bold text-sm">
                                Edytuj
                            </Link>

                            <Link :href="route('tickets.destroy', ticket.id)" method="delete" as="button"
                                preserve-scroll class="text-red-600 hover:text-red-900 font-bold text-sm">
                                Usuń
                            </Link>

                        </div>

                    </div>

                    <div v-if="tickets.length === 0" class="p-6 text-center text-gray-500">
                        Brak zgłoszeń.
                    </div>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>
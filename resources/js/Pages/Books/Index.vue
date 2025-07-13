<script setup lang="ts">
    import { Book, Paginated } from '@/types/index'
    import { Head, Link } from '@inertiajs/vue3';

    defineProps<{
    books: Paginated<Book>
    }>()
</script>

<template>
    <Head title="Books" />
    <div class="p-6">
        <h1 class="text-2xl font-bold mb-4">📚 Book List</h1>

        <ul v-if="books.data.length" class="space-y-2 mb-6">
        <li
            v-for="book in books.data"
            :key="book.id"
            class="border p-4 rounded bg-white shadow"
        >
            <p class="text-lg font-semibold">{{ book.title }}</p>
            <p class="text-sm text-gray-600">by {{ book.author }}</p>
        </li>
        </ul>

        <div v-else class="text-gray-500">No books found.</div>

        <!-- Pagination Links -->
        <div class="flex flex-wrap justify-center gap-2">
        <template v-for="(link, index) in books.links" :key="index">
            <component
            :is="link.url ? Link : 'span'"
            :href="link.url || undefined"
            v-html="link.label"
            class="px-3 py-1 rounded text-sm"
            :class="{
                'bg-blue-500 text-white': link.active,
                'bg-gray-100 text-gray-700': !link.active && link.url,
                'text-gray-400 cursor-not-allowed': !link.url
            }"
            />
        </template>
        </div>
    </div>
</template>
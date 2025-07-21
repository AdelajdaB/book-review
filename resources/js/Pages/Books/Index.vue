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

        <ul v-if="books.data.length" class="space-y-4 mb-6">
        <li
            v-for="book in books.data"
            :key="book.id"
            class="border p-4 rounded bg-white shadow"
        >
            <div class="flex gap-4">
                <!-- Book Cover Image -->
                <div class="flex-shrink-0">
                    <img 
                        v-if="book.cover_url" 
                        :src="book.cover_url" 
                        :alt="`Cover of ${book.title}`"
                        class="w-24 h-36 object-cover rounded shadow"
                        loading="lazy"
                    />
                    <div 
                        v-else 
                        class="w-24 h-36 bg-gray-200 rounded shadow flex items-center justify-center"
                    >
                        <span class="text-gray-400 text-xs text-center">No Cover</span>
                    </div>
                </div>
                
                <!-- Book Details -->
                <div class="flex-1 min-w-0">
                    <p class="text-lg font-semibold text-gray-900 mb-2">{{ book.title }}</p>
                    <p class="text-sm text-gray-600 mb-2">by {{ book.author }}</p>
                    <p class="text-gray-700 text-sm mb-2 line-clamp-3">{{ book.description }}</p>
                    <div class="flex items-center">
                        <span class="text-yellow-500 mr-1">★</span>
                        <span class="text-sm font-medium">{{ book.rating }} / 5</span>
                    </div>
                </div>
            </div>
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

<style scoped>
.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
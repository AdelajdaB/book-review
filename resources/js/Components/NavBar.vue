<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
import { useDarkMode } from '@/Composables/DarkMode'

const { isDark, toggleDark } = useDarkMode()
</script>

<template>
  <nav class="flex items-center justify-between px-6 py-4 bg-white dark:bg-gray-900 shadow">
    <!-- Left: Logo -->
    <Link href="/" class="text-xl font-bold text-blue-600 dark:text-blue-400">
      BookReview
    </Link>

    <!-- Center: Navigation Links -->
    <div class="flex space-x-4">
      <Link
        href="/books"
        class="text-gray-700 dark:text-gray-200 hover:text-blue-600 dark:hover:text-blue-400 font-medium"
      >
        Books
      </Link>
      <Link
        href="/about"
        class="text-gray-700 dark:text-gray-200 hover:text-blue-600 dark:hover:text-blue-400 font-medium"
      >
        About
      </Link>
    </div>

    <!-- Right: Dark Mode Toggle -->
    <button
      @click="toggleDark"
      class="px-3 py-1 rounded text-sm font-medium bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white hover:bg-gray-300 dark:hover:bg-gray-600 transition"
    >
      {{ isDark ? '☀️ Light Mode' : '🌙 Dark Mode' }}
    </button>
  </nav>
</template>

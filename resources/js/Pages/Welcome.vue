<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

defineOptions({
  layout: AuthenticatedLayout,
})
</script>

<template>
  <Head title="Home" />

  <div class="min-h-screen bg-gray-50 basic-theme flex flex-col items-center justify-center px-4 py-12">
    <div class="max-w-2xl text-center">
      <h1 class="text-4xl md:text-5xl font-bold text-gray-800 mb-4">
        📚 Welcome to BookReview
      </h1>
      <p class="text-lg text-gray-600 mb-8">
        Discover, review, and explore books with AI-generated insights.
        Whether you're a reader or a writer — we've got something for you.
      </p>

      <div class="flex justify-center space-x-4">
        <Link
          href="/books"
          class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow transition"
        >
          Browse Books
        </Link>
        <!-- <Link
          href="/admin/books"
          class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-lg shadow transition"
        >
          Admin Panel
        </Link> -->
      </div>
    </div>

    <div class="mt-16 max-w-4xl grid gap-8 grid-cols-1 md:grid-cols-2">
      <div class="p-6 bg-white rounded-xl shadow">
        <h2 class="text-xl font-semibold mb-2">🔍 Smart Descriptions</h2>
        <p class="text-gray-600">
          We use AI to generate book summaries and reviews to give you fast, insightful overviews.
        </p>
      </div>

      <div class="p-6 bg-white rounded-xl shadow">
        <h2 class="text-xl font-semibold mb-2">✍️ Author-Centric</h2>
        <p class="text-gray-600">
          Easily add your books and let our system enhance them with high-quality content.
        </p>
      </div>

      <div class="p-6 bg-white rounded-xl shadow">
        <h2 class="text-xl font-semibold mb-2">📈 Continuous Updates</h2>
        <p class="text-gray-600">
          The library grows as new books are imported and enriched with up-to-date information.
        </p>
      </div>

      <div class="p-6 bg-white rounded-xl shadow">
        <h2 class="text-xl font-semibold mb-2">🧠 AI-Powered Reviews</h2>
        <p class="text-gray-600">
          Automated reviews provide context, sentiment, and detail beyond the basics.
        </p>
      </div>
    </div>
  </div>
</template>

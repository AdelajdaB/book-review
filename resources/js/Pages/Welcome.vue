<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import Button from '@/Components/Button.vue';
import Headline from '@/Components/Headline.vue';
import Text from '@/Components/Text.vue';

defineOptions({
  layout: AuthenticatedLayout,
})
</script>

<template>
  <Head title="Home" />

  <div class="min-h-screen bg-gray-50 basic-theme flex flex-col items-center justify-center px-4 py-12">
    <div class="max-w-2xl text-center">
      <Headline variant="h1" title="📚 Welcome to BookReview" />

      <Text variant="p" class="pb-4">
        Discover, review, and explore books with AI-generated insights.
        Whether you're a reader or a writer — we've got something for you.
      </Text>

      <div class="flex justify-center space-x-4">
        <Button link="/books" title="Browse Books" />
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
        <Headline variant="h2" title="🔍 Smart Descriptions" />
        <Text variant="p">
          We use AI to generate book summaries and reviews to give you fast, insightful overviews.
        </Text>
      </div>

      <div class="p-6 bg-white rounded-xl shadow">
        <Headline variant="h2" title="✍️ Author-Centric" />
        <Text variant="p">
          Easily add your books and let our system enhance them with high-quality content.
        </Text>
      </div>

      <div class="p-6 bg-white rounded-xl shadow">
        <Headline variant="h2" title="📈 Continuous Updates" />
        <Text variant="p">
          The library grows as new books are imported and enriched with up-to-date information.
        </Text>
      </div>

      <div class="p-6 bg-white rounded-xl shadow">
        <Headline variant="h2" title="🧠 AI-Powered Review" />
        <Text variant="p">
          Automated reviews provide context, sentiment, and detail beyond the basics.
        </Text>
      </div>
    </div>
  </div>
</template>

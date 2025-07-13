<script setup lang="ts">
function toggleDarkMode() {
  const html = document.documentElement;
  const isDark = html.classList.contains('dark');

  if (isDark) {
    html.classList.remove('dark');
    localStorage.setItem('theme', 'light');
  } else {
    html.classList.add('dark');
    localStorage.setItem('theme', 'dark');
  }
}
</script>

<template>
  <button @click="toggleDarkMode" class="px-3 py-1 rounded">
    🌓 Toggle Dark Mode
  </button>
</template>

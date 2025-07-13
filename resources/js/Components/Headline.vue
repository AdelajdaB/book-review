<script setup lang="ts">
    defineProps<{
        variant: string,
        title: string
    }>();
</script>

<template>
    <variant :class="[
        variant === 'h1' ? 'text-4xl md:text-5xl' :
        variant === 'h2' ? 'text-xl md:text-2xl' :
        variant === 'h3' ? 'text-lg' :
        variant === 'h4' ? 'text-md' :
        variant === 'h5' ? 'text-sm' :
        variant === 'h6' ? 'text-xs' :
        'text-base',
        'font-semibold text-content dark:text-white mb-2'
        ]"
    >
        {{ title }}
    </variant>
</template>
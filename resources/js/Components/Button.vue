<script setup lang="ts">
    import { Link } from '@inertiajs/vue3';

    defineProps<{
        link: string,
        title: string
    }>();
</script>   

<template>
    <Link
    :href="link"
    class="group relative px-6 py-3 bg-transparent text-primary border-2 border-primary font-semibold shadow transition hover:bg-primary hover:text-white z-10 dark:border-mute dark:text-white dark:hover:bg-mute dark:hover:text-white"
    >
        <div
            class="absolute top-1/2 left-1/2 w-3/4 h-[130%] border border-secondary -translate-x-1/2 -translate-y-1/2 transition-colors duration-300 ease-in-out group-hover:border-primary group-hover:border-2 z-0 dark:border-mute dark:group-hover:border-mute"
        ></div>
        {{ title }}
    </Link>

</template>

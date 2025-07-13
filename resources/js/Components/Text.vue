<script setup lang="ts">
const props = defineProps<{
  variant: string
  class?: string
}>()

const tag = props.variant || 'span'
</script>

<template>
  <component 
    :is="tag" 
    :class="['text-md text-content dark:text-white', props.class]"
  >
    <slot />
  </component>
</template>

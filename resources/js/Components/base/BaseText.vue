<template>
  <p class="opacity-75" :class="size === 'small' ? 'text-sm' : 'text-lg'">
    <slot />
  </p>
</template>

<script setup>
defineProps({
  size: {
    type: String,
    default: "",
  },
});
</script>

<template>
  <component :is="tag" class="relative w-24 h-24">
    <button
      @click="$emit('remove', file)"
      class="absolute bg-red-500 text-white p-0.5 left-0"
      aria-label="Remove"
    >
      <IconClose class="w-2 h-2" />
    </button>
    <img
      class="h-24 w-24 object-cover"
      :src="file.url"
      :alt="file.file.name"
      :title="file.file.name"
    />
  </component>
</template>

<script setup>
defineProps({
  file: { type: Object, required: true },
  tag: { type: String, default: "li" },
});

defineEmits(["remove"]);

import IconClose from "@/components/svg/IconClose.vue";
</script>

<template>
  <div>
    <label
      class="block font-display text-sm"
      :class="$slots.helper ? 'mb-1' : 'mb-2'"
      ><slot
    /></label>
    <p class="text-xs mb-4 lg:w-3/4 opacity-50" v-if="$slots.helper">
      <slot name="helper" />
    </p>
    <QuillEditor
      theme="snow"
      class="bg-white"
      :content="modelValue"
      contentType="html"
      v-model:content="body"
    />
  </div>
</template>

<script setup>
import { ref, watch } from "vue";
// components
import { QuillEditor } from "@vueup/vue-quill";
import "@vueup/vue-quill/dist/vue-quill.snow.css";

const body = ref("");

defineProps({
  modelValue: {
    type: String,
    default: "",
  },
});

const emit = defineEmits(["update:modelValue"]);

watch(body, async (newVal, oldVal) => {
  emit("update:modelValue", newVal);
});
</script>

<style>
.ql-toolbar.ql-snow {
  @apply rounded-t-md;
}

.ql-container.ql-snow {
  @apply rounded-b-md;
}
.ql-editor {
  min-height: 200px;
}
</style>

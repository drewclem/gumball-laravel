<template>
  <div class="flex flex-col">
    <label
      class="text-sm font-bold text-primary-dark"
      :class="$slots.helper ? 'mb-1' : 'mb-2'"
      :for="id"
    >
      <slot /> <span v-if="required">*</span>
    </label>
    <p v-if="$slots.helper" class="text-xs mb-4 lg:w-3/4 opacity-50">
      <slot name="helper" />
    </p>

    <textarea
      :id="id"
      class="border border-gray-300 rounded-md p-4"
      :placeholder="placeholder"
      :required="required"
      :rows="rows"
      :value="modelValue"
      @input="$emit('update:modelValue', $event.target.value)"
    />
  </div>
</template>

<script setup>
import { v4 as uuidv4 } from "uuid";

const id = uuidv4();

defineProps({
  modelValue: {
    type: String,
  },
  rows: {
    type: Number,
    default: 4,
  },
  placeholder: {
    type: String,
    default: "",
  },
  required: {
    type: Boolean,
    default: false,
  },
});

defineEmits(["update:modelValue"]);
</script>

<template>
  <div class="relative flex flex-col" :class="{ 'opacity-50': disabled }">
    <label class="font-display mb-2 text-sm" :for="id">
      <slot /> <span v-if="required">*</span>
    </label>

    <input
      :id="id"
      class="border border-gray-300 rounded-md px-2 py-1"
      :type="showPassword ? text : inputType"
      :placeholder="placeholder"
      :required="required"
      :disabled="disabled"
      :value="modelValue"
      @input="$emit('update:modelValue', $event.target.value)"
    />

    <button
      class="absolute right-0"
      type="button"
      v-if="inputType === 'password'"
      @click="togglePassword"
    >
      <IconEye v-if="!showPassword" class="h-4 w-4 text-gray-400" />
      <IconEyeClosed v-else class="h-4 w-4 text-gray-400" />
      <span class="sr-only">Toggle Password</span>
    </button>
  </div>
</template>

<script setup>
import IconEye from "@/components/svg/IconEye.vue";
import IconEyeClosed from "@/components/svg/IconEyeClosed.vue";

import { ref } from "vue";
import { v4 as uuidv4 } from "uuid";

const id = uuidv4();
const showPassword = ref(false);

defineProps({
  modelValue: {
    type: String,
  },
  inputType: {
    type: String,
    default: "text",
    validator: (value) => {
      const validProps = ["text", "email", "tel", "url", "search", "password"];
      return validProps.includes(value);
    },
  },
  placeholder: {
    type: String,
    default: "",
  },
  required: {
    type: Boolean,
    default: false,
  },
  disabled: {
    type: Boolean,
    default: false,
  },
});
defineEmits(["update:modelValue"]);

function togglePassword() {
  showPassword.value = !showPassword.value;
}
</script>

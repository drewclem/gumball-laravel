<template>
  <div>
    <BaseModal class="text-green-700">
      <template #button> Create<span class="sr-only">new tag</span> </template>
      <template #content>
        <div>
          <BaseHeading class="mb-4" size="h4" tag="h1">
            Create a tag
          </BaseHeading>

          <BaseInput class="mb-4" v-model="state.newTag">Label</BaseInput>

          <div>
            <BaseButton theme="secondary">Create</BaseButton>
          </div>
        </div>
      </template>
    </BaseModal>
  </div>
</template>

<script setup>
// utils
import { reactive } from "vue";

// components
import BaseModal from "@/components/base/BaseModal.vue";
import BaseHeading from "@/components/base/BaseHeading.vue";
import BaseInput from "@/components/base/BaseInput.vue";
import BaseButton from "@/components/base/BaseButton.vue";

const state = reactive({
  newTag: "",
});
</script>

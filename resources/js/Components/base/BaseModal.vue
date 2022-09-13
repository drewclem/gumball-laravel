<template>
  <button
    class="open-button"
    @click="openModal"
    ref="openButtonRef"
    type="button"
    :disabled="disabled"
  >
    <slot name="button" />
    <Teleport v-if="isOpen" to="body">
      <div class="modal-wrapper" @keydown.esc="closeModal">
        <div class="modal-content card-shadow">
          <button
            class="absolute shadow-md bg-red-500 -mt-3 -ml-3 p-1 rounded-full left-0 top-0 text-white"
            @click="closeModal"
            ref="closeButtonRef"
            type="button"
          >
            <IconClose class="h-4 w-4" alt="close menu" />
          </button>
          <slot name="content" />
        </div>
      </div>
    </Teleport>
  </button>
</template>

<script setup>
defineProps({
  disabled: {
    type: Boolean,
    default: false,
  },
});
import { nextTick, ref } from "vue";
import { useGlobalLayout } from "@/stores/global";

import IconClose from "@/components/svg/IconClose.vue";

const { toggleModal, toggleMobileMenu } = useGlobalLayout();
const isOpen = ref(false);
const closeButtonRef = ref(null);
const openButtonRef = ref(null);

function closeModal() {
  isOpen.value = false;
  toggleModal(false);
  setTimeout(() => {
    openButtonRef.value.focus();
  }, 50);
}

async function openModal() {
  isOpen.value = true;
  toggleMobileMenu(false);
  await nextTick();
  await nextTick();
  toggleModal(true);
  setTimeout(() => {
    closeButtonRef.value.focus();
  }, 50);
}
</script>

<style lang="postcss" scoped>
.modal-wrapper {
  @apply z-30;
  position: fixed;
  top: 0;
  right: 0;
  bottom: 0;
  left: 0;
  background-color: rgba(1, 1, 1, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
}

.modal-content {
  @apply relative bg-white p-6 xl:p-10 rounded-lg mx-6;
  max-width: 540px;
  max-height: 95vh;
}

button:disabled {
  @apply opacity-50 pointer-events-none;
}
</style>

<template>
  <div class="relative flex items-center" @keydown.esc="closeSearch">
    <transition name="search" appear>
      <input
        ref="search"
        v-if="state.isOpen"
        class="py-2 px-4 border border-gray-500 rounded-full mr-3 h-[34px] lg:w-64"
        type="text"
        placeholder="Search"
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
      />
    </transition>

    <button
      ref="searchButton"
      class="rounded-full h-full border border-gray-500 p-2 opacity-50 hover:opacity-100"
      :class="{ 'bg-white opacity-100': state.isOpen }"
      type="button"
      @click="toggleSearch"
    >
      <transition v-if="!state.isOpen" name="fade" appear>
        <IconSearch class="w-4 h-4 text-gray-500" />
      </transition>
      <transition v-else name="fade" appear>
        <IconClose class="w-4 h-4 text-gray-500" />
      </transition>
    </button>
  </div>
</template>

<script setup>
// utils
import { ref, nextTick, reactive } from "vue";

// components
import IconSearch from "@/components/svg/IconSearch.vue";
import IconClose from "@/components/svg/IconClose.vue";

defineProps({
  modelValue: {
    type: String,
  },
});

defineEmits(["update:modelValue"]);

const state = reactive({
  isOpen: false,
});
const search = ref(null);
const searchButton = ref(null);

function toggleSearch() {
  state.isOpen = !state.isOpen;

  nextTick(() => {
    if (state.isOpen) {
      search.value.focus();
    } else {
      searchButton.value.focus();
    }
  });
}

function closeSearch() {
  state.isOpen = false;
  searchButton.value.focus();
}
</script>

<style scoped>
.search-enter-active,
.search-leave-active {
  transition: 150ms ease-in-out;
  will-change: width;
}

.search-enter-from,
.search-leave-to {
  width: 0;
  opacity: 0;
}

.fade-enter-active,
.fade-leave-active {
  transition: 150ms ease-in-out;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
  transform: rotate(-90deg);
}
</style>

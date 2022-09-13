<template>
  <div
    class="drop-area"
    @drop.prevent="onDrop"
    :data-active="active"
    @dragenter.prevent="setActive"
    @dragover.prevent="setActive"
    @dragleave.prevent="setInactive"
  >
    <slot :dropZoneActive="active" />
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from "vue";
const emit = defineEmits(["files-dropped"]);

let active = ref(false);
let inactiveTimeout = null;

function setActive() {
  active.value = true;
  clearTimeout(inactiveTimeout);
}

function setInactive() {
  inactiveTimeout = setTimeout(() => {
    active.value = false;
  }, 50);
}

function onDrop(e) {
  setInactive();
  emit("files-dropped", [...e.dataTransfer.files]);
}

function preventDefaults(e) {
  e.preventDefault();
}

const events = ["dragenter", "dragover", "dragleave", "drop"];

onMounted(() => {
  events.forEach((eventName) => {
    document.body.addEventListener(eventName, preventDefaults);
  });
});

onUnmounted(() => {
  events.forEach((eventName) => {
    document.body.removeEventListener(eventName, preventDefaults);
  });
});
</script>

<style>
.drop-area {
  @apply text-center border border-dashed border-gray-300 p-2 rounded-lg;
}

.drop-area label input {
  @apply sr-only;
}

.drop-area[data-active="true"] {
  @apply border-green-500;
}
</style>

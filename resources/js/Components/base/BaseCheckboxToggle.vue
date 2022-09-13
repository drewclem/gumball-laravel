<template>
  <div>
    <label class="active-switch round" :for="id">
      <input
        type="checkbox"
        :checked="checked"
        :id="id"
        @input="$emit('update:checked', $event.target.checked)"
        :disabled="disabled"
      />
      <span class="publish-dot round"></span>
    </label>
  </div>
</template>

<script setup>
defineProps({
  id: {
    type: String,
    required: true,
  },
  disabled: {
    type: Boolean,
    default: false,
  },
  checked: {
    type: Boolean,
    default: false,
  },
});

defineEmits(["update:checked"]);
</script>

<style scoped>
.active-switch {
  position: relative;
  display: inline-block;
  width: 30px;
  height: 20px;
}

.active-switch input {
  opacity: 0;
  width: 0;
  height: 0;
}

.publish-dot {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #f0f0f0;
  -webkit-transition: 0.3s;
  transition: 0.3s;
}

.publish-dot:before {
  @apply bg-gray-400;
  position: absolute;
  content: "";
  height: 16px;
  width: 16px;
  bottom: 2px;
  left: 4px;
  -webkit-transition: 0.3s;
  transition: 0.3s;
}

:before {
  @apply bg-green-500;
}

.publish-dot.round {
  border-radius: 24px;
}

.publish-dot.round:before {
  @apply shadow-inner;
  border-radius: 50%;
}

input:checked + .publish-dot:before {
  -webkit-transform: translateX(7px);
  -ms-transform: translateX(7px);
  transform: translateX(7px);
}
</style>

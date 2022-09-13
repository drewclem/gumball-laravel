<template>
  <router-link
    :class="`btn btn-${theme} group ${$slots.icon ? 'flex items-center' : ''}`"
    v-if="href"
    :to="href"
  >
    <span v-if="$slots.icon" :class="$slots.icon ? 'mr-3' : ''">
      <slot name="icon" />
    </span>

    <slot />
  </router-link>

  <button
    v-else
    :class="`btn btn-${theme} group ${$slots.icon ? 'flex items-center' : ''}`"
    :type="type"
    @click="$emit('user-click')"
  >
    <span v-if="$slots.icon" :class="$slots.icon ? 'mr-3 ' : ''">
      <slot name="icon" />
    </span>

    <slot />
  </button>
</template>

<script>
export default {
  props: {
    type: {
      type: String,
      default: "button",
    },
    theme: {
      type: String,
      default: "primary",
    },
    href: {
      type: String,
      required: false,
    },
  },
};
</script>

<style lang="postcss" scoped>
.btn {
  @apply px-3 py-0.5 font-display text-center rounded-md border-2 border-transparent transition duration-150 ease-in-out;
}
.btn-primary {
  @apply bg-red-500 text-white;

  box-shadow: 0px 3px 3px rgba(0, 0, 0, 0.08), 0 8px 8px rgba(0, 0, 0, 0.05),
    0 10px 10px rgba(0, 0, 0, 0.03);
}

.btn-primary:hover {
  @apply bg-red-600;
}

.btn-secondary {
  @apply border-blue-500 text-black;
}

.btn-secondary:hover {
  @apply bg-blue-500 text-white;

  box-shadow: 0px 3px 3px rgba(0, 0, 0, 0.08), 0 8px 8px rgba(0, 0, 0, 0.05),
    0 10px 10px rgba(0, 0, 0, 0.03);
}

.btn-tertiary {
  @apply bg-green-500 text-white;

  box-shadow: 0px 3px 3px rgba(0, 0, 0, 0.08), 0 8px 8px rgba(0, 0, 0, 0.05),
    0 10px 10px rgba(0, 0, 0, 0.03);
}

.btn-subdued {
  @apply border-gray-300 text-gray-400 hover:border-gray-500 hover:text-gray-500;
}

.btn-tertiary:hover {
  @apply bg-green-600;
}

.btn:disabled {
  @apply opacity-50 pointer-events-none;
}

@screen lg {
  .btn {
    @apply px-6;
  }
}
</style>

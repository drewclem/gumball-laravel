<template>
  <component :is="component" :class="size">
    <slot />
  </component>
</template>

<script>
export default {
  name: "BaseHeading",
  props: {
    size: {
      type: String,
      required: true,
      validator: (value) =>
        ["h1", "h2", "h3", "h4", "h5", "h6"].includes(value),
    },
    /** The semantic HTML element used for the heading. Defaults to the value of `size`. */
    tag: {
      type: String,
      default: undefined,
      validator: (value) =>
        ["h1", "h2", "h3", "h4", "h5", "h6", "div"].includes(value),
    },
  },
  computed: {
    component() {
      return this.tag ?? this.size.slice(0, 2);
    },
  },
};
</script>

<style lang="postcss" scoped>
h1,
h2,
h3,
h4,
h5,
h6 {
  @apply tracking-tighter font-display;
}

.h1 {
  @apply text-5xl font-bold;
}

.h2 {
  @apply text-3xl font-bold;
}

.h3 {
  @apply text-2xl;
}

.h4 {
  @apply text-lg;
}

@screen lg {
  .h1 {
    @apply text-7xl;
  }
  .h2 {
    @apply text-6xl;
  }

  .h3 {
    @apply text-4xl;
  }

  .h4 {
    @apply text-xl;
  }
}
</style>

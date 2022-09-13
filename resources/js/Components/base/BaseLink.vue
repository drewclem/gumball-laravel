<template>
  <a
    v-if="external"
    :class="`cursor-pointer ${classes} ${
      $slots.icon ? 'flex items-center' : ''
    } group`"
    class="text-base"
    :href="href"
    v-bind="$attrs"
  >
    <span v-if="$slots.icon" :class="$slots.icon ? 'mr-2' : ''">
      <slot name="icon" />
    </span>

    <slot />
  </a>

  <router-link
    v-else
    :class="`cursor-pointer  ${classes} ${
      $slots.icon ? 'flex items-center' : ''
    }`"
    class="text-base"
    :to="href"
    v-bind="$attrs"
  >
    <span v-if="$slots.icon" :class="$slots.icon ? 'mr-2' : ''">
      <slot name="icon" />
    </span>

    <slot />
  </router-link>
</template>

<script>
export default {
  inheritAttrs: false,
  props: {
    href: {
      type: String,
      required: true,
    },
    external: {
      type: Boolean,
      default: false,
    },
    classes: {
      type: String,
      default: "",
    },
  },
};
</script>

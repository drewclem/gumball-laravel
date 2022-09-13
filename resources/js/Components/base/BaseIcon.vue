<template>
  <component
    :is="file"
    role="img"
    focusable="false"
    :aria-label="alt"
    fill="currentColor"
  ></component>
</template>

<script>
// import IconComponents from '~/utils/registerIconComponents.js';

export default {
  props: {
    file: {
      type: String,
      required: true,
    },
    alt: {
      type: String,
      required: true,
    },
  },
};
</script>

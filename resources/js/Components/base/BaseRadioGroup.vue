<template>
  <div>
    <!-- @slot The group’s content. Should contain at least two `BaseRadio` components (not necessarily direct children). -->
    <slot />
  </div>
</template>

<script>
let nameIncrement = 0;

export const AbstractBaseRadioGroup = {
  model: {
    prop: "value",
    event: "change",
  },
  props: {
    value: {
      type: [String, Number],
      default: undefined,
    },
  },
  data() {
    return {
      inputName: `radio-group-${nameIncrement++}`,
    };
  },
};

/**
 * An unstyled group of `BaseRadio` components.
 */
export default {
  extends: AbstractBaseRadioGroup,
  provide() {
    return {
      BaseRadioGroup: this,
    };
  },
};
</script>

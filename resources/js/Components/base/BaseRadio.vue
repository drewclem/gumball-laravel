<template>
  <label class="flex items-center">
    <input
      type="radio"
      :name="group.inputName"
      :value="value"
      :checked="checked"
      :disabled="disabled"
      v-on="listeners"
      @change="change"
    />
    <span class="ml-2">
      <!-- @slot The radio button’s label. Should contain text. -->
      <slot />
    </span>
  </label>
</template>

<script>
// /**
//  * A radio button with a label, which can optionally be hidden.
//  */
// export default {
//   inject: {
//     group: "BaseRadioGroup",
//   },
//   props: {
//     /** The value to use when the radio button is checked. */
//     value: {
//       type: [String, Number],
//       required: true,
//     },
//     /** Whether the radio button is disabled. A disabled radio button is grayed out and cannot be focused or checked. */
//     disabled: {
//       type: Boolean,
//       default: false,
//     },
//     /** Whether the label is hidden, in which case only the circular button is shown. Even when hidden, the label remains accessible to screen readers. */
//     labelHidden: {
//       type: Boolean,
//       default: false,
//     },
//   },
//   computed: {
//     checked() {
//       return Boolean(this.value && this.value === this.group.value);
//     },
//     listeners() {
//       const { change, ...listeners } = this.$listeners;
//       return listeners;
//     },
//   },
//   methods: {
//     change() {
//       /** Emitted *on the parent group* when this radio button is checked. */
//       this.group.$emit("change", this.value);
//     },
//   },
// };
</script>

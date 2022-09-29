<script setup>
const props = defineProps({
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
});
</script>

<template>
    <Link
        :class="`cursor-pointer  ${classes} ${
            $slots.icon ? 'flex items-center' : ''
        }`"
        class="text-base"
        :href="href"
    >
        <span v-if="$slots.icon" :class="$slots.icon ? 'mr-2' : ''">
            <slot name="icon" />
        </span>

        <slot />
    </Link>
</template>

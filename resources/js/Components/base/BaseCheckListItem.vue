<script setup>
import IconCheck from "@/Components/svg/IconCheck.vue";
</script>

<template>
    <li class="flex">
        <IconCheck class="mr-4 h-5 w-5 text-blue-500" />
        <div>
            <p class="relative"><slot /></p>
            <p v-if="$slots.helper" class="text-xs text-red-500 -mt-1">
                <slot name="helper" />
            </p>
        </div>
    </li>
</template>

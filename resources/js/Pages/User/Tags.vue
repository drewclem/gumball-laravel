<script>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
export default {
    layout: AuthenticatedLayout,
};
</script>

<script setup>
// components
import BaseHeading from "@/Components/base/BaseHeading.vue";
import IconClose from "@/Components/svg/IconClose.vue";
import { Head } from "@inertiajs/inertia-vue3";
import { Inertia } from "@inertiajs/inertia";

const props = defineProps({
    tags: {
        type: Array,
        required: true,
        default: () => [],
    },
});

async function deleteTag(id) {
    if (window.confirm("This can't be undone! Are you sure?")) {
        Inertia.delete(route("tags.delete", { id: id }));
    }
}
</script>

<template>
    <div>
        <Head title="Tags" />
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center">
                <BaseHeading size="h4" tag="h1">Tags</BaseHeading>
            </div>
        </div>

        <div>
            <ul class="flex flex-wrap">
                <li
                    v-for="tag in tags"
                    :key="tag.id"
                    class="inline bg-white p-4 rounded-md card-shadow mr-6 mb-6"
                >
                    <span class="text-blue-500 mr-2">{{
                        tag.usage_count
                    }}</span>
                    <span class="mr-4">{{ tag.label }}</span>
                    <button
                        @click="deleteTag(tag.id)"
                        class="text-red-500 border border-red-500 rounded-full p-1"
                    >
                        <IconClose class="w-3 h-3" />
                    </button>
                </li>
            </ul>
        </div>
    </div>
</template>

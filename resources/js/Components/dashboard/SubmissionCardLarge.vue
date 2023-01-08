<script setup>
// components
import IconThumbDown from "@/Components/svg/IconThumbDown.vue";
import IconThumbUp from "@/Components/svg/IconThumbUp.vue";
import IconDecline from "@/Components/svg/IconDecline.vue";

const props = defineProps({
    submission: {
        type: Object,
        required: true,
    },
});
</script>

<template>
    <Link
        :href="`/collections/${submission.collection_id}/submissions/${submission.id}`"
        class="relative bg-white card-shadow rounded-lg w-full px-5 pt-4 pb-6 lg:px-8 lg:py-5 text-sm lg:text-base"
    >
        <div
            class="absolute left-0 top-0 flex items-center h-full ml-0.5 lg:ml-1"
        >
            <IconDecline
                v-if="submission.is_declined"
                class="transform scale-50 lg:scale-75 text-gray-300"
            />
            <IconThumbDown
                v-else-if="
                    submission.is_liked === -1 && !submission.is_declined
                "
                class="transform scale-50 lg:scale-75 text-red-300"
            />
            <IconThumbUp
                v-if="submission.is_liked === 1 && !submission.is_declined"
                class="transform scale-50 lg:scale-75 text-green-300"
            />
        </div>

        <p
            v-if="submission.booked"
            class="absolute left-0 top-0 ml-5 lg:ml-8 text-[8px] lg:text-[10px] text-blue-500"
        >
            Booked
        </p>

        <div class="relative grid grid-cols-5 gap-4 overflow-hidden">
            <div class="col-span-1">
                <div class="relative">
                    <img
                        v-if="submission.images.length > 0"
                        class="h-12 w-12 lg:h-24 lg:w-24 object-cover rounded-md"
                        :src="`/${submission.images[0].file_path}`"
                    />
                    <div
                        v-else
                        class="flex justify-center items-center h-12 w-12 lg:h-24 lg:w-24 rounded-lg bg-gray-100"
                    >
                        <p class="text-sm text-gray-500 text-center">
                            No images provided
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-span-4">
                <div class="relative collapsed">
                    <div class="relative h-[100px] overflow-hidden">
                        <div v-html="submission.message" class="mb-4" />

                        <div
                            class="absolute w-full bottom-0 bg-gradient-to-t from-white to-transparent h-1/3 z-10"
                        />
                    </div>

                    <!-- <ul
                        v-if="submission && submission.tags.length > 0"
                        class="flex space-x-3 mt-2"
                    >
                        <li
                            v-for="tag in submission.tags"
                            class="px-3 py-1 bg-blue-100 text-blue-500 text-xs rounded-full"
                            :key="tag.id"
                        >
                            {{ tag.label }}
                        </li>
                    </ul> -->
                </div>
            </div>
        </div>
    </Link>
</template>

<style scoped>
.fade-ender-active,
.fade-leave-active {
    opacity: 1;
    transition: 300ms;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>

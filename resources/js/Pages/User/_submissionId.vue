<script>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
export default {
    layout: AuthenticatedLayout,
};
</script>

<script setup>
// utils
import { ref, computed } from "vue";
import { Head } from "@inertiajs/inertia-vue3";
import { Inertia } from "@inertiajs/inertia";

// components
import BaseHeading from "@/components/base/BaseHeading.vue";
import BaseImage from "@/components/base/BaseImage.vue";
import BaseModal from "@/components/base/BaseModal.vue";

import IconHeart from "@/components/svg/IconHeart.vue";
import IconThumbDown from "@/components/svg/IconThumbDown.vue";
import IconThumbUp from "@/components/svg/IconThumbUp.vue";
import IconArrowLeft from "@/components/svg/IconArrowLeft.vue";
import IconClose from "@/components/svg/IconClose.vue";

const props = defineProps({
    submission: {
        type: Object,
        required: true,
    },
    tags: {
        type: Array,
        default: () => [],
    },
    images: {
        type: Array,
        default: () => [],
    },
    user_tags: {
        type: Array,
    },
});

const newTag = ref("");
const isUnique = ref(true);
const showAllTags = ref(false);

// check against new tag input to see if current phrase already exists as a tag
const matchedTags = computed(() => {
    const input = newTag.value.toLowerCase();
    const filteredTags = [];

    props.user_tags.filter((tag) => {
        if (showAllTags.value && input.length === 0) {
            filteredTags.push(tag);
        } else {
            const formatted = tag.label?.toLowerCase();

            if (input.length > 0 && formatted.includes(input)) {
                filteredTags.push(tag);

                input.length === formatted.length
                    ? (isUnique.value = false)
                    : (isUnique.value = true);
            }
        }
    });

    return filteredTags;
});

async function createTag() {
    Inertia.put(
        route("tags.store", [
            { submission_id: props.submission.id, label: newTag.value },
        ])
    );
    newTag.value = "";
}

async function applyTag(tagId) {
    Inertia.put(
        route("tag_relation.store", [
            {
                submission_id: props.submission.id,
                tag_id: tagId,
            },
        ])
    );

    newTag.value = "";
}

async function deleteTag(relationId) {
    Inertia.delete(route("tag_relation.delete", { id: relationId }));
}

const setHasViewed = () => {
    Inertia.put(route("submission.markViewed", props.submission));
};

if (!props.submission.has_viewed) setHasViewed();

const toggleFavorite = () => {
    Inertia.put(route("submission.favorite", props.submission));
};

const toggleBooked = () => {
    Inertia.put(route("submission.book", props.submission));
};

async function dislikeSubmission() {
    Inertia.put(route("submission.dislike", props.submission));
}

async function likeSubmission() {
    Inertia.put(route("submission.like", props.submission));
}


// delete submission
const deleteSubmission = () => {
    if (window.confirm("This can't be undone! Are you sure?")) {
        Inertia.delete(route("submission.delete", props.submission));
    }
};

/**
 * Decline submission
 */

const isError = ref(false);
const resMessage = ref();
const isSubmitting = ref(false);

async function declineSubmission() {
    isSubmitting.value = true

    Inertia.post(route("submission.decline", props.submission));

    setTimeout(() => {
        isSubmitting.value = false
    }, 1000);
}
</script>

<template>
    <div>

        <Head title="Collections" />

        <div>
            <div class="flex items-center justify-between mb-8">
                <div class="flex items-baseline">
                    <BaseHeading size="h4" tag="h1">Collections</BaseHeading>

                    <Link class="ml-6 opacity-60" aria-label="View the list of submissions for this collection"
                        :href="`/collections/${submission.collection_id}`">
                    <IconArrowLeft class="h-3 w-3 inline -mt-0.5" />
                    Back
                    </Link>
                </div>
            </div>

            <div class="flex flex-col lg:flex-row gap-12">
                <!-- left side -->
                <div class="text-sm w-44 flex flex-col space-y-5">
                    <div>
                        <BaseHeading size="h5" tag="h2">Name</BaseHeading>
                        <p>{{ submission.name }}</p>
                    </div>

                    <div class="overflow-hidden">
                        <BaseHeading size="h5" tag="h2">Email</BaseHeading>
                        <p class="truncate">{{ submission.email }}</p>
                    </div>

                    <div>
                        <BaseHeading size="h5" tag="h2">Phone</BaseHeading>
                        <p>{{ submission.phone }}</p>
                    </div>

                    <hr />

                    <div>
                        <ul>
                            <li>
                                <input type="text" class="border border-gray-300 rounded-md px-2 py-1 mb-2"
                                    placeholder="Add a tag" v-model="newTag" @focus="showAllTags = true" />

                                <button class="bg-green-100 text-green-700 p-1 rounded-full mt-2 ml-auto"
                                    v-if="newTag.length > 0 && isUnique" @click="createTag(newTag)">
                                    Create
                                </button>

                                <ul v-if="
                                    matchedTags.length > 0 &&
                                    newTag.length > 0
                                " class="mt-2 flex flex-col space-y-2">
                                    <li v-for="tag in matchedTags" :key="tag.id">
                                        <button class="tag border border-blue-300" type="button"
                                            @click="applyTag(tag.id)">
                                            {{ tag.label }}
                                        </button>
                                    </li>
                                </ul>
                            </li>

                            <li class="inline-block my-1" v-for="tag in tags" :key="tag.id">
                                <div class="bg-blue-100 py-1 px-3 rounded-full flex space-x-2 items-center">
                                    <span>
                                        {{ tag.label }}
                                    </span>

                                    <button class="border border-gray-400 rounded-full p-0.5"
                                        @click="deleteTag(tag.id)">
                                        <span class="sr-only">
                                            Delete tag {{ tag.label }}
                                        </span>
                                        <IconClose class="h-3 w-3" />
                                    </button>
                                </div>
                            </li>
                        </ul>
                    </div>

                    <form @submit.prevent="toggleFavorite">
                        <button class="flex text-base" type="submit" :disabled="submission.is_declined">
                            <IconHeart class="h-5 w-5 mr-2 -mt-px" :class="
                                submission.is_saved
                                    ? 'text-red-500'
                                    : 'text-gray-300'
                            " />
                            <span v-if="!submission.is_saved">Favorite</span>
                            <span v-else>Favorited</span>
                        </button>
                    </form>

                    <hr />

                    <a :href="`mailto:${submission.email}`"
                        class="py-0.5 border-2 border-green-500 hover:bg-green-500 hover:text-white text-center rounded-md"
                        :class="
                            submission.booked
                                ? 'opacity-50 pointer-events-none'
                                : ''
                        " :disabled="submission.booked">
                        Reply
                    </a>

                    <form class="border-2 border-blue-500 hover:bg-blue-500 hover:text-white text-center rounded-md"
                        :class="{ 'pointer-events-none opacity-50': submission.is_declined }"
                        @submit.prevent="toggleBooked">
                        <button type="submit" :class="`${submission.is_booked
                        ? 'bg-blue-500 text-white'
                        : ''
                        } h-full w-full py-1`" :disabled="submission.is_declined">
                            <span v-if="!submission.is_booked">Mark as booked</span>
                            <span v-else>Booked</span>
                        </button>
                    </form>

                    <div class="relative">
                        <p class="text-xs opacity-50 mb-3">
                            Notify your client they won't be selected this
                            round.
                        </p>
                        <button type="button" class="py-0.5 border-2 border-gray-300 text-center rounded-md w-full mb-6"
                            :class="{
                                'hover:border-gray-400':
                                    !submission.is_declined,
                            }" @click="declineSubmission" :disabled="submission.is_declined || isSubmitting">
                            <span v-if="isSubmitting">Sending...</span>
                            <span v-else-if="submission.is_declined">Declined</span>
                            <span v-else>Decline</span>
                        </button>
                    </div>

                    <hr />

                    <button type="button" class="text-red-500 underline text-left" @click="deleteSubmission">
                        Delete
                    </button>
                </div>

                <!-- right side -->
                <div class="order-first lg:order-last lg:w-3/4">
                    <div class="card-shadow bg-white p-8 lg:p-12 rounded-lg mb-8">
                        <div class="flex justify-between" items-center>
                            <BaseHeading size="h5" tag="h2" class="mb-5">Message</BaseHeading>

                            <div class="flex flex-row space-x-4 mb-4">
                                <button class="flex text-base" @click="dislikeSubmission">
                                    <IconThumbDown :class="
                                        submission.is_liked === -1
                                            ? 'text-red-500'
                                            : 'text-gray-300 hover:text-gray-500'
                                    " />
                                    <span class="sr-only" v-if="submission.is_liked === -1">
                                        Disliked
                                    </span>
                                </button>

                                <button class="flex text-base" @click="likeSubmission">
                                    <IconThumbUp :class="
                                        submission.is_liked === 1
                                            ? 'text-green-500'
                                            : 'text-gray-300 hover:text-gray-500'
                                    " />
                                    <span class="sr-only" v-if="submission.is_liked === 1">
                                        Liked
                                    </span>
                                </button>
                            </div>
                        </div>
                        <div class="richtext" v-html="submission.message" />
                    </div>

                    <div v-if="images" class="card-shadow bg-white p-8 lg:p-12 rounded-lg">
                        <BaseHeading size="h5" tag="h2" class="mb-5">
                            Reference media
                        </BaseHeading>

                        <ul class="grid grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-8">
                            <li v-for="(image, index) in images" :key="index">
                                <BaseModal>
                                    <template #button>
                                        <BaseImage class="img-list hover:scale-125 ease-in-out transform"
                                            :src="`/${image.file_path}`" alt="Img thumbnail" />
                                    </template>
                                    <template #content>
                                        <BaseImage class="rounded-lg" :src="`/${image.file_path}`" />
                                    </template>
                                </BaseModal>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style>
.richtext {
    @apply leading-relaxed;
}

.richtext ul {
    @apply list-disc;
    list-style-position: inside;
}

.img-list {
    @apply rounded-lg shadow-md;
    aspect-ratio: 1/1;
    object-fit: cover;
}

button:disabled {
    @apply opacity-50 pointer-events-none;
}

.tag {
    @apply bg-gray-100 rounded-full px-2 py-1;
}
</style>

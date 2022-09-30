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

// components
import BaseHeading from "@/components/base/BaseHeading.vue";
import BaseSelect from "@/components/base/BaseSelect.vue";
import BaseText from "@/components/base/BaseText.vue";
import BaseCheckboxToggle from "@/components/base/BaseCheckboxToggle.vue";
import SubmissionCard from "@/components/dashboard/SubmissionCard.vue";
import SubmissionCardLarge from "@/components/dashboard/SubmissionCardLarge.vue";
import KeywordSearch from "@/components/dashboard/KeywordSearch.vue";
import IconSearch from "@/components/svg/IconSearch.vue";
import CopyShareLink from "@/components/dashboard/CopyShareLink.vue";

const props = defineProps({
    submissions: {
        type: Array,
        default: () => [],
    },
});

/**
 * Filter through submissions
 */

const searchPhrase = ref(null);
const filterWord = ref(null);

const filteredSubmissions = computed(() => {
    let likedSubmissions = [];
    let midSubmissions = [];
    let dislikedSubmissions = [];
    let declinedSubmissions = [];

    props?.submissions.filter((submission) => {
        if (submission.is_declined) declinedSubmissions.push(submission);
        if (submission.is_liked === -1 && !submission.is_declined)
            dislikedSubmissions.push(submission);
        if (submission.is_liked === 1 && !submission.is_declined)
            likedSubmissions.push(submission);
        if (submission.is_liked === 0 && !submission.is_declined)
            midSubmissions.push(submission);
    });

    const sortedSubmissions = [
        ...likedSubmissions,
        ...midSubmissions,
        ...dislikedSubmissions,
        ...declinedSubmissions,
    ];

    return sortedSubmissions.filter((submission) => {
        if (searchPhrase.value === null) return submission;
        const filter = searchPhrase.value.toLowerCase();

        const email = submission.email?.toLowerCase();
        const name = submission.name?.toLowerCase();
        const message = submission.message?.toLowerCase();

        if (
            searchPhrase.value !== null &&
            (email.includes(filter) ||
                name.includes(filter) ||
                message.includes(filter) ||
                submission.phone.includes(filter))
        )
            return submission;
    });
});
</script>

<template>
    <div>
        <Head title="Favorites" />
        <div class="max-w-4xl">
            <div class="flex items-center justify-between mb-8">
                <div class="flex items-center">
                    <BaseHeading size="h4" tag="h1">Favorites</BaseHeading>

                    <div
                        class="bg-white rounded-full px-4 py-2 shadow-inner flex space-x-6 text-sm ml-6"
                    >
                        <!-- <div class="flex space-x-2 items-center text-sm">
                        <p class="text-blue-500">Message</p>
                        <BaseCheckboxToggle
                            id="`viewMode`"
                            v-model:checked="currentUser.default_view"
                            :modelValue="currentUser.default_view"
                            @update:checked="updateViewMode"
                        />
                        <p class="text-blue-500">Info</p>
                    </div> -->
                    </div>
                </div>

                <div class="relative hidden lg:block">
                    <!-- <div
                    class="
                        absolute
                        top-0
                        right-0
                        flex
                        justify-center
                        items-center
                        -mt-4
                    "
                >
                    <KeywordSearch class="flex mr-4" v-model="searchPhrase" />
                    <BaseSelect
                        :options="currentUser.tags"
                        v-model="filterWord"
                    >
                        Filter
                    </BaseSelect>
                </div> -->
                </div>

                <!-- <BaseSelect
                class="w-full lg:hidden"
                :options="currentUser.tags"
                v-model="filterWord"
            >
                Filter
            </BaseSelect>

            <KeywordSearch
                class="lg:hidden"
                v-model="searchPhrase"
                :value="searchPhrase"
            /> -->
            </div>

            <div>
                <div
                    class="grid grid-cols-6 gap-2 card-padding text-sm lg:text-base opacity-40 mb-4"
                >
                    <p class="col-span-2">Name</p>
                    <p class="col-span-2">Email</p>
                    <p>Phone</p>
                    <p class="ml-auto">Submitted On</p>
                </div>

                <div class="flex flex-col space-y-6">
                    <div v-if="!submissions.length">
                        <p class="mb-5">No saved submissions</p>
                    </div>

                    <div v-else-if="!filteredSubmissions.length">
                        <BaseHeading
                            class="text-red-500 mb-5"
                            size="h3"
                            tag="h2"
                            >Uh oh!</BaseHeading
                        >
                        <BaseText
                            >Looks like we couldn't find anything.</BaseText
                        >
                        <BaseText size="small">Check for typos!</BaseText>
                    </div>

                    <template v-else>
                        <SubmissionCard
                            v-for="submission in filteredSubmissions"
                            :key="submission.id"
                            :submission="submission"
                        />
                    </template>
                </div>
            </div>

            <!-- <div v-else>
            <div
                class="
                    grid grid-cols-5
                    px-5
                    gap-4
                    py-3
                    lg:px-8
                    text-sm
                    lg:text-base
                    lg:py-4
                    opacity-40
                    mb-4
                "
            >
                <p class="col-span-1">Thumbnail</p>
                <p class="col-span-4">Message</p>
            </div>

            <div class="flex flex-col space-y-8">
                <div v-if="!savedSubmissions.length">
                    <p class="mb-5">No submissions yet! Share that link!</p>
                    <CopyShareLink />
                </div>

                <div v-else-if="!filteredSubmissions.length">
                    <BaseHeading class="text-red-500 mb-5" size="h3" tag="h2">
                        Uh oh!
                    </BaseHeading>
                    <BaseText> Looks like we couldn't find anything. </BaseText>
                    <BaseText size="small">Check for typos!</BaseText>
                </div>

                <template v-else>
                    <SubmissionCardLarge
                        v-for="submission in filteredSubmissions"
                        :key="submission.id"
                        :submission="submission"
                    />
                </template>
            </div>
        </div> -->
        </div>
    </div>
</template>

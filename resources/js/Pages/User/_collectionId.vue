<script>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
export default {
    layout: AuthenticatedLayout,
};
</script>

<script setup>
// utils
import { ref, computed, nextTick } from "vue";
import { Head, useForm } from "@inertiajs/inertia-vue3";
import { Inertia } from "@inertiajs/inertia";
import { useDates } from "@/utils/dates";
import axios from 'axios'

// components
import BaseHeading from "@/Components/base/BaseHeading.vue";
import BaseText from "@/Components/base/BaseText.vue";
import BaseCheckboxToggle from "@/Components/base/BaseCheckboxToggle.vue";
import BaseSelect from "@/Components/base/BaseSelect.vue";
import SubmissionCard from "@/Components/dashboard/SubmissionCard.vue";
import SubmissionCardLarge from "@/Components/dashboard/SubmissionCardLarge.vue";
import CopyShareLink from "@/Components/dashboard/CopyShareLink.vue";
import KeywordSearch from "@/Components/dashboard/KeywordSearch.vue";

// icons
import IconSearch from "@/Components/svg/IconSearch.vue";
import IconArrowLeft from "@/Components/svg/IconArrowLeft.vue";
import IconArchive from "@/Components/svg/IconArchive.vue";
import IconDelete from "@/Components/svg/IconDelete.vue";
import IconLockClosed from "@/Components/svg/IconLockClosed.vue";

const copySuccess = ref(false);

const props = defineProps({
    auth: {
        type: Object,
    },
    collection: {
        type: Object,
    },
    submissions: {
        type: Array,
    },
    has_subscription: {
        type: Boolean
    },
    user_tags: {
        type: Array
    }
});

const { currentDate } = useDates();

const isOpen = computed(() => {
    const current = new Date(currentDate);
    const end = new Date(props.collection.end_date);

    const currentformatted = current.getMilliseconds();
    const endFormatted = end.getMilliseconds();

    return current < end;
});

/**
 * Submission filtering
 */
const searchPhrase = ref(null);
const filterWord = ref(null);

const filteredSubmissions = computed(() => {
    let likedSubmissions = [];
    let midSubmissions = [];
    let dislikedSubmissions = [];
    let declinedSubmissions = [];

    props.submissions.filter((submission) => {
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
        let matched = false;

        if (
            (searchPhrase.value === null || searchPhrase.value === "") &&
            (filterWord.value === null || filterWord.value === "null")
        )
            return submission;

        if (
            searchPhrase.value !== null &&
            (filterWord.value === null || filterWord.value === "null")
        ) {
            const search = searchPhrase.value.toLowerCase();

            const email = submission.email?.toLowerCase();
            const name = submission.name?.toLowerCase();
            const message = submission.message?.toLowerCase();

            if (
                email.includes(search) ||
                name.includes(search) ||
                message.includes(search) ||
                submission.phone.includes(search)
            ) {
                matched = true;
            }
        }

        if (
            filterWord.value !== null &&
            (searchPhrase.value === null || searchPhrase.value === "")
        ) {
            const filter = filterWord.value.toLowerCase();
            const email = submission.email?.toLowerCase();
            const name = submission.name?.toLowerCase();
            const message = submission.message?.toLowerCase();

            submission.tags.filter((tag) => {
                const label = tag.label.toLowerCase();
                if (label.includes(filter)) matched = true;
            });
        }

        if (
            (filterWord.value !== null || filterWord.value !== "null") &&
            (searchPhrase.value !== null || searchPhrase.value !== "")
        ) {
            const search = searchPhrase?.value?.toLowerCase();
            const filter = filterWord?.value?.toLowerCase();

            const email = submission.email?.toLowerCase();
            const name = submission.name?.toLowerCase();
            const message = submission.message?.toLowerCase();

            submission.tags.filter((tag) => {
                const label = tag.label.toLowerCase();
                if (
                    label.includes(filter) &&
                    (email.includes(search) ||
                        name.includes(search) ||
                        message.includes(search) ||
                        submission.phone.includes(search))
                )
                    matched = true;
            });
        }

        if (matched) return submission;
    });
});

const searchResults = ref([])

function submitSearch() {
    axios.put('/search', {
        search: searchInput.value
    }).then(res => {
        this.filteredSubmissions = res.data.submissions
    })
}

async function archiveCollection() { }

async function deleteCollection() {
    if (
        window.confirm(
            "Are you sure? This will remove all submissions from this collection too!"
        )
    ) {
        Inertia.delete(route("collections.destroy", props.collection));
    }
}

async function closeCollection() {
    if (
        window.confirm(
            "Are you sure? This will close your current collection window and cease to receive new submissions."
        )
    ) {
        Inertia.put(route("collections.close", [props.collection]));
    }
}

async function updateViewMode(e) {
    Inertia.put(route("settings.view-mode"));
}
</script>

<template>
    <div>

        <Head title="Collections" />

        <div>
            <div
                class="flex flex-col lg:flex-row space-y-6 lg:space-y-0 items-end lg:items-center justify-between mb-8">
                <div class="flex flex-col lg:flex-row w-full space-y-2 lg:space-y-0">
                    <div class="flex justify-between items-center w-full lg:w-auto">
                        <BaseHeading size="h4" tag="h1">
                            Collections
                        </BaseHeading>

                        <Link class="ml-6 opacity-60" href="/collections" aria-label="Go back to account info page">
                        <IconArrowLeft class="h-3 w-3 inline" />
                        Back
                        </Link>
                    </div>

                    <div class="bg-white rounded-full px-4 py-2 shadow-inner flex space-x-6 text-sm lg:ml-6">
                        <div class="flex space-x-2 items-center text-sm">
                            <p class="text-blue-500">Info</p>
                            <BaseCheckboxToggle id="`viewMode`" v-model:checked="auth.user.default_view"
                                :modelValue="auth.user.default_view" @update:checked="updateViewMode" />
                            <p class="text-blue-500">Image</p>
                        </div>

                        <button v-if="has_subscription" class="flex space-x-1 text-red-500 opacity-75 hover:opacity-100"
                            @click="deleteCollection">
                            <IconDelete class="h-4 w-4 mr-2" />
                            <span>Delete</span>
                        </button>

                        <button v-if="isOpen && has_subscription" class="flex space-x-1 text-blue-500 opacity-75 hover:opacity-100"
                            @click="closeCollection">
                            <IconLockClosed class="h-4 w-4 mr-2" />
                            <span>Close</span>
                        </button>
                    </div>
                </div>

                <div class="relative hidden lg:block">
                    <div class="absolute top-0 right-0 flex justify-center items-center -mt-4">
                        <KeywordSearch class="flex mr-4" v-model="searchPhrase" />
                        <BaseSelect
                            :options="user_tags"
                            v-model="filterWord"
                        >
                            Filter
                        </BaseSelect>
                    </div>
                </div>

                <BaseSelect
                    class="w-full lg:hidden"
                    :options="user_tags"
                    v-model="filterWord"
                >
                    Filter
                </BaseSelect>

                <input ref="search"
                    class="lg:hidden py-2 px-4 border border-gray-300 rounded-full h-[34px] w-full bg-transparent focus:bg-white focus:border-gray-500"
                    type="text" placeholder="Search" v-model="searchPhrase" />
            </div>

            <div v-if="!auth.user.default_view">
                <div class="grid grid-cols-6 px-5 gap-2 py-3 lg:px-8 text-sm lg:text-base lg:py-4 opacity-40 mb-4">
                    <p class="col-span-2">Name</p>
                    <p class="col-span-2">Email</p>
                    <p>Phone</p>
                    <p class="ml-auto text-right">Submitted On</p>
                </div>

                <div class="flex flex-col space-y-6">

                    <div v-if="!submissions.length">
                        <p class="mb-5">No submissions yet! Share that link!</p>
                        <CopyShareLink />
                    </div>

                    <div v-else-if="!filteredSubmissions.length">
                        <BaseHeading class="text-red-500 mb-5" size="h3" tag="h2">
                            Uh oh!
                        </BaseHeading>
                        <BaseText>
                            Looks like we couldn't find anything.
                        </BaseText>
                        <BaseText size="small">Check for typos!</BaseText>
                    </div>

                    <template v-else>
                        <SubmissionCard v-for="submission in filteredSubmissions" :key="submission.id"
                            :submission="submission" />
                    </template>
                </div>
            </div>

            <div v-else>
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
                    <p class="col-span-1 truncate">Thumbnail</p>
                    <p class="col-span-4 truncate">Message</p>
                </div>

                <div class="flex flex-col space-y-8">

                    <div v-if="!submissions.length">
                        <p class="mb-5">No submissions yet! Share that link!</p>
                        <CopyShareLink />
                    </div>

                    <div v-else-if="!filteredSubmissions.length">
                        <BaseHeading
                            class="text-red-500 mb-5"
                            size="h3"
                            tag="h2"
                        >
                            Uh oh!
                        </BaseHeading>
                        <BaseText>
                            Looks like we couldn't find anything.
                        </BaseText>
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
            </div>
        </div>
    </div>
</template>

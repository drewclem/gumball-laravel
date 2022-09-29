<script setup>
// utils
import { ref, onMounted, onBeforeMount, computed, nextTick } from "vue";
import { Head } from "@inertiajs/inertia-vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

// components
import BaseHeading from "@/components/base/BaseHeading.vue";
import BaseText from "@/components/base/BaseText.vue";
import BaseChecboxToggle from "@/components/base/BaseCheckboxToggle.vue";
import BaseSelect from "@/components/base/BaseSelect.vue";
import SubmissionCard from "@/components/dashboard/SubmissionCard.vue";
import SubmissionCardLarge from "@/components/dashboard/SubmissionCardLarge.vue";
import CopyShareLink from "@/components/dashboard/CopyShareLink.vue";
import KeywordSearch from "@/components/dashboard/KeywordSearch.vue";

// icons
import IconSearch from "@/components/svg/IconSearch.vue";
import IconArrowLeft from "@/components/svg/IconArrowLeft.vue";
import IconArchive from "@/components/svg/IconArchive.vue";
import IconDelete from "@/components/svg/IconDelete.vue";
import IconLockClosed from "@/components/svg/IconLockClosed.vue";

const copySuccess = ref(false);

const props = defineProps({
    collection: {
        type: Object,
    },
    submissions: {
        type: Array,
        default: () => [],
    },
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

async function archiveCollection() {
    if (window.confirm("Are you sure you want to archive this collection?")) {
        const { error } = await supabase
            .from("collections")
            .update({ archived: true })
            .match({ id: collection_id });

        if (error) {
            alert(error.message);
        } else {
            setCollections();
            router.back();
        }
    }
}

async function deleteCollection() {
    if (
        window.confirm(
            "Are you sure you want to delete this collection? This is an irreversible action and all submissions will be lost permanently."
        )
    ) {
        let assetError = null;
        submissions.value.forEach(async (submission) => {
            const { error } = await supabase
                .from("submission-uploads")
                .delete()
                .match({ submission_id: submission.id });

            if (error) {
                assetError = error;
            }
        });

        if (assetError !== null) {
            alert(assetError.message);
        } else {
            const deleteSubmissions = await supabase
                .from("submissions")
                .delete()
                .match({ collection_id: collection_id });

            const { error } = await supabase
                .from("collections")
                .delete()
                .match({ id: collection_id });

            if (error) {
                alert(error.message);
            } else {
                setCollections();
                router.back();
            }
        }
    }
}

async function closeCollection() {
    const currentDate = new Date();
    const currentDateFormatted = formatDate(currentDate);

    if (
        window.confirm("Are you sure you want to close this collection today?")
    ) {
        const { error } = await supabase
            .from("collections")
            .update({ end_date: currentDateFormatted })
            .match({ id: collection_id });

        if (error) {
            alert(error.message);
        } else {
            setCollections();
            nextTick(() => {
                router.back();
            });
        }
    }
}

async function updateViewMode(e) {
    if (window.confirm("Would you like to set this as your default view?")) {
        const { error } = await supabase
            .from("profiles")
            .update({ default_view: e })
            .match({ id: currentUser.value?.id });
    }
}
</script>

<template>
    <Head title="Collections" />

    <AuthenticatedLayout>
        <div class="max-w-4xl">
            <div
                class="
                    flex flex-col
                    lg:flex-row
                    space-y-6
                    lg:space-y-0
                    items-end
                    lg:items-center
                    justify-between
                    mb-8
                "
            >
                <div
                    class="
                        flex flex-col
                        lg:flex-row
                        w-full
                        space-y-2
                        lg:space-y-0
                    "
                >
                    <div
                        class="
                            flex
                            justify-between
                            items-center
                            w-full
                            lg:w-auto
                        "
                    >
                        <BaseHeading size="h4" tag="h1"
                            >Collections</BaseHeading
                        >

                        <Link
                            class="ml-6 opacity-60"
                            href="/collections"
                            aria-label="Go back to account info page"
                        >
                            <IconArrowLeft class="h-3 w-3 inline" />
                            Back
                        </Link>
                    </div>

                    <div
                        class="
                            bg-white
                            rounded-full
                            px-4
                            py-2
                            shadow-inner
                            flex
                            space-x-6
                            text-sm
                            lg:ml-6
                        "
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

                        <button
                            class="
                                flex
                                space-x-1
                                text-red-500
                                opacity-75
                                hover:opacity-100
                            "
                            @click="deleteCollection"
                        >
                            <IconDelete class="h-4 w-4 mr-2" />
                            <span>Delete</span>
                        </button>

                        <!-- <button
                        v-if="currentCollection.end_date === null"
                        class="
                            flex
                            space-x-1
                            text-blue-500
                            opacity-75
                            hover:opacity-100
                        "
                        @click="closeCollection"
                    >
                        <IconLockClosed class="h-4 w-4 mr-2" />
                        <span>Close</span>
                    </button> -->
                    </div>
                </div>

                <div class="relative hidden lg:block">
                    <div
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
                        <KeywordSearch
                            class="flex mr-4"
                            v-model="searchPhrase"
                        />
                        <!-- <BaseSelect
                            :options="currentUser.tags"
                            v-model="filterWord"
                        >
                            Filter
                        </BaseSelect> -->
                    </div>
                </div>

                <!-- <BaseSelect
                    class="w-full lg:hidden"
                    :options="currentUser.tags"
                    v-model="filterWord"
                >
                    Filter
                </BaseSelect> -->

                <input
                    ref="search"
                    class="
                        lg:hidden
                        py-2
                        px-4
                        border border-gray-300
                        rounded-full
                        h-[34px]
                        w-full
                        bg-transparent
                        focus:bg-white
                        focus:border-gray-500
                    "
                    type="text"
                    placeholder="Search"
                    v-model="searchPhrase"
                />
            </div>

            <div>
                <div
                    class="
                        grid grid-cols-6
                        px-5
                        gap-2
                        py-3
                        lg:px-8
                        text-sm
                        lg:text-base
                        lg:py-4
                        opacity-40
                        mb-4
                    "
                >
                    <p class="col-span-2">Name</p>
                    <p class="col-span-2">Email</p>
                    <p>Phone</p>
                    <p class="ml-auto text-right">Submitted On</p>
                </div>

                <div class="flex flex-col space-y-6">
                    <!-- <p class="opacity-50" v-if="loading">Loading...</p> -->

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
                    <p class="col-span-1 truncate">Thumbnail</p>
                    <p class="col-span-4 truncate">Message</p>
                </div>

                <div class="flex flex-col space-y-8">
                    <p class="opacity-50" v-if="loading">Loading...</p>

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
            </div> -->
        </div>
    </AuthenticatedLayout>
</template>

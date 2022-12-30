<script setup>
import { ref, computed, onMounted, onUnmounted } from "vue";

import DashboardHeader from "@/Components/global/DashboardHeader.vue";
import BaseLink from "@/Components/base/BaseLink.vue";
import BaseModal from "@/Components/base/BaseModal.vue";
import ScheduleCollection from "@/Components/dashboard/ScheduleCollection.vue";
import OpenCollection from "@/Components/dashboard/OpenCollection.vue";

// icons
import IconCollection from "@/Components/svg/IconCollection.vue";
import IconInbox from "@/Components/svg/IconInbox.vue";
import IconHeart from "@/Components/svg/IconHeart.vue";
import IconTag from "@/Components/svg/IconTag.vue";
import IconForm from "@/Components/svg/IconForm.vue";
import IconUser from "@/Components/svg/IconUser.vue";
import IconSchedule from "@/Components/svg/IconSchedule.vue";
import IconLock from "@/Components/svg/IconLock.vue";

const props = defineProps({
    auth: {
        type: Object,
        required: true,
    },
    has_subscription: {
        type: Boolean,
        default: false
    },
    unavail_dates: {
        type: Array,
    },
    global_active: {
        type: Array,
    },
});

const hasActive = computed(() => props.global_active.length > 0);

const disabledDates = computed(() => {
    const datesArray = props.unavail_dates.map((date) => new Date(date));

    return datesArray;
});

const body = document.getElementsByTagName("body");

onMounted(() => {
    body[0].classList.add("overflow-hidden");
});

onUnmounted(() => {
    body[0].classList.remove("overflow-hidden");
});
</script>

<template>
    <div class="min-h-screen">
        <DashboardHeader :current-user="auth.user" />

        <main class="flex relative">
            <div class="hidden bg-white lg:flex h-full flex-col px-6 py-12 lg:w-[60]">
                <div class="flex-grow">
                    <div  class="flex flex-col space-y-5 mb-6 lg:mb-12">
                        <BaseModal v-if="has_subscription">
                            <template #button>
                                <div
                                    class="flex items-center group px-3 py-0.5 font-display text-center rounded-md transition duration-150 text-white ease-in-out bg-green-500 hover:bg-green-600 border-2 border-transparent">
                                    <IconSchedule class="text-white w-4 h-4 opacity-75 mr-2" />
                                    <span class="text-xs lg:text-base">
                                        Schedule
                                        <span class="hidden lg:inline-block">
                                            collection
                                        </span>
                                    </span>
                                </div>
                            </template>

                            <template #content>
                                <ScheduleCollection :disabled-dates="disabledDates" />
                            </template>
                        </BaseModal>

                        <!-- <BaseModal :disabled="hasActive">
                            <template #button>
                                <div
                                    class="flex items-center px-3 lg:px-6 py-0.5 font-display text-xs lg:text-base text-center rounded-md border-2 border-transparent transition duration-150 ease-in-out border-blue-500 text-black group-hover:bg-blue-500 group-hover:text-white mb-2"
                                >
                                    <IconLock
                                        class="text-blue-500 group-hover:text-white w-3 lg:w-5 h-3 lg:h-5 opacity-50 mr-2"
                                    />
                                    Open collection
                                </div>
                            </template>

                            <template #content>
                                <OpenCollection />
                            </template>
                        </BaseModal> -->
                    </div>

                    <nav>
                        <ul class="flex flex-col space-y-3">
                            <li v-if="has_subscription">
                                <BaseLink class="group py-0.5" href="/collections">
                                    <template #icon>
                                        <IconCollection :class="
                                            $page.props.ziggy.location.includes(
                                                'collections'
                                            )
                                                ? 'text-green-500'
                                                : 'group-hover:text-gray-400'
                                        " class="text-gray-200 w-3 lg:w-5 h-3 lg:h-5" />
                                    </template>
                                    Collections
                                </BaseLink>
                            </li>
                            <li>
                                <BaseLink class="group py-0.5" href="/inbox">
                                    <template #icon>
                                        <IconInbox :class="
                                            $page.props.ziggy.location.includes(
                                                'inbox'
                                            )
                                                ? 'text-green-500'
                                                : 'group-hover:text-gray-400'
                                        " class="text-gray-200 w-3 lg:w-5 h-3 lg:h-5" />
                                    </template>
                                    Inbox
                                </BaseLink>
                            </li>
                            <li>
                                <BaseLink class="group py-0.5" href="/favorites">
                                    <template #icon>
                                        <IconHeart :class="
                                            $page.props.ziggy.location.includes(
                                                'favorites'
                                            )
                                                ? 'text-green-500'
                                                : 'group-hover:text-gray-400'
                                        " class="text-gray-200 w-3 lg:w-5 h-3 lg:h-5" />
                                    </template>
                                    Favorites
                                </BaseLink>
                            </li>
                            <li>
                                <BaseLink class="group py-0.5" href="/tags">
                                    <template #icon>
                                        <IconTag :class="
                                            $page.props.ziggy.location.includes(
                                                'tags'
                                            )
                                                ? 'text-green-500'
                                                : 'group-hover:text-gray-400'
                                        " class="text-gray-200 w-3 lg:w-5 h-3 lg:h-5" />
                                    </template>
                                    Tags
                                </BaseLink>
                            </li>
                            <li>
                                <BaseLink class="group py-0.5" :href="`/${$page.props.auth.user.username}`">
                                    <template #icon>
                                        <IconForm
                                            class="text-gray-200 group-hover:text-gray-300 w-3 lg:w-5 h-3 lg:h-5" />
                                    </template>
                                    Request Form
                                </BaseLink>
                            </li>
                        </ul>
                    </nav>
                </div>

                <footer class="flex flex-col space-y-3">
                    <BaseLink class="group py-0.5" href="/settings">
                        <template #icon>
                            <IconUser class="text-gray-200 group-hover:text-gray-300 w-3 lg:w-5 h-3 lg:h-5" />
                        </template>
                        Account
                    </BaseLink>

                    <div class="base-wrapper mx-auto">
                        <Link class="text-sm lg:text-base opacity-50 hover:opacity-100" :href="route('logout')"
                            method="post" as="button" theme="secondary">
                        Sign Out
                        </Link>
                    </div>
                </footer>
            </div>

            <div class="px-6 py-12 lg:px-20 bg-gray-50 flex-grow h-full overflow-y-scroll">
                <div class="max-w-4xl 2xl:max-w-6xl 2xl:mx-auto">
                    <slot />
                </div>
            </div>
        </main>
    </div>
</template>

<style scoped>
main {
    height: calc(100vh - 89.31px);
    overflow: hidden;
}
</style>

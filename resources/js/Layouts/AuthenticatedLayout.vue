<script setup>
import { ref } from "vue";
import { Link } from "@inertiajs/inertia-vue3";

import DashboardHeader from "@/Components/global/DashboardHeader.vue";
import BaseLink from "@/Components/base/BaseLink.vue";
import BaseModal from "@/Components/base/BaseModal.vue";
import ScheduleCollection from "@/Components/dashboard/ScheduleCollection.vue";

// icons
import IconCollection from "@/Components/svg/IconCollection.vue";
import IconInbox from "@/Components/svg/IconInbox.vue";
import IconHeart from "@/Components/svg/IconHeart.vue";
import IconTag from "@/Components/svg/IconTag.vue";
import IconForm from "@/Components/svg/IconForm.vue";
import IconUser from "@/Components/svg/IconUser.vue";
import IconSchedule from "@/Components/svg/IconSchedule.vue";

const showingNavigationDropdown = ref(false);
</script>

<template>
    <div class="min-h-screen">
        <DashboardHeader :currentUser="$page.props.auth.user" />

        <main class="flex relative">
            <div
                class="
                    hidden
                    bg-white
                    lg:flex
                    h-full
                    flex-col
                    px-6
                    py-12
                    lg:w-[60]
                "
            >
                <div class="flex-grow">
                    <div class="flex flex-col space-y-5 mb-6 lg:mb-12">
                        <BaseModal>
                            <template #button>
                                <div
                                    class="
                                        flex
                                        items-center
                                        group
                                        px-3
                                        py-0.5
                                        font-display
                                        text-center
                                        rounded-md
                                        transition
                                        duration-150
                                        text-white
                                        ease-in-out
                                        bg-green-500
                                        hover:bg-green-600
                                        border-2 border-transparent
                                    "
                                >
                                    <IconSchedule
                                        class="
                                            text-white
                                            w-4
                                            h-4
                                            opacity-75
                                            mr-2
                                        "
                                    />
                                    <span class="text-xs lg:text-base">
                                        Schedule
                                        <span class="hidden lg:inline-block">
                                            window
                                        </span>
                                    </span>
                                </div>
                            </template>

                            <template #content>
                                <ScheduleCollection />
                            </template>
                        </BaseModal>
                    </div>

                    <nav>
                        <ul class="flex flex-col space-y-3">
                            <li>
                                <BaseLink class="group" href="dashboard">
                                    <template #icon>
                                        <IconCollection
                                            :class="
                                                $page.props.ziggy.location.includes(
                                                    'collections'
                                                )
                                                    ? 'text-green-500'
                                                    : 'group-hover:text-gray-400'
                                            "
                                            class="
                                                text-gray-200
                                                w-3
                                                lg:w-5
                                                h-3
                                                lg:h-5
                                            "
                                        />
                                    </template>
                                    Collections
                                </BaseLink>
                            </li>
                            <li>
                                <BaseLink class="group" href="/">
                                    <template #icon>
                                        <IconInbox
                                            :class="
                                                $page.props.ziggy.location.includes(
                                                    'inbox'
                                                )
                                                    ? 'text-green-500'
                                                    : 'group-hover:text-gray-400'
                                            "
                                            class="
                                                text-gray-200
                                                group-hover:text-gray-300
                                                w-3
                                                lg:w-5
                                                h-3
                                                lg:h-5
                                            "
                                        />
                                    </template>
                                    Inbox
                                </BaseLink>
                            </li>
                            <li>
                                <BaseLink class="group" href="/">
                                    <template #icon>
                                        <IconHeart
                                            :class="
                                                $page.props.ziggy.location.includes(
                                                    'saved'
                                                )
                                                    ? 'text-green-500'
                                                    : 'group-hover:text-gray-400'
                                            "
                                            class="
                                                text-gray-200
                                                group-hover:text-gray-300
                                                w-3
                                                lg:w-5
                                                h-3
                                                lg:h-5
                                            "
                                        />
                                    </template>
                                    Saved
                                </BaseLink>
                            </li>
                            <li>
                                <BaseLink class="group" href="/">
                                    <template #icon>
                                        <IconTag
                                            :class="
                                                $page.props.ziggy.location.includes(
                                                    'tags'
                                                )
                                                    ? 'text-green-500'
                                                    : 'group-hover:text-gray-400'
                                            "
                                            class="
                                                text-gray-200
                                                group-hover:text-gray-300
                                                w-3
                                                lg:w-5
                                                h-3
                                                lg:h-5
                                            "
                                        />
                                    </template>
                                    Tags
                                </BaseLink>
                            </li>
                            <li>
                                <BaseLink class="group" href="/">
                                    <template #icon>
                                        <IconForm
                                            class="
                                                text-gray-200
                                                group-hover:text-gray-300
                                                w-3
                                                lg:w-5
                                                h-3
                                                lg:h-5
                                            "
                                        />
                                    </template>
                                    Live Form
                                </BaseLink>
                            </li>
                        </ul>
                    </nav>
                </div>

                <footer class="flex flex-col space-y-3">
                    <BaseLink class="group" href="/">
                        <template #icon>
                            <IconUser
                                class="
                                    text-gray-200
                                    group-hover:text-gray-300
                                    w-3
                                    lg:w-5
                                    h-3
                                    lg:h-5
                                "
                            />
                        </template>
                        Account
                    </BaseLink>

                    <div class="base-wrapper mx-auto">
                        <Link
                            class="
                                text-sm
                                lg:text-base
                                opacity-50
                                hover:opacity-100
                            "
                            :href="route('logout')"
                            method="post"
                            as="button"
                            theme="secondary"
                        >
                            Sign Out
                        </Link>
                    </div>
                </footer>
            </div>

            <div
                class="
                    px-6
                    py-12
                    lg:px-20
                    bg-gray-50
                    flex-grow
                    h-full
                    overflow-y-scroll
                "
            >
                <div class="max-w-4xl">
                    <Transition name="fade">
                        <slot />
                    </Transition>
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

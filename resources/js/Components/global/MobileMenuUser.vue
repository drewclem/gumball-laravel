<script setup>
// components
import BaseLink from "@/components/base/BaseLink.vue";
// import BaseModal from "@/components/base/BaseModal.vue";
// import ScheduleCollection from "@/components/dashboard/ScheduleCollection.vue";
// import OpenCollection from "@/components/dashboard/OpenCollection.vue";

// icons
import IconCollection from "@/components/svg/IconCollection.vue";
import IconSchedule from "@/components/svg/IconSchedule.vue";
import IconInbox from "@/components/svg/IconInbox.vue";
import MenuIcon from "@/components/svg/MenuIcon.vue";
import IconClose from "@/components/svg/IconClose.vue";
import IconHeart from "@/components/svg/IconHeart.vue";
import IconForm from "@/components/svg/IconForm.vue";
import IconUser from "@/components/svg/IconUser.vue";
import IconLock from "@/components/svg/IconLock.vue";

defineProps({
    user: {
        type: Object,
        required: true,
        default: () => {},
    },
    currentUser: {
        type: Object,
        required: true,
        default: () => {},
    },
});
</script>

<template>
    <div>
        <!-- <nav role="navigation">
        <ul class="mobile-nav">
            <li>
                <BaseLink
                    class="group"
                    :class="{
                        'router-link-active':
                            route.fullPath.includes('collections'),
                    }"
                    :href="`/${user.user_metadata.username}/collections`"
                >
                    <template #icon>
                        <IconCollection
                            class="
                                text-gray-200
                                group-hover:text-gray-300
                                w-5
                                h-5
                            "
                        />
                    </template>
                    Collections
                </BaseLink>
            </li>
            <li>
                <BaseLink
                    class="group"
                    :class="{
                        'router-link-active': route.fullPath.includes('inbox'),
                    }"
                    :href="`/${user.user_metadata.username}/inbox`"
                >
                    <template #icon>
                        <IconInbox
                            class="
                                text-gray-200
                                group-hover:text-gray-300
                                w-5
                                h-5
                            "
                        />
                    </template>
                    Inbox
                </BaseLink>
            </li>
            <li>
                <BaseLink
                    class="group"
                    :class="{
                        'router-link-active': route.fullPath.includes('saved'),
                    }"
                    :href="`/${user.user_metadata.username}/saved`"
                >
                    <template #icon>
                        <IconHeart
                            class="
                                text-gray-200
                                group-hover:text-gray-300
                                w-5
                                h-5
                            "
                        />
                    </template>
                    Saved
                </BaseLink>
            </li>
            <li>
                <BaseLink
                    class="group"
                    :href="`/${user.user_metadata.username}`"
                >
                    <template #icon>
                        <IconForm
                            class="
                                text-gray-200
                                group-hover:text-gray-300
                                w-5
                                h-5
                            "
                        />
                    </template>
                    Form
                </BaseLink>
            </li>
        </ul>
    </nav>

    <div>
        <ul>
            <li>
                <BaseModal
                    class="px-6 py-3"
                    :disabled="currentUser && !currentUser.subscription_active"
                >
                    <template #button>
                        <div
                            class="
                                flex
                                items-center
                                group
                                px-3
                                lg:px-6
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
                                w-full
                            "
                        >
                            <IconSchedule
                                class="text-white w-5 h-5 opacity-75 mr-2"
                            />
                            <span>Schedule Collection </span>
                        </div>
                    </template>

                    <template #content>
                        <ScheduleCollection />
                    </template>
                </BaseModal>
            </li>
            <li>
                <BaseModal
                    class="px-6 py-3"
                    :disabled="
                        hasActiveCollection ||
                        (currentUser && !currentUser.subscription_active)
                    "
                >
                    <template #button>
                        <div
                            class="
                                flex
                                items-center
                                group
                                px-3
                                lg:px-6
                                py-0.5
                                font-display
                                text-center
                                rounded-md
                                border-2 border-transparent
                                transition
                                duration-150
                                ease-in-out
                                border-blue-500
                                text-black
                                hover:bg-blue-500
                                hover:text-white
                                mb-2
                            "
                        >
                            <IconLock
                                class="
                                    text-blue-500
                                    group-hover:text-white
                                    w-5
                                    h-5
                                    opacity-50
                                    mr-2
                                "
                            />
                            <span> Open Collection</span>
                        </div>
                    </template>

                    <template #content>
                        <OpenCollection />
                    </template>
                </BaseModal>
            </li>
            <li>
                <BaseLink
                    class="px-6 py-3 group"
                    :class="{
                        'router-link-active':
                            route.fullPath.includes('account'),
                    }"
                    :href="`/${user.user_metadata.username}/account`"
                >
                    <template #icon>
                        <IconUser
                            class="
                                text-gray-200
                                group-hover:text-gray-300
                                w-5
                                h-5
                            "
                        />
                    </template>
                    Account
                </BaseLink>
            </li>

            <li>
                <button class="px-6 py-3" @click="handleLogout" type="button">
                    Sign out
                </button>
            </li>
        </ul>
    </div> -->
    </div>
</template>

<style scoped>
.router-link-active svg {
    @apply text-green-500;
}

.mobile-nav li a {
    @apply p-6 border-b border-gray-100;
}
</style>

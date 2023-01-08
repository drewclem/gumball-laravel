<script setup>
import { ref, computed, watch } from "vue";
import { useGlobalLayout } from "@/stores/global";

import GumballLogo from "@/Components/global/GumballLogo.vue";
import MobileMenuUser from "@/Components/global/MobileMenuUser.vue";
import BaseButton from "@/Components/base/BaseButton.vue";
import BaseImage from "@/Components/base/BaseImage.vue";
import MenuIcon from "@/Components/svg/MenuIcon.vue";
import IconClose from "@/Components/svg/IconClose.vue";

const props = defineProps({
    auth: {
        type: Object,
    },
    ziggy: {
        type: Object,
    },
});

const openButtonRef = ref(null);
const closeButtonRef = ref(null);
const isOpen = ref(false);

const { hasOpenModal, toggleMobileMenu } = useGlobalLayout();

watch(props.ziggy.location, (newPath, oldPath) => {
    if (newPath !== oldPath) isOpen.value = false;
    toggleMobileMenu(false);
});

function openMenu() {
    isOpen.value = true;
    toggleMobileMenu(true);

    setTimeout(() => {
        closeButtonRef?.value.focus();
    }, 50);
}
function closeMenu() {
    isOpen.value = false;
    toggleMobileMenu(false);

    setTimeout(() => {
        openButtonRef?.value.focus();
    }, 50);
}
</script>

<template>
    <div class="flex flex-col min-h-screen">
        <header
            class="base-wrapper w-full flex items-center justify-between py-6 z-50 top-0"
        >
            <div>
                <Link href="/">
                    <GumballLogo class="w-40 lg:w-48" />
                    <span class="sr-only">Go to homepage</span>
                </Link>
            </div>

            <nav class="hidden lg:flex font-body items-center">
                <div class="flex flex-col items-end">
                    <template v-if="auth.user">
                        <div class="flex items-center gap-1">
                            <div
                                v-if="auth.user.avatar_path"
                                class="h-10 w-10 rounded-full overflow-hidden mr-2"
                            >
                                <BaseImage
                                    class="h-10 w-10 object-cover"
                                    :src="auth.user.avatar_path"
                                    :alt="auth.user.username"
                                />
                            </div>
                            <Link href="inbox">{{ auth.user.username }}</Link>
                        </div>
                    </template>

                    <div v-else>
                        <BaseButton theme="secondary" href="sign-in">
                            Sign In
                        </BaseButton>

                        <BaseButton href="create-account" class="ml-6">
                            Create an account
                        </BaseButton>
                    </div>

                    <Link
                        v-if="auth.user"
                        class="text-red-600 text-xs"
                        :href="route('logout')"
                        method="post"
                        as="button"
                        >Sign Out</Link
                    >
                </div>
            </nav>

            <div class="mobile lg:hidden">
                <button
                    class="p-2"
                    ref="openButtonRef"
                    @click="openMenu"
                    type="button"
                    :inert="isOpen"
                    aria-label="Open menu"
                >
                    <MenuIcon class="w-8 h-8" title="Open menu" />

                    <Teleport to="body">
                        <div
                            :class="`z-50 fixed lg:hidden inset-0 bg-black transition duration-150 ${
                                isOpen
                                    ? 'bg-opacity-50'
                                    : 'bg-opacity-0 pointer-events-none'
                            }`"
                            :inert="!isOpen"
                            @keydown.esc="closeMenu"
                        >
                            <div
                                :class="`flex transform transition-transform duration-150 ease-in-out ${
                                    isOpen
                                        ? 'translate-x-0'
                                        : 'translate-x-full'
                                }`"
                            >
                                <div @click="closeMenu">
                                    <button
                                        class="flex justify-center items-center bg-white rounded-full ml-4 mr-4 mt-4 h-12 w-12"
                                        ref="closeButtonRef"
                                        @click="closeMenu"
                                        type="button"
                                        aria-label="Close menu"
                                    >
                                        <IconClose
                                            class="h-6 w-6"
                                            alt="close menu"
                                        />
                                    </button>
                                </div>

                                <div class="h-screen w-full bg-white">
                                    <div class="flex flex-col h-full">
                                        <div
                                            v-if="!auth.user"
                                            class="flex flex-col h-full justify-between"
                                        >
                                            <nav role="navigation">
                                                <ul class="nav">
                                                    <li>
                                                        <Link href="sign-in">
                                                            Sign In
                                                        </Link>
                                                    </li>
                                                    <li>
                                                        <Link
                                                            href="create-account"
                                                        >
                                                            Create an Account
                                                        </Link>
                                                    </li>
                                                    <li>
                                                        <Link
                                                            href="terms-of-service"
                                                        >
                                                            Terms and Conditions
                                                        </Link>
                                                    </li>
                                                    <li>
                                                        <Link
                                                            href="/privacy-policy"
                                                        >
                                                            Privacy Policy
                                                        </Link>
                                                    </li>
                                                </ul>
                                            </nav>

                                            <Link
                                                href="/"
                                                class="p-6 block w-3/4"
                                            >
                                                <GumballLogo class="w-full" />
                                            </Link>
                                        </div>
                                        <MobileMenuUser
                                            v-else
                                            :current-user="auth.user"
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </Teleport>
                </button>
            </div>
        </header>

        <main class="flex-grow mb-12">
            <slot />
        </main>

        <footer class="bg-gray-50 py-8">
            <div
                class="flex flex-col lg:flex-row space-y-6 lg:justify-between items-center base-wrapper"
            >
                <a href="block #top ">
                    <GumballLogo class="w-56" />
                </a>

                <nav class="text-sm">
                    <ul class="flex flex-row items-center">
                        <Link class="mr-4" href="/terms-of-service">
                            Terms and Conditions
                        </Link>
                        <Link href="/privacy-policy"> Privacy Policy </Link>
                    </ul>
                </nav>
            </div>
        </footer>
    </div>
</template>

<style lang="postcss" scoped>
nav ul li div a {
    @apply tracking-wider;
}

.nuxt-link-exact-active {
    @apply opacity-100;
}

.nav li a {
    @apply p-6 border-b border-gray-100 block;
}
</style>

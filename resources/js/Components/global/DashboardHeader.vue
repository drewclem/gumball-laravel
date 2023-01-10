<script setup>
import { ref, watch } from "vue";
import { useGlobalLayout } from "@/stores/global";
import { storeToRefs } from "pinia";

import GumballLogo from "@/Components/global/GumballLogo.vue";
import BaseImage from "@/Components/base/BaseImage.vue";
import MobileMenuUser from "@/Components/global/MobileMenuUser.vue";
import MenuIcon from "@/Components/svg/MenuIcon.vue";
import IconClose from "@/Components/svg/IconClose.vue";

const props = defineProps({
    currentUser: {
        type: Object,
        required: true,
    },
});

/**
 * Init refs and state
 */
const openButtonRef = ref(null);
const closeButtonRef = ref(null);

const isOpen = ref(false);

const { toggleMobileMenu } = useGlobalLayout();
const global = useGlobalLayout();
const { isMobileMenuOpen, hasOpenModal } = storeToRefs(global);

function openMenu() {
    toggleMobileMenu(true);

    setTimeout(() => {
        closeButtonRef?.value.focus();
    }, 50);
}

function closeMenu() {
    toggleMobileMenu(false);

    setTimeout(() => {
        openButtonRef?.value.focus();
    }, 50);
}
</script>

<template>
    <header
        class="px-6 py-3 lg:p-6 flex justify-between w-full items-center border-b-2 border-gray-100"
    >
        <div id="top">
            <Link v-if="currentUser" href="/inbox" class="block w-40 lg:w-48">
                <GumballLogo class="w-full" />
            </Link>
        </div>

        <!-- Mobile nav -->
        <div class="mobile lg:hidden">
            <button
                class="py-2"
                ref="openButtonRef"
                @click="openMenu"
                type="button"
                aria-label="Open menu"
            >
                <MenuIcon class="w-8 h-8" title="Open Menu" />

                <Teleport to="body">
                    <div
                        :class="`z-50 fixed lg:hidden inset-0 bg-black transition duration-150 ${
                            isMobileMenuOpen
                                ? 'bg-opacity-50'
                                : 'bg-opacity-0 pointer-events-none'
                        }`"
                        :inert="!isMobileMenuOpen"
                        @keydown.esc="closeMenu"
                    >
                        <div
                            :class="`flex transform transition-transform duration-150 ease-in-out ${
                                isMobileMenuOpen
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

                            <div class="flex flex-col h-screen w-full bg-white">
                                <MobileMenuUser
                                    :user="currentUser"
                                    :current-user="currentUser"
                                />
                            </div>
                        </div>
                    </div>
                </Teleport>
            </button>
        </div>

        <!-- Desktop nav -->
        <div class="hidden lg:flex items-center space-x-6">
            <div class="flex items-center">
                <div
                    v-if="currentUser.avatar_path"
                    class="h-10 w-10 rounded-full overflow-hidden mr-2"
                >
                    <BaseImage
                        class="h-10 w-10 object-cover"
                        :src="currentUser.avatar_path"
                        :alt="currentUser.username"
                    />
                </div>
                <p>{{ currentUser.username }}</p>
            </div>
        </div>
    </header>
</template>

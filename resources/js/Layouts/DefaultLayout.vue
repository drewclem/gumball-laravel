<script setup>
import GumballLogo from "@/Components/global/GumballLogo.vue";
import BaseButton from "@/Components/base/BaseButton.vue";
import BaseImage from "@/Components/base/BaseImage.vue";

const props = defineProps({
    auth: {
        type: Object,
    },
});
</script>

<template>
    <div class="flex flex-col min-h-screen">
        <header class="base-wrapper w-full flex items-center justify-between py-6 z-50 top-0">
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
                            <div v-if="auth.user.avatar_path" class="h-10 w-10 rounded-full overflow-hidden mr-2">
                                <BaseImage class="h-10 w-10 object-cover" :src="auth.user.avatar_path"
                                    :alt="auth.user.username" />
                            </div>
                            <Link href="inbox">{{
                                    auth.user.username
                            }}</Link>
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

                    <Link v-if="auth.user" class="text-red-600 text-xs" :href="route('logout')" method="post"
                        as="button">Sign Out</Link>
                </div>
            </nav>
        </header>

        <main class="flex-grow mb-12">
            <slot />
        </main>

        <footer class="bg-gray-50 py-8">
            <div class="flex flex-col lg:flex-row space-y-6 lg:justify-between items-center base-wrapper">
                <a href="block #top ">
                    <GumballLogo class="w-56" />
                </a>

                <nav class="text-sm">
                    <ul class="flex flex-row items-center">
                        <Link class="mr-4" href="/terms-of-service">
                        Terms and Conditions
                        </Link>
                        <Link href="/privacy-policy">
                        Privacy Policy
                        </Link>
                    </ul>
                </nav>
            </div>
        </footer>
    </div>
</template>

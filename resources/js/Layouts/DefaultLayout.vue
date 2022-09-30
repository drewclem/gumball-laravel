<script setup>
import GumballLogo from "@/Components/global/GumballLogo.vue";
import BaseButton from "@/Components/base/BaseButton.vue";
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
                    <template v-if="$page.props.auth.user">
                        <Link href="collections">{{
                            $page.props.auth.user.name
                        }}</Link>
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
                        v-if="$page.props.auth.user"
                        class="mt-2 text-xs opacity-75 hover:opacity-100"
                        :href="route('logout')"
                        method="post"
                        as="button"
                        >Sign Out</Link
                    >
                </div>
            </nav>
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
                        <router-link class="mr-4" to="/terms-and-conditions">
                            Terms and Conditions
                        </router-link>
                        <router-link to="/privacy-policy"
                            >Privacy Policy</router-link
                        >
                    </ul>
                </nav>
            </div>
        </footer>
    </div>
</template>

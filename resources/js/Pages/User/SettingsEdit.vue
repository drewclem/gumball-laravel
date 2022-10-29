<script>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
export default {
    layout: AuthenticatedLayout,
};
</script>

<script setup>
// utils
import { onMounted, reactive, watchEffect } from "vue";

// components
import BaseImage from "@/components/base/BaseImage.vue";
import BaseLink from "@/components/base/BaseLink.vue";
import BaseHeading from "@/components/base/BaseHeading.vue";
import BaseRichText from "@/components/base/BaseRichText.vue";
import BaseTextarea from "@/components/base/BaseTextarea.vue";
import BaseInput from "@/components/base/BaseInput.vue";
import IconArrowLeft from "@/components/svg/IconArrowLeft.vue";
import IconUserCircle from "@/components/svg/IconUserCircle.vue";

const props = defineProps({
    auth: {
        type: Object,
        required: true,
    },
});

const userForm = reactive({
    username: null,
    full_name: null,
    email: null,
    instagram_url: null,
    tiktok_url: null,
    twitter_url: null,
    facebook_url: null,
    prescreen: null,
    decline_response: null,
    submitting: false,
});

// const state = reactive({
//     avatar_url: null,
// });

// watchEffect(() => {
//     (userForm.username = auth.user.value?.username),
//         (userForm.full_name = auth.user.value?.full_name),
//         (userForm.email = auth.user.value?.email),
//         (userForm.instagram_url = auth.user.value?.instagram_url),
//         (userForm.tiktok_url = auth.user.value?.tiktok_url),
//         (userForm.twitter_url = auth.user.value?.twitter_url),
//         (userForm.facebook_url = auth.user.value?.facebook_url);
//     userForm.prescreen = auth.user.value?.prescreen;
//     userForm.decline_response = auth.user.value?.decline_response;
// });

// async function downloadAvatar(fileName) {
//     const { error, data } = await supabase.storage
//         .from("avatars")
//         .createSignedUrl(fileName, 60);

//     state.avatar_url = data.signedURL;
// }

// async function updateUserInfo() {
//     userForm.submitting = true;
//     const { error } = await supabase.from("profiles").upsert({
//         id: auth.user.value?.id,
//         full_name: userForm.full_name,
//         email: userForm.email,
//         instagram_url:
//             userForm.instagram_url === "" ? null : userForm.instagram_url,
//         tiktok_url: userForm.tiktok_url === "" ? null : userForm.tiktok_url,
//         twitter_url: userForm.twitter_url === "" ? null : userForm.twitter_url,
//         facebook_url:
//             userForm.facebook_url === "" ? null : userForm.facebook_url,
//         prescreen: userForm.prescreen === "" ? null : userForm.prescreen,
//         decline_response:
//             userForm.decline_response === "" ? null : userForm.decline_response,
//     });

//     if (error) {
//         alert("Oops! Something went wrong.");
//     }
//     setTimeout(() => {
//         userForm.submitting = false;

//         router.back();
//     }, 300);

//     setauth.userId(auth.user.value.id);
// }

// onMounted(() => {
//     if (auth.user.value.user_avatar !== null) {
//         downloadAvatar(auth.user.value.user_avatar);
//     }
// });
</script>

<template>
    <div class="max-w-4xl">
        <div class="flex items-center justify-between mb-8">
            <div class="flex">
                <BaseHeading size="h4" tag="h1">Settings</BaseHeading>
            </div>

            <div>
                <BaseLink href="/settings" class="text-red-500 mr-5 underline">
                    Cancel
                </BaseLink>
                <button
                    type="submit"
                    @click="updateUserInfo"
                    class="underline"
                    :disabled="userForm.submitting"
                >
                    {{ userForm.submitting ? "Submitting..." : "Save Changes" }}
                </button>
            </div>
        </div>

        <div v-if="auth.user" class="grid grid-cols-1 lg:grid-cols-10">
            <div class="lg:col-span-2">
                <div class="h-24 w-24 bg-gray-200 rounded-full overflow-hidden">
                    <transition name="fade">
                        <BaseImage
                            v-if="auth.user.avatar_url"
                            class="h-24 w-24 object-cover"
                            :src="auth.user.avatar_url"
                            :alt="auth.user.username"
                        />
                        <IconUserCircle
                            v-else
                            class="w-full h-full text-gray-400"
                        />
                    </transition>
                </div>
            </div>

            <div class="lg:col-span-8">
                <div class="info-group info-list">
                    <div>
                        <BaseInput
                            class="mb-2"
                            v-model="userForm.username"
                            disabled
                        >
                            User Name
                        </BaseInput>
                        <p class="text-xs opacity-50">
                            Contact support to update your username
                        </p>
                    </div>

                    <div>
                        <BaseInput v-model="userForm.full_name"
                            >Full Name</BaseInput
                        >
                    </div>

                    <div>
                        <BaseInput
                            class="mb-2"
                            inputType="email"
                            v-model="userForm.email"
                            disabled
                        >
                            Email
                        </BaseInput>
                        <p class="text-xs opacity-50">
                            Contact support to update your email
                        </p>
                    </div>
                </div>

                <div class="info-group info-grid">
                    <div>
                        <BaseInput v-model="userForm.instagram_url"
                            >Instagram</BaseInput
                        >
                    </div>

                    <div>
                        <BaseInput v-model="userForm.tiktok_url"
                            >Tiktok</BaseInput
                        >
                    </div>

                    <div>
                        <BaseInput v-model="userForm.twitter_url"
                            >Twitter</BaseInput
                        >
                    </div>

                    <div>
                        <BaseInput v-model="userForm.facebook_url"
                            >Facebook</BaseInput
                        >
                    </div>
                </div>

                <div class="info-group info-list">
                    <div>
                        <BaseRichText v-model="userForm.prescreen">
                            Pre-form Screen
                            <template v-slot:helper>
                                Use this to let people know specific info about
                                you before filling out your contact form. Style
                                preference, timeframe, whatever it is- put it
                                here and they'll be notified about it before
                                submitting a request.
                            </template>
                        </BaseRichText>
                    </div>

                    <div>
                        <BaseTextarea
                            v-model="userForm.decline_response"
                            rows="6"
                        >
                            Decline Reponse
                            <template v-slot:helper>
                                The email that will be sent to prospective
                                clients when using the one-click decline button
                            </template>
                        </BaseTextarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.info-heading {
    @apply opacity-50 mb-1;
}

.info-group {
    @apply border-b-2 border-gray-200 pb-12 mb-12;
}

.info-list {
    @apply flex flex-col space-y-6;
}

.info-grid {
    @apply grid lg:grid-cols-2 gap-6;
}
.fade-enter-active,
.fade-leave-active {
    opacity: 1;
    transition: 300ms ease-in-out;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>

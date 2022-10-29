<script>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
export default {
    layout: AuthenticatedLayout,
};
</script>

<script setup>
import { useForm } from "@inertiajs/inertia-vue3";

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

const form = useForm({
    username: props.auth.user.username,
    name: props.auth.user.name,
    email: props.auth.user.email,
    instagram_url: props.auth.user.instagram_url,
    tiktok_url: props.auth.user.tiktok_url,
    twitter_url: props.auth.user.twitter_url,
    facebook_url: props.auth.user.facebook_url,
    prescreen: props.auth.user.prescreen,
    decline_response: props.auth.user.decline_response,
});

const updateUserInfo = () => {
    form.post(route("settings.edit"), {
        user: props.auth.user,
    });
};

// const state = reactive({
//     avatar_url: null,
// });

// watchEffect(() => {
//     (form.username = auth.user.value?.username),
//         (form.name = auth.user.value?.name),
//         (form.email = auth.user.value?.email),
//         (form.instagram_url = auth.user.value?.instagram_url),
//         (form.tiktok_url = auth.user.value?.tiktok_url),
//         (form.twitter_url = auth.user.value?.twitter_url),
//         (form.facebook_url = auth.user.value?.facebook_url);
//     form.prescreen = auth.user.value?.prescreen;
//     form.decline_response = auth.user.value?.decline_response;
// });

// async function downloadAvatar(fileName) {
//     const { error, data } = await supabase.storage
//         .from("avatars")
//         .createSignedUrl(fileName, 60);

//     state.avatar_url = data.signedURL;
// }

// async function updateUserInfo() {
//     form.submitting = true;
//     const { error } = await supabase.from("profiles").upsert({
//         id: auth.user.value?.id,
//         name: form.name,
//         email: form.email,
//         instagram_url:
//             form.instagram_url === "" ? null : form.instagram_url,
//         tiktok_url: form.tiktok_url === "" ? null : form.tiktok_url,
//         twitter_url: form.twitter_url === "" ? null : form.twitter_url,
//         facebook_url:
//             form.facebook_url === "" ? null : form.facebook_url,
//         prescreen: form.prescreen === "" ? null : form.prescreen,
//         decline_response:
//             form.decline_response === "" ? null : form.decline_response,
//     });

//     if (error) {
//         alert("Oops! Something went wrong.");
//     }
//     setTimeout(() => {
//         form.submitting = false;

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
                    :disabled="form.processing"
                >
                    {{ form.processing ? "Submitting..." : "Save Changes" }}
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
                            v-model="form.username"
                            disabled
                        >
                            User Name
                        </BaseInput>
                        <p class="text-xs opacity-50">
                            Contact support to update your username
                        </p>
                    </div>

                    <div>
                        <BaseInput v-model="form.name">Full Name</BaseInput>
                    </div>

                    <div>
                        <BaseInput
                            class="mb-2"
                            inputType="email"
                            v-model="form.email"
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
                        <BaseInput v-model="form.instagram_url"
                            >Instagram</BaseInput
                        >
                    </div>

                    <div>
                        <BaseInput v-model="form.tiktok_url">Tiktok</BaseInput>
                    </div>

                    <div>
                        <BaseInput v-model="form.twitter_url"
                            >Twitter</BaseInput
                        >
                    </div>

                    <div>
                        <BaseInput v-model="form.facebook_url"
                            >Facebook</BaseInput
                        >
                    </div>
                </div>

                <div class="info-group info-list">
                    <div>
                        <BaseRichText v-model="form.prescreen">
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
                        <BaseTextarea v-model="form.decline_response" rows="6">
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

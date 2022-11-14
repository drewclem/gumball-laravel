<script>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
export default {
    layout: AuthenticatedLayout,
};
</script>

<script setup>
// utility
import { reactive, onMounted, computed } from "vue";
import { useForm } from "@inertiajs/inertia-vue3";
import useFileList from "@/utils/file-list";

// components
import BaseImage from "@/components/base/BaseImage.vue";
import BaseHeading from "@/components/base/BaseHeading.vue";
import BaseLink from "@/components/base/BaseLink.vue";
import BaseButton from "@/components/base/BaseButton.vue";
import BaseDropzone from "@/components/base/BaseDropzone.vue";
import BaseFilePreview from "@/components/base/BaseFilePreview.vue";
import IconUserCircle from "@/components/svg/IconUserCircle.vue";
import IconClose from "@/components/svg/IconClose.vue";

const props = defineProps({
    auth: {
        type: Object,
        required: true,
    },
});

const form = useForm({
    avatar: null,
});

const uploadAvatar = () => {
    form.post(route("settings.upload"), {
        onSuccess: () => {
            form.reset();
            files = [];
        },
    });
};

/**
 * Avatar upload
 */
const { files, addFiles, removeFile } = useFileList();

const state = reactive({
    avatarButtonText: "Save",
    uploading: false,
    avatar_url: null,
});

function onInputChange(e) {
    addFiles(e.target.files);
    form.avatar = e.target.files[0];
}

function cancelUpload() {
    removeFile(0);
    form.reset();
}

/**
 * Stripe
 */
let stripe = null;

// onMounted(async () => {
//     try {
//         stripe = await loadStripe(process.env.VUE_APP_STRIPE_PUBLIC_KEY);
//     } catch (err) {
//         alert(err);
//     }
// });

async function manageSubscription() {
    if (!auth.user.value.subscription_active) {
        try {
            const bodyData = {
                customerId: `${auth.user.value.stripe_customer}`,
                username: `${auth.user.value.username}`,
            };

            const stripeSession = await fetch(
                "/.netlify/functions/create-stripe-checkout-session",
                {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify(bodyData),
                }
            );

            const sessionData = await stripeSession.json();

            if (sessionData?.url) {
                window.location.href = sessionData.url;
            }
        } catch (error) {
            alert(error);
        }
    } else {
        const data = {
            customer: `${auth.user.value.stripe_customer}`,
            return_url: `${process.env.VUE_APP_BASE_URL}/${auth.user.value.username}/account`,
        };

        try {
            const sessionUrl = await fetch(
                "/.netlify/functions/stripe-customer-portal",
                {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify(data),
                }
            );

            const rawData = await sessionUrl.json();

            if (rawData) {
                window.location.href = rawData.url;
            }
        } catch (error) {
            alert(error);
        }
    }
}
</script>

<template>
    <div class="max-w-4xl">
        <div class="flex items-center justify-between mb-8">
            <BaseHeading size="h4" tag="h1">Settings</BaseHeading>

            <BaseLink href="/settings/edit">Edit</BaseLink>
        </div>

        <div class="grid grid-cols-1 gap-4 lg:grid-cols-10">
            <div class="lg:col-span-2">
                <div>
                    <form @submit.prevent="uploadAvatar">
                        <label class="cursor-pointer underline">
                            <div
                                class="h-24 w-24 bg-gray-200 rounded-full overflow-hidden mb-2"
                            >
                                <BaseImage
                                    class="h-24 w-24 object-cover"
                                    v-if="
                                        auth.user.avatar_path &&
                                        form.avatar === null
                                    "
                                    :src="`./${auth.user.avatar_path}`"
                                    :alt="`${auth.user.username}`"
                                />

                                <BaseFilePreview
                                    v-else-if="files.length > 0"
                                    :file="files[0]"
                                    tag="div"
                                />

                                <IconUserCircle
                                    v-else
                                    class="w-full h-full text-gray-400"
                                />
                            </div>
                            <span v-if="form.avatar === null">
                                Update avatar
                            </span>
                            <input
                                class="sr-only"
                                @input="onInputChange"
                                type="file"
                                accept="image/*"
                            />
                        </label>

                        <div v-if="form.avatar !== null">
                            <button class="text-green-700" type="submit">
                                Save
                            </button>
                        </div>
                    </form>

                    <button
                        v-if="form.avatar !== null"
                        @click="cancelUpload"
                        type="button"
                        class="text-red-600"
                    >
                        Cancel
                    </button>
                </div>
            </div>

            <div class="lg:col-span-8">
                <div class="info-group info-list">
                    <div>
                        <h2 class="info-heading">Username</h2>
                        <p v-if="auth.user">{{ auth.user.username }}</p>
                        <p v-else>--</p>
                    </div>

                    <div>
                        <h2 class="info-heading">Full Name</h2>
                        <p v-if="auth.user && auth.user.name">
                            {{ auth.user.name }}
                        </p>
                        <p v-else>--</p>
                    </div>

                    <div>
                        <h2 class="info-heading">Email</h2>
                        <p v-if="auth.user && auth.user.email">
                            {{ auth.user.email }}
                        </p>
                        <p v-else>--</p>
                    </div>
                </div>

                <div class="info-group info-grid">
                    <div>
                        <h2 class="info-heading">Instagram</h2>
                        <p v-if="auth.user && auth.user.instagram_url">
                            {{ auth.user.instagram_url }}
                        </p>
                        <p v-else>--</p>
                    </div>

                    <div>
                        <h2 class="info-heading">TikTok</h2>
                        <p v-if="auth.user && auth.user.tiktok_url">
                            {{ auth.user.tiktok_url }}
                        </p>
                        <p v-else>--</p>
                    </div>

                    <div>
                        <h2 class="info-heading">Twitter</h2>
                        <p v-if="auth.user && auth.user.twitter_url">
                            {{ auth.user.twitter_url }}
                        </p>
                        <p v-else>--</p>
                    </div>

                    <div>
                        <h2 class="info-heading">Facebook</h2>
                        <p v-if="auth.user && auth.user.facebook_url">
                            {{ auth.user.facebook_url }}
                        </p>
                        <p v-else>--</p>
                    </div>
                </div>

                <div class="info-group info-list">
                    <div class="">
                        <h2 class="info-heading">Pre-screen</h2>
                        <p class="text-xs mb-4 lg:w-3/4 opacity-50">
                            Use this to let people know specific info about you
                            before filling out your contact form. Style
                            preference, timeframe, whatever it is- put it here
                            and they'll be notified about it before submitting a
                            request.
                        </p>
                        <div
                            v-if="auth.user && auth.user.prescreen"
                            v-html="auth.user && auth.user.prescreen"
                        />
                        <p v-else>--</p>
                    </div>

                    <div>
                        <h2 class="info-heading">Decline Response</h2>
                        <div
                            v-if="auth.user && auth.user.decline_response"
                            v-html="auth.user && auth.user.decline_response"
                        />
                        <p v-else>--</p>
                    </div>
                </div>

                <div class="info-group info-list">
                    <p v-if="auth.user">
                        Subscription status:
                        <span
                            :class="
                                auth.user.subscription_active
                                    ? 'text-green-500'
                                    : 'text-red-500'
                            "
                            >{{
                                auth.user.subscription_active
                                    ? "Active"
                                    : "Inactive"
                            }}</span
                        >
                    </p>

                    <p
                        class="lg:w-1/2 text-sm opacity-50"
                        v-if="auth.user.subscription_active"
                    >
                        Your subscription is currently inactive. You can still
                        access any previous collections and submissions.
                    </p>

                    <div>
                        <BaseButton type="primary" @click="manageSubscription">
                            Manage Subscription
                        </BaseButton>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.info-heading {
    @apply opacity-50 mb-1 font-display;
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

button:disabled {
    @apply opacity-50;
}

.fade-enter-active,
.fade-leave-active {
    opacity: 1;
    transition: 150ms ease-in-out;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>

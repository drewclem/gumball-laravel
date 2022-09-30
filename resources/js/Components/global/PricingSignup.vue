<script setup>
// utils
import { reactive } from "vue";

// components
import BaseHeading from "@/Components/base/BaseHeading.vue";
import BaseButton from "@/Components/base/BaseButton.vue";
import BaseText from "@/Components/base/BaseText.vue";
import BaseInput from "@/Components/base/BaseInput.vue";
import IconCheck from "@/Components/svg/IconCheck.vue";

const props = defineProps({
    showPricing: {
        type: Boolean,
        default: false,
    },
});

const state = reactive({
    email: "",
    newsletter: true,
    responseMessage: null,
    responseStatus: null,
    submitting: false,
});

async function submitAccessForm(e) {
    e.preventDefault();

    state.submitting = true;

    const tagId = 3188672;

    if (state.newsletter) {
        const dataToSend = {
            api_key: process.env.VUE_APP_CONVERT_KIT_KEY,
            email: state.email,
        };

        const options = {
            method: "POST",
            headers: { "Content-type": "application/json; charset=UTF-8" },
            body: JSON.stringify(dataToSend),
        };

        try {
            await fetch(
                `https://api.convertkit.com/v3/tags/${tagId}/subscribe`,
                options
            ).then((res) => {
                if (!res.ok) {
                    alert("Oops! Something went wrong subscribing.");
                }
            });

            await submitNetlifyForm();
        } catch (err) {
            console.log(err.message);
        }
    } else {
        await submitNetlifyForm();
    }

    setTimeout(() => {
        state.submitting = false;
    }, 3000);
}

function encode(data) {
    return Object.keys(data)
        .map(
            (key) =>
                `${encodeURIComponent(key)}=${encodeURIComponent(data[key])}`
        )
        .join("&");
}

function submitNetlifyForm() {
    const data = {
        email: state.email,
        newsletter: state.newsletter,
    };
    try {
        fetch("/", {
            method: "post",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: encode({
                "form-name": "Early access",
                ...data,
            }),
        }).then((res) => {
            if (res.status === 200) {
                state.responseMessage =
                    "We appreciate you're interest! We'll be in contact soon.";
                state.responseStatus = "success";
            } else {
                state.responseMessage =
                    "Oops! Looks like something went wrong. Please try again!";
                state.responseStatus = "error";
            }
        });

        setTimeout(() => {
            state.responseMessage = null;
            state.responseStatus = null;
            state.email = "";
        }, 10000);
    } catch (err) {
        console.log(err.message);
    }
}
</script>

<style scoped>
ul li {
    @apply flex space-x-3;
}
</style>

<template>
    <section class="narrow-wrapper my-32 lg:my-52">
        <div v-if="showPricing" class="grid lg:grid-cols-5 gap-12 mb-12">
            <div class="flex flex-col space-y-6 lg:col-span-3">
                <BaseHeading size="h3">
                    Everything you need to make filling your books easier on
                    you.
                </BaseHeading>

                <div class="flex items-center space-x-6">
                    <BaseHeading
                        size="h5"
                        class="text-lg text-red-500 whitespace-nowrap"
                    >
                        What&apos;s included
                    </BaseHeading>
                    <hr class="w-full" />
                </div>

                <ul class="grid lg:grid-cols-2 gap-y-4">
                    <li>
                        <IconCheck class="h-5 w-5 text-green-500" />
                        <p>Unlimited collections &amp; submissions!</p>
                    </li>
                    <li>
                        <IconCheck class="h-5 w-5 text-green-500" />
                        <p>Open, close, &amp; schedule your books</p>
                    </li>
                    <li>
                        <IconCheck class="h-5 w-5 text-green-500" />
                        <p>Collect reference material</p>
                    </li>
                    <li>
                        <IconCheck class="h-5 w-5 text-green-500" />
                        <p>Instant search and filtering</p>
                        <!-- <p class="relative line-through">
              Custom tagging/sorting
              <span
                class="block absolute left-0 bottom-0 text-xs text-red-500 -mb-4"
              >
                In development
              </span>
            </p> -->
                        <p></p>
                    </li>
                </ul>
            </div>

            <div
                class="lg:col-span-2 bg-gray-100 flex flex-col space-y-6 justify-center items-center rounded-lg w-full py-6"
            >
                <p class="relative text-green-500 text-9xl font-bold">
                    <span class="text-3xl absolute left-0 -ml-4 mt-8">$</span>
                    10
                    <span
                        class="text-base font-normal text-black absolute bottom-0 left-0 right-0 ml-8 -mb-2"
                        >/month</span
                    >
                </p>

                <BaseButton href="create-account" theme="secondary">
                    Create an Account
                </BaseButton>
            </div>
        </div>

        <!-- <div class="hidden lg:grid-cols-3 gap-6 lg:gap-12">
            <div>
                <BaseHeading class="mb-4" size="h4" tag="h3">
                    Release stage:<span class="text-red-500">
                        Alpha testing
                    </span>
                </BaseHeading>

                <BaseText size="small">
                    We’re currently in the early-stages of testing and
                    development so access to the platform is invite-only.<br /><br />If
                    you’d like to request early access at our early bird rate,
                    provide your email here and we’ll reach out to you!
                </BaseText>
            </div>

            <div class="lg:col-span-2">
                <form
                    @submit.prevent="submitAccessForm"
                    name="Early access"
                    data-netlify="true"
                    data-netlify-honeypot="bot-field"
                >
                    <input
                        type="hidden"
                        name="form-name"
                        value="Early access"
                    />
                    <div class="relative flex flex-col mb-2">
                        <label
                            class="font-display mb-2 text-sm ml-3"
                            for="email"
                            >Email *</label
                        >
                        <div class="relative">
                            <input
                                id="email"
                                name="email"
                                v-model="state.email"
                                class="
                                    border border-gray-300
                                    rounded-md
                                    px-2
                                    py-1
                                    w-full
                                "
                                :disabled="state.submitting"
                                required
                            />

                            <div class="absolute mt-2">
                                <input
                                    id="newsletter"
                                    name="newsletter"
                                    v-model="state.newsletter"
                                    type="checkbox"
                                    class="mr-1"
                                />
                                <label for="newsletter">
                                    Sign up for the Heygumball newsletter for
                                    updates!
                                </label>
                            </div>

                            <button
                                class="
                                    absolute
                                    right-0
                                    bg-green-500
                                    text-white
                                    font-bold
                                    px-6
                                    h-full
                                    rounded-r-lg
                                "
                                type="submit"
                                :disabled="state.submitting"
                            >
                                Send
                            </button>
                        </div>

                        <p
                            v-if="state.responseMessage"
                            :class="{
                                'text-red-500':
                                    state.responseStatus === 'error',
                                'text-green-500':
                                    state.responseStatus === 'success',
                            }"
                            class="mt-16 lg:mt-8 text-sm"
                        >
                            {{ state.responseMessage }}
                        </p>

                        <p class="hidden">
                            <label>
                                Don’t fill this out if you're human:
                                <input name="bot-field" />
                            </label>
                        </p>
                    </div>
                </form>
            </div>
        </div> -->
    </section>
</template>

<script setup>
// utils
import { Head, useForm } from "@inertiajs/inertia-vue3";

//components
import DefaultLayout from "@/Layouts/DefaultLayout.vue";
import BaseHeading from "@/Components/base/BaseHeading.vue";
import BaseInput from "@/Components/base/BaseInput.vue";
import BaseButton from "@/Components/base/BaseButton.vue";
import InputError from "@/Components/InputError.vue";
import BaseText from "@/Components/base/BaseText.vue";
import CreateAccount from "@/Components/svg/CreateAccount.vue";

const props = defineProps({
    status: String,
});

const form = useForm({
    name: "",
    username: "",
    email: "",
    password: "",
    password_confirmation: "",
    terms: false,
});

const submit = () => {
    form.post(route("register"), {
        onFinish: () => form.reset("password", "password_confirmation"),
    });
};
</script>

<template>
    <Head title="Create an account" />

    <DefaultLayout>
        <section>
            <div
                class="
                    max-w-4xl
                    mx-auto
                    px-6
                    xl:px-0
                    grid grid-cols-1
                    lg:grid-cols-2
                    gap-24
                    items-center
                "
            >
                <form class="mt-12" @submit.prevent="submit">
                    <BaseHeading class="mb-5" size="h4" tag="h1">
                        Create an account
                    </BaseHeading>

                    <div class="flex flex-col space-y-6">
                        <div class="relative">
                            <BaseInput v-model="form.username" required>
                                Username
                            </BaseInput>

                            <InputError
                                class="mt-2"
                                :message="form.errors.username"
                            />
                        </div>

                        <div class="relative">
                            <BaseInput v-model="form.name" required>
                                Name
                            </BaseInput>

                            <InputError
                                class="mt-2"
                                :message="form.errors.name"
                            />
                        </div>

                        <div class="relative">
                            <BaseInput v-model="form.email" required>
                                Email
                            </BaseInput>

                            <InputError
                                class="mt-2"
                                :message="form.errors.email"
                            />
                        </div>

                        <div class="relative">
                            <BaseInput
                                v-model="form.password"
                                inputType="password"
                                required
                            >
                                Password
                            </BaseInput>

                            <InputError
                                class="mt-2"
                                :message="form.errors.password"
                            />
                        </div>

                        <div class="relative">
                            <BaseInput
                                v-model="form.password_confirmation"
                                inputType="password"
                                required
                            >
                                Confirm Password
                            </BaseInput>

                            <InputError
                                class="mt-2"
                                :message="form.errors.password_confirmation"
                            />
                        </div>

                        <div class="relative">
                            <input
                                id="terms"
                                name="terms"
                                v-model="form.terms"
                                type="checkbox"
                                class="mr-1"
                            />
                            <label for="terms">
                                I agree to the
                                <router-link
                                    to="/terms-and-conditions"
                                    class="text-blue-500 underline"
                                >
                                    terms and conditions
                                </router-link>
                                of Heygumball
                            </label>
                            <InputError
                                class="mt-2"
                                :message="form.errors.terms"
                            />
                        </div>

                        <div class="w-full lg:w-auto ml-auto">
                            <BaseButton
                                type="submit"
                                theme="tertiary"
                                class="w-full"
                                :disabled="
                                    form.processing === 'submitting' ||
                                    !form.terms
                                "
                            >
                                {{
                                    form.processing === "submitting"
                                        ? "Submitting"
                                        : "Create Account"
                                }}
                            </BaseButton>
                        </div>
                    </div>
                </form>

                <div class="relative">
                    <CreateAccount class="w-full" />
                </div>
            </div>
        </section>
    </DefaultLayout>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 300ms ease-in-out;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>

<script setup>
// utils
import { Head, Link, useForm } from "@inertiajs/inertia-vue3";

//components
import DefaultLayout from "@/Layouts/DefaultLayout.vue";
import BaseHeading from "@/Components/base/BaseHeading.vue";
import BaseInput from "@/Components/base/BaseInput.vue";
import BaseButton from "@/Components/base/BaseButton.vue";
import WelcomeBack from "@/Components/svg/WelcomeBack.vue";

defineProps({
    canResetPassword: Boolean,
    status: String,
});

const form = useForm({
    email: "",
    password: "",
    remember: false,
});

const submit = () => {
    form.post(route("login"), {
        onFinish: () => form.reset("password"),
    });
};
</script>

<template>
    <DefaultLayout>
        <section>
            <div
                class="
                    max-w-3xl
                    mx-auto
                    px-6
                    xl:px-0
                    grid grid-cols-1
                    lg:grid-cols-2
                    gap-24
                    items-center
                "
            >
                <form @submit.prevent="submit">
                    <BaseHeading class="mb-5" size="h4" tag="h1"
                        >Welcome back!</BaseHeading
                    >

                    <div class="flex flex-col space-y-6">
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
                                inputType="password"
                                required
                                v-model="form.password"
                            >
                                Password
                            </BaseInput>

                            <InputError
                                class="mt-2"
                                :message="form.errors.password"
                            />
                        </div>

                        <div class="w-full">
                            <BaseButton
                                class="w-full lg:w-auto ml-auto"
                                type="submit"
                                theme="tertiary"
                                :disabled="form.processing"
                            >
                                Sign In
                            </BaseButton>
                        </div>
                    </div>
                </form>

                <div class="relative">
                    <WelcomeBack class="w-full" />
                </div>
            </div>
        </section>
    </DefaultLayout>
</template>

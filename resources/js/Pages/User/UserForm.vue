<script>
import DefaultLayout from "@/Layouts/DefaultLayout.vue";
export default {
    layout: DefaultLayout,
};
</script>

<script setup>
// utils
import { computed, ref } from "vue";
import useFileList from "@/utils/file-list";
import { Head, useForm } from "@inertiajs/inertia-vue3";

// recaptcha
import { VueRecaptcha } from "vue-recaptcha";

// components
import BaseHeading from "@/components/base/BaseHeading.vue";
import BaseText from "@/components/base/BaseText.vue";
import BaseInput from "@/components/base/BaseInput.vue";
import BaseRichText from "@/components/base/BaseRichText.vue";
import BaseButton from "@/components/base/BaseButton.vue";
import BaseImage from "@/components/base/BaseImage.vue";
import BaseDropzone from "@/components/base/BaseDropzone.vue";
import BaseFilePreview from "@/components/base/BaseFilePreview.vue";

const props = defineProps({
    collection: Array,
    user: Object,
});

const { files, addFiles, removeFile } = useFileList();

const form = useForm({
    name: "",
    email: "",
    phone: "",
    message: "",
    images: files,
    toc: false,
    recaptcha: false,
    collection_id: props?.collection[0]?.id,
    user_id: props.user.id,
});

const step = ref(1);

const submit = () => {
    form.post(route("submission.store"), {
        onSuccess: () => (step.value = 2),
    });
};

const sitekey = import.meta.env.VITE_RECAPTCHA_SITE_KEY;

const hasCollection = computed(() => {
    return props.collection.length > 0;
});

function onInputChange(e) {
    addFiles(e.target.files);
    e.target.value = null;
}
</script>

<template>
    <div class="relative">

        <Head :title="user.username" />
        <div class="absolute bg-gray-100 top-0 w-full h-56" />

        <transition name="fade" appear>
            <div class="relative max-w-lg xl:max-w-2xl mx-auto mb-12">
                <div class="flex justify-between items-center py-8 lg:py-12 px-6 lg:p-11">
                    <BaseHeading size="h4" tag="h1">
                        Contact
                        <span class="text-blue-500">
                            <span>@{{ user.username }}</span>
                        </span>
                    </BaseHeading>

                    <div class="h-16 w-16 bg-gray-400 rounded-full overflow-hidden">
                        <BaseImage v-if="user.avatar_path" :src="`./${user.avatar_path}`" :alt="user.username"
                            class="h-16 w-16 object-cover" />
                    </div>
                </div>

                <div class="card-shadow bg-white rounded-xl mx-6 lg:mx-0 p-6 lg:p-11">
                    <div v-if="!hasCollection">
                        <BaseHeading tag="h2" size="h3" class="text-red-500 mb-5">
                            Uh oh!
                        </BaseHeading>

                        <BaseText>
                            It looks like their books are currently closed.
                        </BaseText>
                        <BaseText>Please try again later.</BaseText>
                    </div>

                    <div class="flex flex-col space-y-6" v-else-if="hasCollection && user.prescreen !== null">
                        <BaseHeading size="h3" tag="h2">
                            Read first:
                        </BaseHeading>

                        <div v-html="user.prescreen" />

                        <div>
                            <BaseButton @click="showForm = true" theme="tertiary">
                                Confirm
                            </BaseButton>
                        </div>
                    </div>

                    <div v-else>
                        <form v-if="step === 1" class="flex flex-col gap-8" @submit.prevent="submit">
                            <div class="relative">
                                <BaseInput v-model="form.name" required>
                                    Name
                                </BaseInput>

                                <InputError class="mt-2" :message="form.errors.name" />
                            </div>

                            <div class="relative">
                                <BaseInput v-model="form.email" input-type="email" required>
                                    Email
                                </BaseInput>

                                <InputError class="mt-2" :message="form.errors.email" />
                            </div>

                            <div class="relative">
                                <BaseInput v-model="form.phone" input-type="tel" required>
                                    Phone
                                </BaseInput>

                                <InputError class="mt-2" :message="form.errors.phone" />
                            </div>

                            <div class="relative">
                                <BaseRichText v-model="form.message" required>
                                    Message
                                </BaseRichText>

                                <InputError class="mt-2" :message="form.errors.message" />
                            </div>

                            <div>
                                <p class="font-display text-sm">Add images</p>
                                <p class="text-xs mb-4 opacity-50">
                                    Accepted file types: jpeg, jpg, png, heic
                                </p>
                                <BaseDropzone @files-dropped="addFiles" #default="{ dropZoneActive }">
                                    <label class="text-sm" for="file-input">
                                        <span v-if="dropZoneActive">
                                            <span>Drop Them Here</span>
                                        </span>
                                        <span v-else>
                                            <span>Drag Your Files Here</span>
                                            <span class="text-sm">
                                                or
                                                <strong class="text-red-500"><em>click here</em></strong>
                                                to select files
                                            </span>
                                        </span>

                                        <input type="file" id="file-input" accept="image/*" multiple
                                            @change="onInputChange" />
                                    </label>

                                    <ul class="grid grid-cols-4 gap-2 mt-3" v-show="files.length">
                                        <BaseFilePreview v-for="(file, index) of files" :key="file.id" :file="file"
                                            tag="li" @remove="removeFile(index)" />
                                    </ul>
                                </BaseDropzone>
                            </div>

                            <div class="border-t-2 border-gray-100" />

                            <div>
                                <VueRecaptcha :sitekey="sitekey" :load-recaptcha-script="true"
                                    @verify="form.recaptcha = true" />
                            </div>

                            <div class="relative">
                                <input id="terms" name="terms" v-model="form.toc" type="checkbox" class="mr-1" />
                                <label for="terms">
                                    I agree to the
                                    <Link href="/terms-and-conditions" class="text-blue-500 underline">
                                    terms and conditions
                                    </Link>
                                    of Heygumball
                                </label>
                                <InputError class="mt-2" :message="form.errors.terms" />
                            </div>

                            <div class="lg:ml-auto">
                                <BaseButton class="w-full" theme="tertiary" type="submit"
                                    :disabled="form.processing || !form.toc">
                                    Submit
                                </BaseButton>
                            </div>
                        </form>

                        <div v-else>
                            <BaseHeading tag="h2" size="h3" class="text-green-500 mb-5">
                                Thanks for submitting!
                            </BaseHeading>

                            <BaseText>
                                <span> @{{ user.username }} </span>
                                will be in touch!
                            </BaseText>
                        </div>
                    </div>
                </div>
            </div>
        </transition>
    </div>
</template>

<style scoped>
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

<script setup>
// utils
import { reactive } from "vue";
import { useDates } from "@/utils/dates";
import { useForm } from "@inertiajs/inertia-vue3";

// components
import BaseHeading from "@/components/base/BaseHeading.vue";
import BaseText from "@/components/base/BaseText.vue";
import BaseButton from "@/components/base/BaseButton.vue";
import IconArrowLeft from "@/components/svg/IconArrowLeft.vue";
import Datepicker from "vue3-datepicker";
import CopyShareLink from "@/components/dashboard/CopyShareLink.vue";

const { currentDate, tomorrowDate } = useDates();

const state = reactive({
    step: 0,
});

const form = useForm({
    start_date: currentDate,
    end_date: null,
    is_archived: false,
});

function incrementStep() {
    state.step++;
}

const submit = () => {
    form.post(route("collection"), {
        onFinish: () => (state.step = 3),
    });
};
</script>

<template>
    <div>
        <div class="flex flex-col space-y-6" v-if="state.step === 0">
            <div>
                <BaseHeading class="mb-1" size="h4" tag="h1">
                    Would you like to schedule an end date?
                </BaseHeading>

                <BaseText size="small">
                    If not, your collection form will remain open until you
                    close it manually.
                </BaseText>
            </div>

            <div class="ml-auto flex">
                <form @submit.prevent="submit">
                    <BaseButton
                        class="mr-4"
                        theme="subdued"
                        type="submit"
                        :disabled="form.processing"
                    >
                        No
                    </BaseButton>
                </form>
                <BaseButton theme="tertiary" @user-click="state.step++">
                    Yes
                </BaseButton>
            </div>
        </div>

        <div class="flex flex-col space-y-6 w-full" v-if="state.step === 1">
            <div>
                <button
                    class="text-sm opacity-50 hover:opacity-100"
                    type="button"
                    @click="state.step--"
                >
                    <IconArrowLeft class="h-3 w-3 inline -mt-0.5" />
                    Back
                </button>
                <BaseHeading class="mb-1" size="h4" tag="h1">
                    Select an end date
                </BaseHeading>
            </div>

            <form @submit.prevent="submit">
                <p class="font-display mb-2 text-sm">End date</p>
                <Datepicker
                    class="mb-6"
                    v-model="form.end_date"
                    :lower-limit="tomorrowDate"
                    :disabled-dates="disabledDates"
                    starting-view="day"
                />

                <BaseButton
                    class="block ml-auto"
                    type="submit"
                    theme="tertiary"
                    @user-click="openCollection"
                    :disabled="form.processing"
                >
                    Confirm
                </BaseButton>
            </form>
        </div>

        <div class="flex flex-col space-y-6" v-if="state.step === 3">
            <BaseHeading class="mb-1 text-blue-500" size="h3" tag="h1">
                You're live!
            </BaseHeading>

            <BaseText> Share your public link for booking requests. </BaseText>

            <CopyShareLink />
        </div>
    </div>
</template>

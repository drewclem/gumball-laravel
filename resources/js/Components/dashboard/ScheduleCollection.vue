<script setup>
// utils
import { reactive } from "vue";
import { useDates } from "@/utils/dates";
import { useForm } from "@inertiajs/inertia-vue3";

// components
import BaseHeading from "@/Components/base/BaseHeading.vue";
import BaseText from "@/Components/base/BaseText.vue";
import BaseButton from "@/Components/base/BaseButton.vue";
import IconArrowLeft from "@/Components/svg/IconArrowLeft.vue";
import Datepicker from "vue3-datepicker";
import CopyShareLink from "@/Components/dashboard/CopyShareLink.vue";

const state = reactive({
    step: 0,
});

const form = useForm({
    start_date: "",
    end_date: "",
    is_archived: false,
});

const { currentDate, tomorrowDate } = useDates();

const submit = () => {
    form.post(route("collections.store"), {
        onFinish: () => state.step++,
    });
};
</script>

<template>
    <div>
        <form
            class="flex flex-col space-y-6"
            v-if="state.step === 0"
            @submit.prevent="submit"
        >
            <div>
                <BaseHeading class="mb-1" size="h4" tag="h1">
                    Schedule Collection
                </BaseHeading>
            </div>

            <div class="flex flex-col lg:flex-row space-6 gap-6">
                <div>
                    <p class="font-display mb-2 text-sm">Start date</p>
                    <Datepicker
                        class="mb-6"
                        v-model="form.start_date"
                        :lower-limit="currentDate"
                        :disabled-dates="disabledDates"
                    />
                </div>

                <div>
                    <p class="font-display mb-2 text-sm">End date</p>
                    <Datepicker
                        class="mb-6"
                        v-model="form.end_date"
                        :lower-limit="tomorrowDate"
                        :disabled-dates="disabledDates"
                    />
                </div>
            </div>

            <div class="ml-auto">
                <BaseButton
                    theme="tertiary"
                    type="submit"
                    :disabled="form.processing"
                >
                    {{ !form.processing ? "Schedule" : "Scheduling..." }}
                </BaseButton>
            </div>
        </form>

        <div class="flex flex-col space-y-6" v-if="state.step === 1">
            <BaseHeading class="mb-1 text-blue-500" size="h3" tag="h1">
                You're live!
            </BaseHeading>

            <BaseText> Share your public link for booking requests. </BaseText>

            <CopyShareLink />
        </div>
    </div>
</template>

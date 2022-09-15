<template>
  <div>
    <div class="flex flex-col space-y-6" v-if="state.step === 0">
      <div>
        <BaseHeading class="mb-1" size="h4" tag="h1">
          Would you like to schedule an end date?
        </BaseHeading>

        <BaseText size="small">
          If not, your profile will remain open until it's closed manually.
        </BaseText>
      </div>

      <div class="ml-auto">
        <BaseButton
          class="mr-4"
          theme="subdued"
          @user-click="openCollection"
          :disabled="state.submitting"
        >
          No
        </BaseButton>
        <BaseButton theme="tertiary" @user-click="state.step++">Yes</BaseButton>
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

      <div>
        <p class="font-display mb-2 text-sm">End date</p>
        <Datepicker
          class="mb-6"
          v-model="state.endDate"
          :lower-limit="tomorrowDate"
          :disabled-dates="disabledDates"
          starting-view="day"
        />

        <BaseButton
          class="block ml-auto"
          theme="tertiary"
          @user-click="openCollection"
          :disabled="state.submitting"
        >
          Confirm
        </BaseButton>
      </div>
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

<script setup>
// utils
import { reactive } from "vue";
import useSupabase from "@/utils/useSupabase";
import { useUserStore } from "@/stores/user";
import { useDates } from "@/utils/dates";

// components
import BaseHeading from "@/components/base/BaseHeading.vue";
import BaseText from "@/components/base/BaseText.vue";
import BaseButton from "@/components/base/BaseButton.vue";
import IconArrowLeft from "@/components/svg/IconArrowLeft.vue";
import Datepicker from "vue3-datepicker";
import CopyShareLink from "@/components/dashboard/CopyShareLink.vue";

const state = reactive({
  step: 0,
  endDate: null,
  submitting: false,
});

const { currentUser, setCollections, disabledDates } = useUserStore();
const { createCollection } = useSupabase();

const { currentDate, tomorrowDate } = useDates();

function incrementStep() {
  state.step++;
}

function openCollection() {
  state.submitting = true;

  const { data, error } = createCollection({
    startDate: currentDate,
    endDate: state.endDate,
    currentUser: {
      ...currentUser,
    },
  });

  if (error) {
    alert(error);
  } else {
    setCollections();

    setTimeout(() => {
      state.submitting = false;
      state.step = 3;
    }, 100);
  }
}
</script>

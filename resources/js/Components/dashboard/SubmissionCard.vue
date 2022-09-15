<template>
  <router-link
    :to="`/${user.user_metadata.username}/collections/${submission.collection_id}/${submission.id}`"
    class="relative flex w-full items-center"
  >
    <div
      v-if="!submission.viewed"
      class="absolute rounded-full bg-red-500 w-2 h-2 ml-3"
    />
    <div
      class="card-padding card-shadow text-sm lg:text-base bg-white rounded-lg w-full overflow-hidden"
    >
      <div class="relative grid grid-cols-6 gap-2">
        <div class="absolute left-0 flex items-center h-full ml-0.5 lg:ml-1">
          <IconDecline
            v-if="submission.is_declined"
            class="transform scale-50 lg:scale-75 text-gray-300"
          />
          <IconThumbDown
            v-else-if="submission.is_liked === -1 && !submission.is_declined"
            class="transform scale-50 lg:scale-75 text-red-300"
          />
          <IconThumbUp
            v-if="submission.is_liked === 1 && !submission.is_declined"
            class="transform scale-50 lg:scale-75 text-green-300"
          />
        </div>

        <div class="relative col-span-2">
          <p
            v-if="submission.booked"
            class="text-[8px] lg:text-[10px] text-blue-500 bottom-0 -mb-[12px] absolute"
          >
            Booked
          </p>
          <p class="truncate">{{ submission.name }}</p>
        </div>
        <p class="col-span-2 opacity-60 truncate">
          {{ submission.email }}
        </p>
        <p class="opacity-60 truncate">
          {{ submission.phone }}
        </p>
        <p class="opacity-60 ml-auto truncate">
          {{ formatDate(submission.created_at) }}
        </p>
      </div>

      <ul
        v-if="submission.tags && submission.tags.length > 0"
        class="flex space-x-3 mt-2"
      >
        <li
          v-for="tag in submission.tags"
          class="px-2 py-0.5 bg-blue-100 text-blue-500 text-xs rounded-full"
          :key="tag.id"
        >
          {{ tag.label }}
        </li>
      </ul>
    </div>
  </router-link>
</template>

<script setup>
// utils
import useAuthUser from "@/utils/useAuth";
import { useRoute } from "vue-router";

// components
import AccountCardGrid from "@/components/dashboard/AccountCardGrid.vue";
import IconThumbDown from "@/components/svg/IconThumbDown.vue";
import IconThumbUp from "@/components/svg/IconThumbUp.vue";
import IconDecline from "@/components/svg/IconDecline.vue";

const { user } = useAuthUser();
const route = useRoute();

const props = defineProps({
  submission: {
    type: Object,
    required: true,
  },
});

// simple function to take the date returned from Supbase (yyyy-mm-dd) and format it to MMM-YY
function formatDate(date) {
  const dateObj = new Date(date);

  const dateFormatted = new Date(
    dateObj.getTime() - dateObj.getTimezoneOffset() * -60000
  );

  return `${dateFormatted.toLocaleString("default", {
    month: "short",
  })} ${dateFormatted.toLocaleString("default", {
    day: "numeric",
  })}`;
}
</script>

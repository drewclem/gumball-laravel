<template>
  <router-link
    :to="`/${user.user_metadata.username}/collections/${submission.collection_id}/${submission.id}`"
    class="relative bg-white card-shadow rounded-lg w-full px-5 pt-4 pb-6 lg:px-8 lg:py-5 text-sm lg:text-base"
  >
    <div class="absolute left-0 top-0 flex items-center h-full ml-0.5 lg:ml-1">
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

    <p
      v-if="submission.booked"
      class="absolute left-0 top-0 ml-5 lg:ml-8 text-[8px] lg:text-[10px] text-blue-500"
    >
      Booked
    </p>

    <div class="relative grid grid-cols-5 gap-4 overflow-hidden">
      <div class="col-span-1">
        <div class="relative">
          <img
            v-if="state.thumbnailUrl"
            class="h-12 w-12 lg:h-24 lg:w-24 object-cover rounded-md"
            :src="state.thumbnailUrl"
          />
          <div
            v-else
            class="flex justify-center items-center h-12 w-12 lg:h-24 lg:w-24 rounded-lg bg-gray-100"
          >
            <p class="text-sm text-gray-500 text-center">No images provided</p>
          </div>
        </div>
      </div>

      <div class="col-span-4">
        <div class="relative collapsed">
          <div class="relative h-[100px] overflow-hidden">
            <div v-html="submission.message" class="mb-4" />

            <div
              class="absolute w-full bottom-0 bg-gradient-to-t from-white to-transparent h-1/3 z-10"
            />
          </div>

          <ul
            v-if="submission && submission.tags.length > 0"
            class="flex space-x-3 mt-2"
          >
            <li
              v-for="tag in submission.tags"
              class="px-3 py-1 bg-blue-100 text-blue-500 text-xs rounded-full"
              :key="tag.id"
            >
              {{ tag.label }}
            </li>
          </ul>
        </div>
      </div>
    </div>
  </router-link>
</template>

<script setup>
// utils
import { onMounted, reactive } from "vue";
import { useRoute } from "vue-router";
import { useUserStore } from "@/stores/user";
import { supabase } from "@/supabase";
import useAuthUser from "@/utils/useAuth";

// components
import BaseButton from "@/components/base/BaseButton.vue";
import IconChevronDown from "@/components/svg/IconChevronDown.vue";
import IconThumbDown from "@/components/svg/IconThumbDown.vue";
import IconThumbUp from "@/components/svg/IconThumbUp.vue";
import IconDecline from "@/components/svg/IconDecline.vue";

const route = useRoute();
const { currentUser } = useUserStore();

const { user } = useAuthUser();

const props = defineProps({
  submission: {
    type: Object,
    required: true,
  },
});

const state = reactive({
  isOpen: false,
  thumbnailUrl: null,
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

async function retrieveImages() {
  const { data } = await supabase
    .from("submission-uploads")
    .select()
    .limit(1)
    .match({ submission_id: props.submission.id });

  if (data.length) {
    const imgData = await supabase.storage
      .from(`submission-uploads/${props.submission.id}`)
      .createSignedUrl(data[0].file_name, 60);

    const img = await imgData.data;

    state.thumbnailUrl = img.signedURL;
  }
}

onMounted(() => {
  retrieveImages();
});
</script>

<style scoped>
.fade-ender-active,
.fade-leave-active {
  opacity: 1;
  transition: 300ms;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>

<script setup>
// utility
import { ref, toRefs, onMounted } from "vue";
import { Link } from "@inertiajs/inertia-vue3";

// components
import AccountCardGrid from "@/Components/dashboard/AccountCardGrid.vue";

const props = defineProps({
    collection: {
        type: Object,
        required: true,
    },
});

const { collection } = toRefs(props);

const isActive = ref(false);

function checkIsActive() {
    const currentDate = new Date();
    const startDate = new Date(collection.value.start_date);
    const endDate = new Date(collection.value.end_date);

    const currentDateFormatted = currentDate.getTime();
    const startDateFormatted =
        startDate.getTime() - startDate.getTimezoneOffset() * -60000;
    const endDateFormatted =
        endDate.getTime() - endDate.getTimezoneOffset() * -60000;

    if (
        (startDateFormatted < currentDateFormatted &&
            endDateFormatted > currentDateFormatted) ||
        (startDateFormatted < currentDateFormatted &&
            collection.value.end_date === null)
    ) {
        isActive.value = true;
    }
}

onMounted(() => {
    checkIsActive();
});

// format collection dates
function formatOpenDates(date) {
    if (date === null) return "";

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

// format collection year
function getYear(date) {
    const dateObj = new Date(date);

    return dateObj.getFullYear();
}
</script>

<template>
    <Link :href="`collections/${collection.id}`">
        <AccountCardGrid
            class="card-shadow bg-white rounded-lg px-5 lg:px-8"
            :class="isActive ? 'py-4 lg:py-6 bg-blue-100' : 'py-3 lg:py-4'"
        >
            <template #col-1>
                {{ formatOpenDates(collection.start_date) }}
                <span v-if="collection.end_date !== null">-</span>
                {{ formatOpenDates(collection.end_date) }}
            </template>
            <template #col-2>{{ getYear(collection.start_date) }}</template>
            <!-- <template #col-3>{{ collection.requests_received }}</template>
            <template #col-4>{{ collection.booked_requests }}</template> -->
        </AccountCardGrid>
    </Link>
</template>

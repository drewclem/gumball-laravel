<script setup>
// components
import AccountCardGrid from "@/Components/dashboard/AccountCardGrid.vue";

const props = defineProps({
    collection: {
        type: Object,
        required: true,
    },
    isActive: {
        type: Boolean,
        default: false,
    },
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
            <template #col-3>{{ collection.submissions_count }}</template>
            <template #col-4>{{ collection.booked_count }}</template>
        </AccountCardGrid>
    </Link>
</template>

<script>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
export default {
    layout: AuthenticatedLayout,
};
</script>

<script setup>
import { Head } from "@inertiajs/inertia-vue3";
import CollectionCard from "@/Components/dashboard/CollectionCard.vue";

import BaseHeading from "@/Components/base/BaseHeading.vue";

const props = defineProps({
    collections: {
        type: Array,
    },
    active: {
        type: Array,
    },
});

const reducedCollections = props?.collections.filter((collection) => {
    return collection?.id !== props.active?.id;
});
</script>

<template>
    <div>
        <Head title="Collections" />
        <div>
            <div class="flex items-center justify-between mb-8">
                <BaseHeading size="h4" tag="h1">Collections</BaseHeading>
            </div>

            <div>
                <div
                    class="grid grid-cols-5 card-padding text-sm lg:text-base opacity-40 mb-4"
                >
                    <p class="col-span-2">Open Dates</p>
                    <p>Year</p>
                    <p>Submissions</p>
                    <p class="ml-auto">Booked</p>
                </div>

                <div
                    v-if="collections.length > 0"
                    class="flex flex-col space-y-6"
                >
                    <CollectionCard :collection="active" is-active />
                    <CollectionCard
                        v-for="collection in reducedCollections"
                        :key="collection.id"
                        :collection="collection"
                    />
                </div>

                <p v-else>Looks like you haven't created a collection yet!</p>
            </div>
        </div>
    </div>
</template>

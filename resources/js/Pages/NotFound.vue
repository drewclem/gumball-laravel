<script>
import DefaultLayout from "@/Layouts/DefaultLayout.vue";
export default {
    layout: DefaultLayout,
};
</script>

<script setup>
import BaseHeading from "@/Components/base/BaseHeading.vue";
import BaseText from "@/Components/base/BaseText.vue";
import NotFoundGraphic from "@/Components/svg/NotFoundGraphic.vue";
</script>

<template>
    <section
        class="flex flex-col lg:flex-row base-wrapper max-w-3xl mx-auto items-center"
    >
        <div>
            <BaseHeading class="text-red-500" size="h2">Uh oh!</BaseHeading>
            <BaseText
                >Looks like we can't find that route. Try another one!</BaseText
            >
        </div>

        <NotFoundGraphic class="w-1/2" />
    </section>
</template>

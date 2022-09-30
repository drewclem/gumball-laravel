<script>
import DefaultLayout from "@/Layouts/DefaultLayout.vue";
export default {
    layout: DefaultLayout,
};
</script>

<script setup>
import { Head } from "@inertiajs/inertia-vue3";
import BaseImage from "@/Components/base/BaseImage.vue";
import BaseHeading from "@/Components/base/BaseHeading.vue";
import BaseText from "@/Components/base/BaseText.vue";
import BaseButton from "@/Components/base/BaseButton.vue";

import ScheduleGraphic from "@/Components/svg/ScheduleGraphic.vue";
import SearchGraphic from "@/Components/svg/SearchGraphic.vue";
import ReviewGraphic from "@/Components/svg/ReviewGraphic.vue";
import IconSorting from "@/Components/svg/IconSorting.vue";
import IconReply from "@/Components/svg/IconReply.vue";
import PricingSignup from "@/Components/global/PricingSignup.vue";

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    laravelVersion: String,
    phpVersion: String,
});
</script>

<template>
    <div>
        <Head title="Heygumball | Do great work" />

        <section class="my-16 xl:my-24 base-wrapper flex">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <div class="rounded-2xl overflow-hidden lg:order-last">
                    <BaseImage
                        src="./hero_image.jpg"
                        alt="A woman works efficiently at a laptop"
                    />
                </div>

                <div class="flex flex-col space-y-6">
                    <BaseHeading size="h1">
                        Do great work.<br />
                        Stay busy.
                    </BaseHeading>

                    <BaseText class="lg:w-3/4">
                        Manage your appointment &amp; booking requests better
                        with Heygumball&apos;s booking request management tools.
                    </BaseText>

                    <div>
                        <BaseButton href="create-account" theme="primary">
                            Get Started
                        </BaseButton>
                    </div>
                </div>
            </div>
        </section>

        <section class="narrow-wrapper my-24 lg:my-52">
            <div>
                <BaseHeading
                    size="h2"
                    class="lg:text-center mb-8 !leading-tight"
                >
                    Managing booking requests <br />just got
                    <span class="text-green-500">awesome</span>
                </BaseHeading>

                <BaseText
                    class="text-lg lg:text-xl max-w-5xl mx-auto lg:text-center"
                >
                    Everyone wants to book time with you, and for good reason!
                    But your time is best spent creating the value everyone
                    wants. Heygumball puts the power back in your hands by
                    letting you decide when you&apos;re open to take requests
                    and by giving you tools to manage, sort, and respond in a
                    simple and efficient way!
                </BaseText>
            </div>

            <div class="max-w-3xl mx-auto">
                <div
                    class="grid lg:grid-cols-2 gap-12 items-center my-20 lg:my-48"
                >
                    <div class="flex flex-col space-y-3">
                        <ScheduleGraphic class="w-1/2 lg:hidden mx-auto mb-4" />

                        <BaseHeading class="text-blue-500" size="h3">
                            Schedule when your books are open
                        </BaseHeading>

                        <BaseText>
                            Do you know when you'll be booking next? Get ahead
                            of the curve by scheduling your Heygumball form to
                            be live and open to the public.<br /><br />You'll
                            get notifications when requests start coming in
                            <span class="italic">and</span> your request form
                            will close itself when it's time has lapsed.
                        </BaseText>
                    </div>

                    <div class="hidden lg:block">
                        <ScheduleGraphic class="w-full" />
                    </div>
                </div>

                <div
                    class="grid lg:grid-cols-2 gap-12 items-center my-20 lg:my-48"
                >
                    <div class="hidden lg:block">
                        <SearchGraphic class="w-full" />
                    </div>

                    <div class="flex flex-col space-y-3">
                        <SearchGraphic class="w-1/2 lg:hidden mx-auto mb-4" />

                        <BaseHeading class="text-blue-500" size="h3">
                            Search, filter, &amp; tag your backlog
                        </BaseHeading>

                        <BaseText>
                            Navigating your requests has never been easier!
                            Search by keyword, name, date- you name it, we find
                            it.<br /><br />Requests are grouped by collections
                            by default, making it easy for you to see the most
                            recent and relevant items.
                        </BaseText>
                    </div>
                </div>

                <div
                    class="grid lg:grid-cols-2 gap-12 items-center my-20 lg:my-48"
                >
                    <div class="flex flex-col space-y-3">
                        <ReviewGraphic class="w-1/2 lg:hidden mx-auto mb-4" />

                        <BaseHeading class="text-blue-500" size="h3">
                            Review &amp; respond to requests
                        </BaseHeading>

                        <BaseText>
                            Review and respond to prospective requests from the
                            easy-to-use dashboard. Mark requests as denied,
                            approved, contacted, or booked!<br /><br />
                            Once you've narrowed down your options, one-click
                            reply to those that made the cut and start filling
                            up your calendar!
                        </BaseText>
                    </div>

                    <div class="hidden lg:block">
                        <ReviewGraphic class="w-full" />
                    </div>
                </div>
            </div>
        </section>

        <PricingSignup :showPricing="true" />
    </div>
</template>

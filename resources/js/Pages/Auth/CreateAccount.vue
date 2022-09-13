<template>
  <section>
    <div
      class="max-w-3xl mx-auto px-6 xl:px-0 grid grid-cols-1 lg:grid-cols-2 gap-24 items-center"
    >
      <transition v-if="state.step === 0" name="fade" appear>
        <div class="flex flex-col space-y-6">
          <BaseHeading size="h4" tag="h1"> Create an account </BaseHeading>
          <BaseText size="small">
            We're invite only during testing. If the man in the hat gave you a
            password, please provide it now.
          </BaseText>

          <div
            class="relative"
            :class="{ error: invite$.invite.$errors.length }"
          >
            <BaseInput v-model="state.invite">Invite Password</BaseInput>
            <div class="input-errors">
              <p
                v-for="error of invite$.invite.$errors"
                :key="error.$uid"
                class="error-msg absolute text-xs text-red-500 mt-1"
              >
                {{ error.$message }}
              </p>
            </div>
          </div>

          <div class="w-full lg:w-auto ml-auto">
            <BaseButton class="w-full" @click="verifyPassword">
              Verify
            </BaseButton>
          </div>
        </div>
      </transition>

      <transition v-else name="fade" appear>
        <form @submit.prevent="handleFormSubmission">
          <BaseHeading class="mb-5" size="h4" tag="h1">
            Create an account
          </BaseHeading>

          <div v-if="formState === 'submitted'">
            <BaseHeading tag="h2" size="h4" class="text-green-500 mb-5">
              Thanks for signing up!
            </BaseHeading>

            <BaseText>Check your email to confirm your registration</BaseText>
          </div>

          <div v-else class="flex flex-col space-y-8">
            <div
              class="relative"
              :class="{ error: v$.username.$errors.length }"
            >
              <BaseInput v-model="state.username"> User Name </BaseInput>
              <div
                class="input-errors"
                v-for="error of v$.username.$errors"
                :key="error.$uid"
              >
                <p class="error-msg absolute text-xs text-red-500 mt-1">
                  {{ error.$message }}
                </p>
              </div>
            </div>

            <div class="relative" :class="{ error: v$.email.$errors.length }">
              <BaseInput v-model="state.email"> Email </BaseInput>
              <div
                class="input-errors"
                v-for="error of v$.email.$errors"
                :key="error.$uid"
              >
                <p class="error-msg absolute text-xs text-red-500 mt-1">
                  {{ error.$message }}
                </p>
              </div>
            </div>

            <div
              class="relative"
              :class="{ error: v$.password.$errors.length }"
            >
              <BaseInput inputType="password" v-model="state.password">
                Password
              </BaseInput>
              <div
                class="input-errors"
                v-for="error of v$.password.$errors"
                :key="error.$uid"
              >
                <p class="error-msg absolute text-xs text-red-500 mt-1">
                  {{ error.$message }}
                </p>
              </div>
            </div>

            <div class="relative" :class="{ error: v$.toc.$errors.length }">
              <input
                id="toc"
                name="toc"
                v-model="state.toc"
                type="checkbox"
                class="mr-1"
              />
              <label for="toc">
                I agree to the
                <router-link
                  to="/terms-and-conditions"
                  class="text-blue-500 underline"
                >
                  terms and conditions
                </router-link>
                of Heygumball
              </label>
              <div
                class="input-errors"
                v-for="error of v$.toc.$errors"
                :key="error.$uid"
              >
                <p class="error-msg absolute text-xs text-red-500 mt-1">
                  {{ error.$message }}
                </p>
              </div>
            </div>

            <div class="w-full lg:w-auto ml-auto">
              <BaseButton
                type="submit"
                theme="tertiary"
                class="w-full"
                :disabled="formState === 'submitting'"
              >
                {{
                  formState === "submitting" ? "Submitting" : "Create Account"
                }}
              </BaseButton>
            </div>
          </div>
        </form>
      </transition>

      <div class="relative">
        <CreateAccount class="w-full" />
      </div>
    </div>

    <PricingSignup />
  </section>
</template>

<script>
// utils
import { ref, reactive, computed } from "vue";
import useAuthUser from "@/utils/useAuth";

// vuelidate form validation
import useVuelidate from "@vuelidate/core";
import { required, email, minLength, helpers } from "@vuelidate/validators";
import { useValidate } from "@/utils/validate";

//components
import BaseHeading from "@/components/base/BaseHeading.vue";
import BaseInput from "@/components/base/BaseInput.vue";
import BaseButton from "@/components/base/BaseButton.vue";
import BaseText from "@/components/base/BaseText.vue";
import CreateAccount from "@/components/svg/CreateAccount.vue";
import PricingSignup from "@/components/global/PricingSignup.vue";

export default {
  components: {
    BaseHeading,
    BaseText,
    BaseInput,
    BaseButton,
    CreateAccount,
    PricingSignup,
  },
  setup() {
    const usernames = ref();
    const formState = ref();
    const state = reactive({
      username: "",
      email: "",
      password: "",
      toc: "",
      invite: "",
      step: 0,
    });

    const { handleSignup } = useAuthUser();
    const { inviteMustBeValid, mustBeUniqueUsername, notEmpty } = useValidate();

    const rules = {
      username: {
        required: helpers.withMessage(notEmpty, required),
        mustBeUniqueUsername: helpers.withMessage(
          "This username is already taken",
          mustBeUniqueUsername
        ),
      },
      email: {
        required: helpers.withMessage(notEmpty, required),
        email: helpers.withMessage(
          "This field must contain a valid email address",
          email
        ),
      },
      password: {
        required: helpers.withMessage(notEmpty, required),
        minLength: minLength(8),
      },
      toc: {
        required: helpers.withMessage(
          "You must aggree to the terms and conditions before proceeding",
          required
        ),
      },
    };

    const inviteRule = {
      invite: {
        inviteMustBeValid: helpers.withMessage(
          "The man in the hat doesn't know this word",
          inviteMustBeValid
        ),
      },
    };

    const invite$ = useVuelidate(inviteRule, state);

    const v$ = useVuelidate(rules, state);

    return {
      usernames,
      formState,
      state,
      handleSignup,
      v$,
      invite$,
    };
  },
  methods: {
    async handleFormSubmission() {
      const isFormValid = await this.v$.$validate();

      if (!isFormValid) return;

      this.formState = "submitting";

      this.handleSignup(this.state);

      setTimeout(() => {
        this.formState = "submitted";
      }, 500);
    },

    async verifyPassword() {
      const isValidInvite = await this.invite$.$validate();

      if (isValidInvite) this.state.step++;
    },
  },
};
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 300ms ease-in-out;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>

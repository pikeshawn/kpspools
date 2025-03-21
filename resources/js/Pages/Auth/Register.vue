<template>
  <jet-authentication-card class="py-24">
    <template #logo>
      <jet-authentication-card-logo/>
    </template>

    <jet-validation-errors class="mb-4"/>

    <form @submit.prevent="submit">
      <div>
        <jet-label for="first_name" value="First Name"/>
        <jet-input id="first_name" type="text" class="mt-1 block w-full" v-model="form.first_name" required autofocus
                   autocomplete="first_name"/>
      </div>

      <div>
        <jet-label for="last_name" value="Last Name"/>
        <jet-input id="last_name" type="text" class="mt-1 block w-full" v-model="form.last_name" required autofocus
                   autocomplete="last_name"/>
      </div>

      <div>
        <jet-label for="phoneNumber" value="Phone Number (15555551234)"/>
        <jet-input id="phoneNumber" type="text" class="py-2 mt-1 block w-full" v-model="form.phoneNumber" required autofocus
                   autocomplete="phoneNumber" aria-placeholder="15555551234"/>
      </div>

      <div>
        <jet-label for="addressLine1" value="Street Address"/>
        <jet-input id="addressLine1" type="text" class="py-2 mt-1 block w-full" v-model="form.addressLine1" required autofocus
                   autocomplete="addressLine1"/>
      </div>

      <div>
        <jet-label for="city" value="City"/>
        <jet-input id="city" type="text" class="py-2 mt-1 block w-full" v-model="form.city" required autofocus
                   autocomplete="city"/>
      </div>

      <div>
        <jet-label for="zip" value="Zip Code"/>
        <jet-input id="zip" type="text" class="py-2 mt-1 block w-full" v-model="form.zip" required autofocus
                   autocomplete="zip"/>
      </div>

      <div class="mt-4">
        <jet-label for="email" value="Email"/>
        <jet-input id="email" type="email" class="mt-1 block w-full" v-model="form.email" required/>
      </div>

      <div class="mt-4">
        <jet-label for="password" value="Password"/>
        <jet-input id="password" type="password" class="mt-1 block w-full" v-model="form.password" required
                   autocomplete="new-password"/>
      </div>

      <div class="mt-4">
        <jet-label for="password_confirmation" value="Confirm Password"/>
        <jet-input id="password_confirmation" type="password" class="mt-1 block w-full"
                   v-model="form.password_confirmation" required autocomplete="new-password"/>
      </div>

      <div class="flex items-center justify-end mt-4">
        <inertia-link :href="route('login')" class="underline text-sm text-gray-600 hover:text-gray-900">
          Already registered?
        </inertia-link>

        <jet-button class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
          Register
        </jet-button>
      </div>
    </form>
  </jet-authentication-card>
</template>

<script>
import JetAuthenticationCard from '@/Jetstream/AuthenticationCard.vue'
import JetAuthenticationCardLogo from '@/Jetstream/AuthenticationCardLogo.vue'
import JetButton from '@/Jetstream/Button.vue'
import JetInput from '@/Jetstream/Input.vue'
import JetCheckbox from "@/Jetstream/Checkbox.vue";
import JetLabel from '@/Jetstream/Label.vue'
import JetValidationErrors from '@/Jetstream/ValidationErrors.vue'

export default {
  components: {
    JetAuthenticationCard,
    JetAuthenticationCardLogo,
    JetButton,
    JetInput,
    JetCheckbox,
    JetLabel,
    JetValidationErrors
  },
  props: {

  },
  data() {
    return {
      form: this.$inertia.form({
        first_name: '',
        last_name: '',
        phoneNumber: '',
        addressLine1: '',
        city: '',
        zip: '',
        email: '',
        password: '',
        password_confirmation: '',
        terms: false,
      })
    }
  },

  methods: {
    submit() {
      this.form.post(this.route('register'), {
        onFinish: () => this.form.reset('password', 'password_confirmation'),
      })
    }
  }
}
</script>

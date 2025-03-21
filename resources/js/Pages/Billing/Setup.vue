<template>
  <layout
      title="Billing Setup"
      :user="user"
  >

      <main>
          <!-- Pricing section -->
          <div class="mx-auto mt-16 max-w-7xl px-6 sm:mt-32 lg:px-8">
              <div class="mx-auto max-w-4xl text-center">
                  <h1 class="text-base font-semibold leading-7 text-indigo-600">Pricing</h1>
                  <p class="mt-2 text-4xl font-bold tracking-tight text-gray-900 sm:text-5xl">Pricing plans for teams of&nbsp;all&nbsp;sizes</p>
              </div>
              <p class="mx-auto mt-6 max-w-2xl text-center text-lg leading-8 text-gray-600">Choose an affordable plan that’s packed with the best features for engaging your audience, creating customer loyalty, and driving sales.</p>
              <div class="mt-16 flex justify-center">
                  <RadioGroup v-model="frequency" class="grid grid-cols-2 gap-x-1 rounded-full p-1 text-center text-xs font-semibold leading-5 ring-1 ring-inset ring-gray-200">
                      <RadioGroupLabel class="sr-only">Payment frequency</RadioGroupLabel>
                      <RadioGroupOption as="template" v-for="option in pricing.frequencies" :key="option.value" :value="option" v-slot="{ checked }">
                          <div :class="[checked ? 'bg-indigo-600 text-white' : 'text-gray-500', 'cursor-pointer rounded-full px-2.5 py-1']">
                              <span>{{ option.label }}</span>
                          </div>
                      </RadioGroupOption>
                  </RadioGroup>
              </div>
              <div class="isolate mx-auto mt-10 grid max-w-md grid-cols-1 gap-8 md:max-w-2xl md:grid-cols-2 lg:max-w-4xl xl:mx-0 xl:max-w-none xl:grid-cols-4">
                  <div v-for="tier in pricing.tiers" :key="tier.id" :class="[tier.mostPopular ? 'ring-2 ring-indigo-600' : 'ring-1 ring-gray-200', 'rounded-3xl p-8']">
                      <h2 :id="tier.id" :class="[tier.mostPopular ? 'text-indigo-600' : 'text-gray-900', 'text-lg font-semibold leading-8']">{{ tier.name }}</h2>
                      <p class="mt-4 text-sm leading-6 text-gray-600">{{ tier.description[frequency.value] }}</p>
                      <p class="mt-6 flex items-baseline gap-x-1">
                          <span class="text-4xl font-bold tracking-tight text-gray-900">${{ tier.price[frequency.value] }}</span>
                          <span class="text-sm font-semibold leading-6 text-gray-600">{{ frequency.priceSuffix }}</span>
                      </p>
                      <a @click="buyPlan(tier.price[frequency.value])" :aria-describedby="tier.id" :class="[tier.mostPopular ?
                      'bg-indigo-600 text-white shadow-sm hover:bg-indigo-500' :
                      'text-indigo-600 ring-1 ring-inset ring-indigo-200 hover:ring-indigo-300',
                      'mt-6 block rounded-md py-2 px-3 text-center text-sm ' +
                       'font-semibold leading-6 focus-visible:outline focus-visible:outline-2 ' +
                        'focus-visible:outline-offset-2 focus-visible:outline-indigo-600']">Buy plan</a>
                      <ul role="list" class="mt-8 space-y-3 text-sm leading-6 text-gray-600">
                          <li v-for="feature in tier.features[frequency.value]" :key="feature" class="flex gap-x-3">
                              <CheckIcon class="h-6 w-5 flex-none text-indigo-600" aria-hidden="true" />
                              {{ feature }}
                          </li>
                      </ul>
                  </div>
              </div>
          </div>
      </main>

  </layout>
</template>

<script setup>
import { defineComponent, h, ref } from 'vue'
import {
    RadioGroup,
    RadioGroupLabel,
    RadioGroupOption,
} from '@headlessui/vue'
import { CheckIcon } from '@heroicons/vue/20/solid'

const pricing = {
    frequencies: [
        { value: 'monthly', label: 'Monthly Rate', priceSuffix: '/month' },
        { value: 'weekly', label: 'Weekly Rate', priceSuffix: '/weekly' },
    ],
    tiers: [
        {
            name: 'Service Rate',
            id: 'service',
            href: '#',
            price: { monthly: '30', weekly: '120' },
            description: {monthly: 'Monthly', weekly: 'Weekly'},
            features: {
                monthly: ['Consistent Monthly Amounts', 'No unapproved charges'],
                weekly: ['Cancel Early Without Penalties', 'Low Winter Bills']
            },
            mostPopular: true,
        }
    ],
}

const mobileMenuOpen = ref(false)
const frequency = ref(pricing.frequencies[0])
</script>

<script>
import JetInput from '@/Jetstream/Input.vue'
import SimpleTable from "../Shared/SimpleTable.vue";
import Layout from "../Shared/Layout.vue";
import {Link} from '@inertiajs/inertia-vue3';
import {Inertia} from '@inertiajs/inertia';


export default {
  name: 'BillingSetup',
  components: {

  },
  data() {
    return {}
  },
  mounted() {},
  methods: {
      buyPlan(price){
          Inertia.get('/subscription-checkout', {'price': price})
          // Inertia.get('/subscription/checkout', {'price': price})
      }
  },
  props: {
    user: Object
  }
}
</script>

<style scoped>


.completed {
  background-color: #9bcd9b;
}

.notCompleted {
  background-color: #de7777;
}

</style>

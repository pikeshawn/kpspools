<template>
  <div class="relative isolate overflow-hidden bg-gray-900 py-24 sm:py-32">
    <!--    <img src="https://images.unsplash.com/photo-1521737604893-d14cc237f11d?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&crop=focalpoint&fp-y=.8&w=2830&h=1500&q=80&blend=111827&sat=-100&exp=15&blend-mode=multiply" alt="" class="absolute inset-0 -z-10 h-full w-full object-cover object-right md:object-center" />-->
    <div class="hidden sm:absolute sm:-top-10 sm:right-1/2 sm:-z-10 sm:mr-10 sm:block sm:transform-gpu sm:blur-3xl"
         aria-hidden="true">
      <div class="aspect-[1097/845] w-[68.5625rem] bg-gradient-to-tr from-[#ff4694] to-[#776fff] opacity-20"
           style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)"/>
    </div>
    <div
        class="absolute -top-52 left-1/2 -z-10 -translate-x-1/2 transform-gpu blur-3xl sm:top-[-28rem] sm:ml-16 sm:translate-x-0 sm:transform-gpu"
        aria-hidden="true">
      <div class="aspect-[1097/845] w-[68.5625rem] bg-gradient-to-tr from-[#ff4694] to-[#776fff] opacity-20"
           style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)"/>
    </div>
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
      <div class="mx-auto max-w-2xl lg:mx-0">
        <h2 class="text-4xl font-bold tracking-tight text-white sm:text-6xl">KPS Pools</h2>
        <p class="mt-6 text-lg leading-8 text-gray-300">NEED INFO FOR AN EXCITING INTRO</p>
      </div>
      <div class="mx-auto mt-10 max-w-2xl lg:mx-0 lg:max-w-none">
        <div
            class="grid grid-cols-1 gap-x-8 gap-y-6 text-base font-semibold leading-7 text-white sm:grid-cols-2 md:flex lg:gap-x-10">
          <a v-for="link in links" :key="link.name" :href="link.href">{{ link.name }} <span
              aria-hidden="true">&rarr;</span></a>
        </div>
        <dl class="mt-16 grid grid-cols-1 gap-8 sm:mt-20 sm:grid-cols-2 lg:grid-cols-4">
          <div v-for="stat in stats" :key="stat.name" class="flex flex-col-reverse">
            <dt class="text-base leading-7 text-gray-300">{{ stat.name }}</dt>
            <dd class="text-2xl font-bold leading-9 tracking-tight text-white px-4">{{ stat.value }}</dd>
          </div>
        </dl>
      </div>
    </div>
  </div>

  <div class="bg-white py-24 sm:py-32">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
      <div class="mx-auto max-w-2xl lg:text-center">
        <p class="mt-2 text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">Schedule Your Consultation</p>
        <p class="mt-6 text-lg leading-8 text-gray-600">Select the date below. Then select one of the available times
          and we will come out to take a look at your pool.</p>
        <div style="margin-top: 50px">
          <div>
            <h2 class="font-bold py-2.5 text-2xl">Current Appointment Time :: <span>{{ this.appointments }}</span></h2>
          </div>
        </div>
        <div class="flex justify-between">
          <div class="flex flex-col">
            <input
                id="appointmentDate"
                @change="getAvailableTimes()"
                type="date" v-model="appointmentDate">
          </div>
          <div>
            <nav v-if="jsonData" class="mt-5 flex-1 px-2 space-y-1">
              <button
                  v-if="jsonData['04']"
                  style="margin-top: 10px; width: 150px"
                  @click="displayRequestedTime('04:00:00')"
                  class="w-1/4 py-2 bg-gray-800 text-gray-300 hover:bg-gray-700 hover:text-white group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                <component is="HomeIcon" class="text-white-300 mr-3 flex-shrink-0 h-6 w-6" aria-hidden="true"/>
                4 pm
              </button>
              <button
                  v-if="jsonData['05']"
                  style="margin-top: 10px; width: 150px"
                  @click="displayRequestedTime('05:00:00')"
                  class="w-1/4 py-2 bg-gray-800 text-gray-300 hover:bg-gray-700 hover:text-white group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                <component is="HomeIcon" class="text-white-300 mr-3 flex-shrink-0 h-6 w-6" aria-hidden="true"/>
                5 pm
              </button>
              <button
                  v-if="jsonData['06']"
                  style="margin-top: 10px; width: 150px"
                  @click="displayRequestedTime('06:00:00')"
                  class="w-1/4 py-2 bg-gray-800 text-gray-300 hover:bg-gray-700 hover:text-white group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                <component is="HomeIcon" class="text-white-300 mr-3 flex-shrink-0 h-6 w-6" aria-hidden="true"/>
                6 pm
              </button>
              <button
                  v-if="jsonData['07']"
                  style="margin-top: 10px; width: 150px"
                  @click="displayRequestedTime('07:00:00')"
                  class="w-1/4 py-2 bg-gray-800 text-gray-300 hover:bg-gray-700 hover:text-white group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                <component is="HomeIcon" class="text-white-300 mr-3 flex-shrink-0 h-6 w-6" aria-hidden="true"/>
                7 pm
              </button>
            </nav>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="bg-white py-24 sm:py-32">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
      <div class="mx-auto max-w-4xl text-center">
        <h2 class="text-base font-semibold leading-7 text-indigo-600">Pricing</h2>
        <p class="mt-2 text-4xl font-bold tracking-tight text-gray-900 sm:text-5xl">Pricing plans for teams of&nbsp;all&nbsp;sizes</p>
      </div>
      <p class="mx-auto mt-6 max-w-2xl text-center text-lg leading-8 text-gray-600">Choose an affordable plan that’s
        packed with the best features for engaging your audience, creating customer loyalty, and driving sales.</p>
      <div class="mt-16 flex justify-center">
        <RadioGroup v-model="frequency"
                    class="grid grid-cols-2 gap-x-1 rounded-full p-1 text-center text-xs font-semibold leading-5 ring-1 ring-inset ring-gray-200">
          <RadioGroupLabel class="sr-only">Payment frequency</RadioGroupLabel>
          <RadioGroupOption as="template" v-for="option in frequencies" :key="option.value" :value="option"
                            v-slot="{ checked }">
            <div
                :class="[checked ? 'bg-indigo-600 text-white' : 'text-gray-500', 'cursor-pointer rounded-full px-2.5 py-1']">
              <span>{{ option.label }}</span>
            </div>
          </RadioGroupOption>
        </RadioGroup>
      </div>
      <div class="isolate mx-auto mt-10 grid max-w-md grid-cols-1 gap-8 lg:mx-0 lg:max-w-none lg:grid-cols-3">
        <div v-for="tier in tiers" :key="tier.id"
             :class="[tier.mostPopular ? 'ring-2 ring-indigo-600' : 'ring-1 ring-gray-200', 'rounded-3xl p-8 xl:p-10']">
          <div class="flex items-center justify-between gap-x-4">
            <h3 :id="tier.id"
                :class="[tier.mostPopular ? 'text-indigo-600' : 'text-gray-900', 'text-lg font-semibold leading-8']">
              {{ tier.name }}</h3>
            <p v-if="tier.mostPopular"
               class="rounded-full bg-indigo-600/10 px-2.5 py-1 text-xs font-semibold leading-5 text-indigo-600">Most
              popular</p>
          </div>
          <p class="mt-4 text-sm leading-6 text-gray-600">{{ tier.description }}</p>
          <p class="mt-6 flex items-baseline gap-x-1">
            <span class="text-4xl font-bold tracking-tight text-gray-900">{{ tier.price[frequency.value] }}</span>
            <span class="text-sm font-semibold leading-6 text-gray-600">{{ frequency.priceSuffix }}</span>
          </p>
          <a :href="tier.href" :aria-describedby="tier.id"
             :class="[tier.mostPopular ? 'bg-indigo-600 text-white shadow-sm hover:bg-indigo-500' : 'text-indigo-600 ring-1 ring-inset ring-indigo-200 hover:ring-indigo-300', 'mt-6 block rounded-md py-2 px-3 text-center text-sm font-semibold leading-6 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600']">Buy
            plan</a>
          <ul role="list" class="mt-8 space-y-3 text-sm leading-6 text-gray-600 xl:mt-10">
            <li v-for="feature in tier.features" :key="feature" class="flex gap-x-3">
              <CheckIcon class="h-6 w-5 flex-none text-indigo-600" aria-hidden="true"/>
              {{ feature }}
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>

  <div class="bg-white py-24 sm:py-32">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
      <div
          class="mx-auto grid max-w-2xl grid-cols-1 items-start gap-x-8 gap-y-16 sm:gap-y-24 lg:mx-0 lg:max-w-none lg:grid-cols-2">
        <div class="lg:pr-4">
          <div
              class="relative overflow-hidden rounded-3xl bg-gray-900 px-6 pb-9 pt-64 shadow-2xl sm:px-12 lg:max-w-lg lg:px-8 lg:pb-8 xl:px-10 xl:pb-10">
            <img class="absolute inset-0 h-full w-full object-cover brightness-125 saturate-0"
                 src="https://images.unsplash.com/photo-1630569267625-157f8f9d1a7e?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=2669&q=80"
                 alt=""/>
            <div class="absolute inset-0 bg-gray-900 mix-blend-multiply"/>
            <div class="absolute left-1/2 top-1/2 -ml-16 -translate-x-1/2 -translate-y-1/2 transform-gpu blur-3xl"
                 aria-hidden="true">
              <div class="aspect-[1097/845] w-[68.5625rem] bg-gradient-to-tr from-[#ff4694] to-[#776fff] opacity-40"
                   style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)"/>
            </div>
            <figure class="relative isolate">
              <svg viewBox="0 0 162 128" fill="none" aria-hidden="true"
                   class="absolute -left-2 -top-4 -z-10 h-32 stroke-white/20">
                <path id="0ef284b8-28c2-426e-9442-8655d393522e"
                      d="M65.5697 118.507L65.8918 118.89C68.9503 116.314 71.367 113.253 73.1386 109.71C74.9162 106.155 75.8027 102.28 75.8027 98.0919C75.8027 94.237 75.16 90.6155 73.8708 87.2314C72.5851 83.8565 70.8137 80.9533 68.553 78.5292C66.4529 76.1079 63.9476 74.2482 61.0407 72.9536C58.2795 71.4949 55.276 70.767 52.0386 70.767C48.9935 70.767 46.4686 71.1668 44.4872 71.9924L44.4799 71.9955L44.4726 71.9988C42.7101 72.7999 41.1035 73.6831 39.6544 74.6492C38.2407 75.5916 36.8279 76.455 35.4159 77.2394L35.4047 77.2457L35.3938 77.2525C34.2318 77.9787 32.6713 78.3634 30.6736 78.3634C29.0405 78.3634 27.5131 77.2868 26.1274 74.8257C24.7483 72.2185 24.0519 69.2166 24.0519 65.8071C24.0519 60.0311 25.3782 54.4081 28.0373 48.9335C30.703 43.4454 34.3114 38.345 38.8667 33.6325C43.5812 28.761 49.0045 24.5159 55.1389 20.8979C60.1667 18.0071 65.4966 15.6179 71.1291 13.7305C73.8626 12.8145 75.8027 10.2968 75.8027 7.38572C75.8027 3.6497 72.6341 0.62247 68.8814 1.1527C61.1635 2.2432 53.7398 4.41426 46.6119 7.66522C37.5369 11.6459 29.5729 17.0612 22.7236 23.9105C16.0322 30.6019 10.618 38.4859 6.47981 47.558L6.47976 47.558L6.47682 47.5647C2.4901 56.6544 0.5 66.6148 0.5 77.4391C0.5 84.2996 1.61702 90.7679 3.85425 96.8404L3.8558 96.8445C6.08991 102.749 9.12394 108.02 12.959 112.654L12.959 112.654L12.9646 112.661C16.8027 117.138 21.2829 120.739 26.4034 123.459L26.4033 123.459L26.4144 123.465C31.5505 126.033 37.0873 127.316 43.0178 127.316C47.5035 127.316 51.6783 126.595 55.5376 125.148L55.5376 125.148L55.5477 125.144C59.5516 123.542 63.0052 121.456 65.9019 118.881L65.5697 118.507Z"/>
                <use href="#0ef284b8-28c2-426e-9442-8655d393522e" x="86"/>
              </svg>
              <img src="https://tailwindui.com/img/logos/workcation-logo-white.svg" alt="" class="h-12 w-auto"/>
              <blockquote class="mt-6 text-xl font-semibold leading-8 text-white">
                <p>“Amet amet eget scelerisque tellus sit neque faucibus non eleifend. Integer eu praesent at a. Ornare
                  arcu gravida natoque erat et cursus tortor.”</p>
              </blockquote>
              <figcaption class="mt-6 text-sm leading-6 text-gray-300"><strong class="font-semibold text-white">Judith
                Rogers,</strong> CEO at Workcation
              </figcaption>
            </figure>
          </div>
        </div>
        <div>
          <div class="text-base leading-7 text-gray-700 lg:max-w-lg">
            <p class="text-base font-semibold leading-7 text-indigo-600">Company values</p>
            <h1 class="mt-2 text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">On a mission to empower remote
              teams</h1>
            <div class="max-w-xl">
              <p class="mt-6">Faucibus commodo massa rhoncus, volutpat. Dignissim sed eget risus enim. Mattis mauris
                semper sed amet vitae sed turpis id. Id dolor praesent donec est. Odio penatibus risus viverra tellus
                varius sit neque erat velit. Faucibus commodo massa rhoncus, volutpat. Dignissim sed eget risus enim.
                Mattis mauris semper sed amet vitae sed turpis id.</p>
              <p class="mt-8">Et vitae blandit facilisi magna lacus commodo. Vitae sapien duis odio id et. Id blandit
                molestie auctor fermentum dignissim. Lacus diam tincidunt ac cursus in vel. Mauris varius vulputate et
                ultrices hac adipiscing egestas. Iaculis convallis ac tempor et ut. Ac lorem vel integer orci.</p>
              <p class="mt-8">Et vitae blandit facilisi magna lacus commodo. Vitae sapien duis odio id et. Id blandit
                molestie auctor fermentum dignissim. Lacus diam tincidunt ac cursus in vel. Mauris varius vulputate et
                ultrices hac adipiscing egestas. Iaculis convallis ac tempor et ut. Ac lorem vel integer orci.</p>
            </div>
          </div>
          <dl class="mt-10 grid grid-cols-2 gap-8 border-t border-gray-900/10 pt-10 sm:grid-cols-4">
            <div v-for="(stat, statIdx) in contentStats" :key="statIdx">
              <dt class="text-sm font-semibold leading-6 text-gray-600">{{ stat.label }}</dt>
              <dd class="mt-2 text-3xl font-bold leading-10 tracking-tight text-gray-900">{{ stat.value }}</dd>
            </div>
          </dl>
          <div class="mt-10 flex">
            <a href="#" class="text-base font-semibold leading-7 text-indigo-600">Learn more about our company <span
                aria-hidden="true">&rarr;</span></a>
          </div>
        </div>
      </div>
    </div>
  </div>

</template>

<script setup>
import {ref} from 'vue'
import {RadioGroup, RadioGroupLabel, RadioGroupOption} from '@headlessui/vue'
import {CheckIcon} from '@heroicons/vue/20/solid'

const contentStats = [
  {label: 'Founded', value: '2021'},
  {label: 'Employees', value: '37'},
  {label: 'Countries', value: '12'},
  {label: 'Raised', value: '$25M'},
]

const frequencies = [
  {value: 'monthly', label: 'Monthly', priceSuffix: '/month'},
  {value: 'annually', label: 'Annually', priceSuffix: '/year'},
]
const tiers = [
  {
    name: 'Freelancer',
    id: 'tier-freelancer',
    href: '#',
    price: {monthly: '$15', annually: '$144'},
    description: 'The essentials to provide your best work for clients.',
    features: ['5 products', 'Up to 1,000 subscribers', 'Basic analytics', '48-hour support response time'],
    mostPopular: false,
  },
  {
    name: 'Startup',
    id: 'tier-startup',
    href: '#',
    price: {monthly: '$30', annually: '$288'},
    description: 'A plan that scales with your rapidly growing business.',
    features: [
      '25 products',
      'Up to 10,000 subscribers',
      'Advanced analytics',
      '24-hour support response time',
      'Marketing automations',
    ],
    mostPopular: true,
  },
  {
    name: 'Enterprise',
    id: 'tier-enterprise',
    href: '#',
    price: {monthly: '$60', annually: '$576'},
    description: 'Dedicated support and infrastructure for your company.',
    features: [
      'Unlimited products',
      'Unlimited subscribers',
      'Advanced analytics',
      '1-hour, dedicated support response time',
      'Marketing automations',
      'Custom reporting tools',
    ],
    mostPopular: false,
  },
]

const frequency = ref(frequencies[0])

const links = [
  {name: 'Pricing Plans', href: '#'},
  {name: 'Service Description', href: '#'},
  {name: 'Schedule Consultation Time', href: '#'},
  {name: 'Meet our Team', href: '#'},
]
const stats = [
  {name: 'Service Area', value: 'East Valley'},
  {name: 'Service Size', value: '200 Pools'},
  {name: 'Years In Business', value: '26 Years'},
  {name: 'Team Size', value: '5'},
]
</script>

<script>
import {Inertia} from "@inertiajs/inertia";

export default {
  name: 'Landing',
  data() {
    return {
      appointmentDate: this.currentAppointmentDate,
      requestedTime: null
    }
  },
  props: {
    currentAppointmentDate: String,
    appointments: Object,
    jsonData: Object
  },
  methods: {
    displayRequestedTime(time){
      this.requestedTime = this.appointmentDate + " " + time;
      const promise = Inertia.post('/prospective/requested', {
        date: this.appointmentDate,
        time: time
      })
    },
    getAvailableTimes() {
      const promise = Inertia.post('/prospectiveTimes', {date: this.appointmentDate})

      promise.then(response => {
        // Handle the response data here
        const $element = document.getElementById('response')
        $element.innerHTML = this.response;

      }).catch(error => {
        // Handle any errors that occurred during the request
        console.error(error);
      });
    }
  }
}
</script>

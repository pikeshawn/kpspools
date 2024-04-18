<template>
    <layout
        :user="user"
    >
        <div class="isolate bg-white px-6 py-24 sm:py-32 lg:px-8">
            <div class="absolute inset-x-0 top-[-10rem] -z-10 transform-gpu overflow-hidden blur-3xl sm:top-[-20rem]"
                 aria-hidden="true">
                <div
                    class="relative left-1/2 -z-10 aspect-[1155/678] w-[36.125rem] max-w-none -translate-x-1/2 rotate-[30deg] bg-gradient-to-tr from-[#ff80b5] to-[#9089fc] opacity-30 sm:left-[calc(50%-40rem)] sm:w-[72.1875rem]"
                    style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)"/>
            </div>


<!--            <pre>{{ prospective }}</pre>-->
<!--            <pre>{{ address }}</pre>-->


            <div class="py-6 mx-auto max-w-2xl text-center">
                <h2 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">New Customer</h2>
            </div>
            <div class="mx-auto mt-16 max-w-xl sm:mt-20">
                <div class="grid grid-cols-1 gap-x-8 gap-y-6 sm:grid-cols-2">
                    <div>
                        <label for="first-name" class="block text-sm font-semibold leading-6 text-gray-900">First
                            name</label>
                        <div class="mt-2.5">
                            <input type="text" v-model="customer.firstName" name="first-name" id="first-name"
                                   autocomplete="given-name"
                                   class="block w-full rounded-md border-0 px-3.5 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"/>
                        </div>
                    </div>
                    <div>
                        <label for="last-name" class="block text-sm font-semibold leading-6 text-gray-900">Last
                            name</label>
                        <div class="mt-2.5">
                            <input type="text" v-model="customer.lastName" name="last-name" id="last-name"
                                   autocomplete="family-name"
                                   class="block w-full rounded-md border-0 px-3.5 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"/>
                        </div>
                    </div>
                    <div class="sm:col-span-2">
                        <label for="address" class="block text-sm font-semibold leading-6 text-gray-900">Address</label>
                        <div class="mt-2.5">
                            <input type="text" v-model="customer.address" name="address" id="address"
                                   autocomplete="organization"
                                   class="block w-full rounded-md border-0 px-3.5 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"/>
                        </div>
                    </div>

                    <div>
                        <label for="city" class="block text-sm font-semibold leading-6 text-gray-900">City</label>
                        <div class="mt-2.5">
                            <input type="text" v-model="customer.city" name="city" id="city" autocomplete="city"
                                   class="block w-full rounded-md border-0 px-3.5 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"/>
                        </div>
                    </div>
                    <div>
                        <label for="zip" class="block text-sm font-semibold leading-6 text-gray-900">Zip</label>
                        <div class="mt-2.5">
                            <input type="text" v-model="customer.zip" name="zip" id="zip" autocomplete="zip"
                                   class="block w-full rounded-md border-0 px-3.5 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"/>
                        </div>
                    </div>

                    <div>
                        <label for="service-day" class="block text-sm font-semibold leading-6 text-gray-900">Service
                            Day</label>
                        <div class="mt-2.5">
                            <input type="text" v-model="customer.serviceDay" name="service-day" id="service-day"
                                   autocomplete="service-day"
                                   class="block w-full rounded-md border-0 px-3.5 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"/>
                        </div>
                    </div>

                    <div>
                        <label for="plan-price" class="block text-sm font-semibold leading-6 text-gray-900">Plan
                            Price</label>
                        <div class="mt-2.5">
                            <input type="text" v-model="customer.planPrice" name="plan-price" id="plan-price"
                                   autocomplete="zip"
                                   class="block w-full rounded-md border-0 px-3.5 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"/>
                        </div>
                    </div>

                    <div>
                        <label for="assigned-serviceman" class="block text-sm font-semibold leading-6 text-gray-900">Assigned
                            Serviceman</label>
                        <div class="mt-2.5">
                            <input type="text" v-model="customer.assignedServiceman" name="assigned-serviceman"
                                   id="assigned-serviceman" autocomplete="assigned-serviceman"
                                   class="block w-full rounded-md border-0 px-3.5 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"/>
                        </div>
                    </div>

                    <div>
                        <label for="gate-code" class="block text-sm font-semibold leading-6 text-gray-900">Gate
                            Code</label>
                        <div class="mt-2.5">
                            <input type="text" v-model="customer.gateCode" name="gate-code" id="gate-code"
                                   autocomplete="gate-code"
                                   class="block w-full rounded-md border-0 px-3.5 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"/>
                        </div>
                    </div>

                    <!--
          This example requires some changes to your config:

          ```
          // tailwind.config.js
          module.exports = {
          // ...
          plugins: [
          // ...
          require('@tailwindcss/forms'),
          ],
          }
          ```
          -->
                    <!--
            This example requires some changes to your config:

            ```
            // tailwind.config.js
            module.exports = {
              // ...
              plugins: [
                // ...
                require('@tailwindcss/forms'),
              ],
            }
            ```
          -->
                    <div>
                        <label class="text-base font-semibold text-gray-900">Chemicals Included</label>
                        <fieldset class="mt-4">
                            <legend class="sr-only">Chemicals Included</legend>
                            <div class="space-y-4">
                                <div v-for="notificationMethod in chemsIncluded" :key="notificationMethod.id"
                                     class="flex items-center">
                                    <input v-model="customer.method" :value="notificationMethod.title"
                                           :id="notificationMethod.id" name="notification-method"
                                           type="radio"
                                           :checked="notificationMethod.id === 'yes'"
                                           class="h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-600"/>
                                    <label :for="notificationMethod.id" class="ml-3 block text-sm font-medium leading-6
                                         text-gray-900">{{ notificationMethod.title }}</label>
                                </div>
                            </div>
                        </fieldset>
                    </div>

                    <!--                    <SwitchGroup as="div" class="flex gap-x-4 sm:col-span-2">-->
                    <!--                        <div class="flex h-6 items-center">-->
                    <!--                            <Switch v-model="agreed" :class="[agreed ? 'bg-indigo-600' : 'bg-gray-200', 'flex w-8 flex-none cursor-pointer rounded-full p-px ring-1 ring-inset ring-gray-900/5 transition-colors duration-200 ease-in-out focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600']">-->
                    <!--                                <span class="sr-only">Chemicals Included</span>-->
                    <!--                                <span aria-hidden="true" :class="[agreed ? 'translate-x-3.5' : 'translate-x-0', 'h-4 w-4 transform rounded-full bg-white shadow-sm ring-1 ring-gray-900/5 transition duration-200 ease-in-out']" />-->
                    <!--                            </Switch>-->
                    <!--                        </div>-->
                    <!--                        <SwitchLabel class="text-sm leading-6 text-gray-600">-->
                    <!--                            Chemical Included-->
                    <!--                        </SwitchLabel>-->
                    <!--                    </SwitchGroup>-->

                    <div class="sm:col-span-2">
                        <label for="phone-number" class="block text-sm font-semibold leading-6 text-gray-900">Phone
                            number</label>
                        <div class="relative mt-2.5">
                            <input type="tel" v-model="customer.phoneNumber" name="phone-number" id="phone-number"
                                   autocomplete="tel"
                                   class="block w-full rounded-md border-0 px-3.5 py-2 text-gray-900
                                    shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2
                                    focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"/>
                        </div>
                    </div>
                    <div class="sm:col-span-2">
                        <label for="notes" class="block text-sm font-semibold leading-6 text-gray-900">Notes</label>
                        <div class="mt-2.5">
                            <textarea name="notes" v-model="customer.notes" id="notes" rows="4"
                                      class="block w-full rounded-md border-0 px-3.5 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"/>
                        </div>
                    </div>

                </div>
                <div class="mt-10">
                    <!--                    <button @click="addCustomer()" type="submit" class="block w-full rounded-md bg-indigo-600 px-3.5 py-2.5 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Add Customer</button>-->
                    <Link href="/customers/addStore"
                          class="block w-full rounded-md bg-indigo-600 px-3.5 py-2.5 text-center text-sm font-semibold
                          text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2
                          focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                          method="post" as="button" :data="customer">Add Customer
                    </Link>
                </div>
            </div>
        </div>
    </layout>
</template>

<script>
import Layout from "../Shared/Layout";
import {Link} from '@inertiajs/inertia-vue3'
import {ref} from 'vue'
// import { ChevronDownIcon } from '@heroicons/vue/20/solid'
import {Switch, SwitchGroup, SwitchLabel} from '@headlessui/vue'
// import {vue} from "laravel-mix";
// import { router } from ""


export default {
    components: {
        Layout,
        Link,
        Switch,
        SwitchGroup,
        SwitchLabel
    },
    props: {
        user: Object,
        prospective: Object,
        address: Object
    },
    data() {
        return {
            new_customer_phone_number: null,
            customer: {
                userId: "",
                customerId: "",
                addressId: "",
                firstName: "",
                lastName: "",
                address: "",
                city: "",
                zip: "",
                serviceDay: "Saturday",
                planPrice: 120,
                assignedServiceman: "Shawn",
                chemsIncluded: 1,
                gateCode: "",
                phoneNumber: "",
                notes: ""
            },
            chemsIncluded: [
                {id: 'yes', title: 1},
                {id: 'no', title: 0}
            ],
            form: {
                personal_information: {
                    time_in: null,
                    time_out: null,
                    initial_consultation_date: null,
                    primary_phone: null,
                    secondary_phone: null,
                    start_date: null,
                    first_name: null,
                    last_name: null,
                    first_name_2: null,
                    last_name_2: null,
                    plan_price: 115,
                    plan_duration: "weekly",
                    type: true,
                    chems_included: true,
                    plan: "Monthly 100 Includes Chems",
                    service_day: null
                },
                pool_information: {
                    pool_difficulty: null,
                    service_man: null,
                    saturday_service: null,
                    preferred_communication: null,
                    texting_ok: null,
                    early_morning: null,
                    filter_type: null,
                    surface_type: null,
                    pump_type: null,
                    chlorine: null,
                    automation: null,
                    automation_type: null,
                    number_of_lights: null,
                    fiber_optic: null,
                    amerilite: null,
                    led: null,
                    spa_bulb: null,
                    dogs: null,
                    dog_name_1: null,
                    dog_name_2: null,
                    dog_name_3: null,
                    dog_name_4: null,
                    special_instructions_dog: null,
                    gate_locked: null,
                    key: null,
                    special_instructions_gate: null,
                    code: null,
                    repairs: null,
                    chlorine_level: null,
                    pH: null,
                    tds: null,
                    number_of_drops: null,
                    gallons: null
                },
                addresses: []
            }
        }
    },
    mounted() {
        this.customer.userId = this.prospective.user_id;
        this.customer.customerId = this.prospective.id;
        this.customer.addressId = this.address.id;
        this.customer.firstName = this.prospective.first_name;
        this.customer.lastName = this.prospective.last_name;
        this.customer.address = this.address.address_line_1;
        this.customer.city = this.address.city;
        this.customer.zip = this.address.zip;
        this.customer.phoneNumber = this.prospective.phone_number;
    },
    methods: {
        addCustomer() {
            // Perform the POST request
            this.$inertia.post('/customers/store', this.customer, {
                onSuccess: () => {
                    // Handle the success response
                    // e.g., show a success message
                },
                onError: (errors) => {
                    // Handle the error response
                    // e.g., display validation errors
                }
            });
        },
        addAddressFields() {
            var field = document.getElementById('additionalAddress')

            // create a new div element
            const newDiv = document.createElement("h1");

            // and give it some content
            ``           // const newContent = document.createTextNode("Hi there and greetings!");

            // add the text node to the newly created div
            field.appendChild(newDiv);
        },
        agreedValue() {
            console.log('agreed dkjsalfhks', this.agreed)
        }
    }
}
</script>

<style scoped>

</style>

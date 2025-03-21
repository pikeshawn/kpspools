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
                <div class="relative mb-6 w-full max-w-lg">
                    <button
                        @click="searchResults = []"
                        class="px-6 py-2 mb-2 bg-blue-600 text-white font-semibold rounded-lg shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2 transition duration-300"
                    >
                        Clear
                    </button>
                    <input
                        type="text"
                        v-model="searchQuery"
                        @input="fetchResults"
                        class="w-full px-4 py-2 border rounded-lg shadow-sm focus:ring focus:ring-blue-300"
                        placeholder="Search by Name or Address..."
                    />

                    <ul v-if="searchResults.length" class="absolute z-10 w-full bg-white border rounded-lg shadow-lg mt-2 max-h-60 overflow-auto">
                        <li
                            v-for="customer in searchResults"
                            :key="customer.mailing_address_1"
                            @click="selectCustomer(customer)"
                            class="px-4 py-2 hover:bg-blue-100 cursor-pointer"
                        >
                            {{ customer.ownership }} - {{ customer.mailing_address_1 }}
                        </li>
                    </ul>

                    <div v-if="selectedCustomer" class="mt-4 p-4 bg-gray-100 rounded-lg shadow">
                        <h3 class="text-lg font-semibold">Selected Customer</h3>
                        <p><strong>Name:</strong> {{ selectedCustomer.firstName }} {{ selectedCustomer.lastName }}</p>
                        <p><strong>Address:</strong> {{ selectedCustomer.address }}, {{ selectedCustomer.city }}, {{ selectedCustomer.zip }}</p>
                        <p><strong>Sale Date:</strong> {{ selectedCustomer.saleDate }}</p>
                        <p><strong>Sale Price:</strong> {{ selectedCustomer.salePrice }}</p>
                        <button
                            @click="populateFields(selectedCustomer)"
                            class="px-6 py-2 bg-blue-600 text-white font-semibold rounded-lg shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2 transition duration-300"
                        >
                            Use
                        </button>
                    </div>
                </div>
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


                    <div class="mt-2 sm:col-span-2">
                        <label class="block text-sm font-semibold leading-6 text-gray-900">Initiate Bid</label>
                        <div class="mt-2.5">
                            <input type="checkbox" v-model="customer.initiateBid" id="initiateBid" class="mr-2">
                            <label for="initiateBid" class="text-sm text-gray-600">Check to initiate a bid</label>
                        </div>
                    </div>

                    <div id="app" class="p-6">
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-semibold leading-6 text-gray-900">Tasks</label>
                            <div v-for="(task, index) in customer.tasks" :key="index" class="mt-4">
                                <input v-model="task.description" placeholder="Description"
                                       class="block w-full rounded-md border-0 px-3.5 py-2 mb-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"/>
                                <input v-model="task.price" type="number" placeholder="Price"
                                       class="block w-full rounded-md border-0 px-3.5 py-2 mb-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"/>
                                <input v-model="task.quantity" type="number" placeholder="Quantity"
                                       class="block w-full rounded-md border-0 px-3.5 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"/>
                            </div>
                        </div>


                    </div>

                    <!--                <pre>{{ customer.tasks }}</pre>-->

                </div>
            </div>
        </div>
    </layout>
</template>

<script setup>
import { ref, computed } from "vue";
import axios from "axios";

const searchQuery = ref("");
const searchResults = ref([]);
const selectedCustomer = ref(null);

const fetchResults = async () => {
    if (searchQuery.value.length < 2) return;

    try {
        const response = await axios.get(`/customer/search`, {
            params: { query: searchQuery.value }
        });

        searchResults.value = response.data;
    } catch (error) {
        console.error("Error fetching data:", error);
    }
};

const selectCustomer = (customer) => {
    const [firstName, ...lastName] = customer.ownership.split(" ");
    selectedCustomer.value = {
        firstName: firstName || "",
        lastName: lastName.join(" ") || "",
        address: customer.mailing_address_1,
        city: customer.mailing_city,
        zip: customer.mailing_zip,
        saleDate: customer.sale_date,
        salePrice: customer.sale_price
    };
    searchQuery.value = customer.mailing_address_1;
    searchResults.value = [];
};
</script>

<script>
import Layout from "../Shared/Layout.vue";
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
                notes: "",
                initiateBid: false,
                tasks: [
                    {description: "Monthly Service", price: 110, quantity: 1}
                ],
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
        // this.customer.userId = this.prospective.user_id;
        // this.customer.customerId = this.prospective.id;
        // this.customer.addressId = this.address.id;
        // this.customer.firstName = this.prospective.first_name;
        // this.customer.lastName = this.prospective.last_name;
        // this.customer.address = this.address.address_line_1;
        // this.customer.city = this.address.city;
        // this.customer.zip = this.address.zip;
        // this.customer.phoneNumber = this.prospective.phone_number;
    },
    methods: {
        // addTaskContainers(){
        //     const taskContainer = document.getElementById('taskContainer');
        //
        //     // Create task wrapper div
        //     const taskDiv = document.createElement('div');
        //     taskDiv.className = 'mt-4';
        //
        //     const randomInt = getRandomInt(1, 100);
        //     console.log(randomInt); // e.g., 42
        //
        //     // Create description input
        //     const descriptionInput = document.createElement('input');
        //     descriptionInput.type = 'text';
        //     descriptionInput.id = 'description' + randomInt;
        //     descriptionInput.placeholder = 'Description';
        //     descriptionInput.className = 'block w-full rounded-md border-0 px-3.5 py-2 mb-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6';
        //
        //     // Create price input
        //     const priceInput = document.createElement('input');
        //     priceInput.type = 'number';
        //     priceInput.id = 'price' + randomInt;
        //     priceInput.placeholder = 'Price';
        //     priceInput.className = 'block w-full rounded-md border-0 px-3.5 py-2 mb-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6';
        //
        //     // Create quantity input
        //     const quantityInput = document.createElement('input');
        //     quantityInput.type = 'number';
        //     quantityInput.id = 'quantity' + randomInt;
        //     quantityInput.placeholder = 'Quantity';
        //     quantityInput.className = 'block w-full rounded-md border-0 px-3.5 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6';
        //
        //     // Create remove button
        //     const removeButton = document.createElement('button');
        //     removeButton.type = 'button';
        //     removeButton.className = 'mt-2 inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 focus:outline-none focus:border-red-700 focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150';
        //     removeButton.textContent = 'Remove Task';
        //
        //     // Create remove button
        //     const addButton = document.createElement('button');
        //     addButton.type = 'button';
        //     addButton.setAttribute('v-click')
        //     addButton.className = 'mt-2 inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 focus:outline-none focus:border-red-700 focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150';
        //     addButton.textContent = 'Remove Task';
        //
        //     // Remove task on button click
        //     removeButton.addEventListener('click', function() {
        //         taskContainer.removeChild(taskDiv);
        //     });
        //
        //     // Append inputs and button to taskDiv
        //     taskDiv.appendChild(descriptionInput);
        //     taskDiv.appendChild(priceInput);
        //     taskDiv.appendChild(quantityInput);
        //     taskDiv.appendChild(removeButton);
        //
        //     // Append taskDiv to taskContainer
        //     taskContainer.appendChild(taskDiv);
        //
        // },
        populateFields(customer){
            this.customer.firstName = customer.lastName;
            this.customer.lastName = customer.firstName;
            this.customer.address = customer.address;
            this.customer.city = customer.city;
            this.customer.zip = customer.zip;
        },
        addTask() {
            console.log('another task');
            console.log(this.customer.tasks);
            this.customer.tasks.push({description: "", price: 0, quantity: 1});
        },
        addTaskContainer() {
            this.customer.tasks.push({description: "", price: 0, quantity: 1});
        },
        updateTask(index) {
            const task = this.customer.tasks[index];
            if (!task.description) {
                this.customer.tasks.splice(index, 1);
            }
        },
        removeTask(index) {
            console.log('remove me')
            console.log(this.customer.tasks);
            this.customer.tasks.splice(index, 1);
        },
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

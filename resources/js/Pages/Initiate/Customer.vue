<template>
    <layout
        :user="user"
    >
        <div>
            <div class="px-4 sm:px-0">
                <h3 class="text-base font-semibold leading-7 text-gray-900">Initiate Bid And First Tasks</h3>
            </div>
            <div class="mt-6 border-t border-gray-100">
                <dl class="divide-y divide-gray-100">
                    <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                        <dt class="text-sm font-medium leading-6 text-gray-900">Full name</dt>
                        <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ customer.first_name }}
                            {{ customer.last_name }}
                        </dd>
                    </div>
                    <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                        <dt class="text-sm font-medium leading-6 text-gray-900">Plan Price</dt>
                        <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                            {{ customer.plan_price }}
                        </dd>
                    </div>
                    <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                        <dt class="text-sm font-medium leading-6 text-gray-900">Chemicals Included</dt>
                        <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0" v-if="customer.chemicals_included">
                            Yes
                        </dd>
                        <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0" v-else>
                            No
                        </dd>
                    </div>
<!--                    <pre>{{ completedTasks }}</pre>-->
                    <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                        <dt class="text-sm font-medium leading-6 text-gray-900">Completed Tasks</dt>
                        <br>
                        <dd v-for="ct in completedTasks" :key="ct.id" class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                            {{ ct.created_at }}
                        </dd>
                        <dd v-for="ct in completedTasks" :key="ct.id" class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                            {{ ct.description }}
                        </dd>
                        <dd v-for="ct in completedTasks" :key="ct.id" class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                            {{ ct.price }}
                        </dd>
                    </div>
                    <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                        <dt class="text-sm font-medium leading-6 text-gray-900">Stops</dt>
                        <br>
                        <dd v-for="ss in serviceStops" :key="ss.id" class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                            {{ ss.created_at }} {{ ss.service_type }} {{ ss.user_id }}
                        </dd>
                    </div>
                    <div class="relative flex items-start">
                        <div class="flex h-6 items-center">
                            <input v-model="form.initiateBid" id="comments" aria-describedby="comments-description"
                                   name="comments" type="checkbox"
                                   class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600"/>
                        </div>
                        <div class="ml-3 text-sm leading-6">
                            <label for="comments" class="font-medium text-gray-900">Initiate Bid</label>
                            <p id="comments-description" class="text-gray-500">Initiate their first bid</p>
                        </div>
                    </div>
                    <div class="px-4 py-6 sm:gap-4 sm:px-0">
                        <div v-for="task in form.tasks" :key="task.id" class="flex flex-col">
                            <div class="mt-2">
                                <input v-model="task.name" type="text" name="taskName1" id="taskName1"
                                       class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                       placeholder="Task Name"/>
                            </div>
                            <div class="mt-2">
                                <input v-model="task.price" type="number" name="taskName1" id="taskPrice1"
                                       class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                       placeholder="Task Price"/>
                            </div>
                            <div class="mt-2">
                                <input v-model="task.qty" type="number" name="taskQty1" id="taskName1"
                                       class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                       placeholder="Task Quantity"/>
                            </div>
                            <hr style="margin: 1rem">
                        </div>
                    </div>
                </dl>
                <button @click="sendBid()"
                        class="mt-4 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    sendBid
                </button>
            </div>
        </div>

<!--        <pre>{{ serviceStops }}</pre>-->
<!--        <pre>{{ customer }}</pre>-->

    </layout>

</template>
<script setup>
import {PaperClipIcon} from '@heroicons/vue/20/solid'
import {RadioGroup, RadioGroupLabel, RadioGroupOption} from "@headlessui/vue";
</script>
<script>
import Layout from "../Shared/Layout";
import DropDown from "../Shared/DropDown";
import Toggle from "../Shared/Toggle";
import LoadingButton from "../Shared/LoadingButton";
import {Inertia} from '@inertiajs/inertia'
import {reactive} from 'vue'
import {Link} from '@inertiajs/inertia-vue3'


export default {
    name: "Index",
    components: {
        DropDown,
        LoadingButton,
        Link,
        Layout,
        Toggle
    },
    props: {
        customer: Array,
        serviceStops: Array,
        completedTasks: Array,
        user: String
    },
    data() {

        return {
            paymentOptions: [
                {
                    option: 'cash',
                    label: 'Cash'
                },
                {
                    option: 'creditCard',
                    label: 'Credit Card'
                }
            ],
            form: {
                initiateBid: true,
                paymentOption: 'creditCard',
                tasks: [
                    {
                        id: 1,
                        name: null,
                        price: null,
                        qty: 1,
                    }, {
                        id: 2,
                        name: null,
                        price: null,
                        qty: 1,
                    }, {
                        id: 3,
                        name: null,
                        price: null,
                        qty: 1,
                    }, {
                        id: 4,
                        name: null,
                        price: null,
                        qty: 1,
                    }, {
                        id: 5,
                        name: null,
                        price: null,
                        qty: 1,
                    },
                ],
                customer: this.customer,
                serviceStops: this.serviceStops
            },
        }
    },
    mounted() {
    },
    beforeUpdate() {
    },
    updated() {
    },
    methods: {
        sendBid() {
            Inertia.post('/sendBid', this.form);
        }
    },
    computed: {}
}
</script>

<style scoped>

</style>

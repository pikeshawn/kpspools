<template>
    <Layout>
        <Link
            class="mb-2.5 inline-flex items-center px-6 py-3 border border-transparent
            text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700
            focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            :href="route('customers')">Customers
        </Link>

        <div class="bg-gray-900 px-6 py-24 sm:py-8 lg:px-8">
            <div class="mx-auto max-w-2xl text-center">
                <h2 class="text-4xl font-bold tracking-tight text-white sm:text-6xl">{{ customer.first_name }}
                    {{ customer.last_name }}</h2>
                <div>
                    <div class="mt-6 border-t border-white/10">
                        <dl class="">
                            <div class="px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                <dt class="text-sm font-medium leading-6 text-white">Address</dt>
                                <dd class="mt-1 text-sm leading-6 text-gray-400 sm:col-span-2 sm:mt-0">{{ address[0].address_line_1 }}, {{ address[0].city }} {{ address[0].state }} {{ address[0].zip }}</dd>
                            </div>
                            <div class="px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                <dt class="text-sm font-medium leading-6 text-white">Gate Code</dt>
                                <dd class="mt-1 text-sm leading-6 text-gray-400 sm:col-span-2 sm:mt-0">{{ address[0].community_gate_code }}</dd>
                            </div>
                            <div class="px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                <dt class="text-sm font-medium leading-6 text-white">Phone Number</dt>
                                <dd class="mt-1 text-sm leading-6 text-gray-400 sm:col-span-2 sm:mt-0">{{ customer.phone_number }}</dd>
                            </div>
                            <div v-if="user.id === 2">
                                <div class="px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                    <dt class="text-sm font-medium leading-6 text-white">Plan Price</dt>
                                    <dd class="mt-1 text-sm leading-6 text-gray-400 sm:col-span-2 sm:mt-0">{{ customer.plan_price }}</dd>
                                </div>
                                <div class="px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                    <dt class="text-sm font-medium leading-6 text-white">Chemicals Included</dt>
                                    <dd class="mt-1 text-sm leading-6 text-gray-400 sm:col-span-2 sm:mt-0">{{ customer.chemicals_included }}</dd>
                                </div>
                                <div class="px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                    <dt class="text-sm font-medium leading-6 text-white">Service Day</dt>
                                    <dd class="mt-1 text-sm leading-6 text-gray-400 sm:col-span-2 sm:mt-0">{{ customer.service_day }}</dd>
                                </div>
                                <div class="px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                    <dt class="text-sm font-medium leading-6 text-white">Terms</dt>
                                    <dd class="mt-1 text-sm leading-6 text-gray-400 sm:col-span-2 sm:mt-0">{{ customer.terms }}</dd>
                                </div>
                            </div>
                        </dl>
                    </div>
                </div>

                <button style="margin-top: 2rem;"
                        type="button" @click="textMessage.textDialog = !textMessage.textDialog"
                        class="rounded-md bg-indigo-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                    On My Way
                </button>
            </div>
        </div>

        <div v-show="textMessage.textDialog" class="m-3 justify-center"
             style="background-color:white; margin: 2rem; padding: 2rem;">
            <div style="margin-left: auto; margin-right: auto">
                <label for="company-website" class="block text-sm font-medium leading-6 text-gray-900">On My Way Text
                    Message</label>
                <div class="mt-2">
                    <!--                    <div-->
                    <!--                        class="flex rounded-md shadow-sm ring-1 ring-inset ring-gray-300 focus-within:ring-2 focus-within:ring-inset focus-within:ring-indigo-600 sm:max-w-md">-->
                    <!--                        <input type="text" name="company-website" id="company-website"-->
                    <!--                               class="block flex-1 border-0 bg-transparent py-1.5 pl-1 text-gray-900 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6"-->
                    <!--                               v-model="textMessage.customerPhoneNumber"/>-->
                    <!--                    </div>-->
                    <div
                        class="flex rounded-md shadow-sm ring-1 ring-inset ring-gray-300 focus-within:ring-2 focus-within:ring-inset focus-within:ring-indigo-600 sm:max-w-md">
                        <textarea rows="5" type="text" name="company-website" id="company-website"
                                  class="block flex-1 border-0 bg-transparent py-1.5 pl-1 text-gray-900 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6"
                                  v-model="textMessage.textMessage"/>
                    </div>
                    <button type="button" @click="textMessage.textDialog = !textMessage.textDialog" class="rounded-md bg-indigo-600 px-3.5 py-2.5
                text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline
                focus-visible:outline-2 focus-visible:outline-offset-2
                focus-visible:outline-indigo-600" style="margin: 0.5rem;">Cancel
                    </button>
                    <button type="button" @click="onMyWay()" class="rounded-md bg-indigo-600 px-3.5 py-2.5 text-sm
                font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2
                focus-visible:outline-offset-2 focus-visible:outline-indigo-600" style="margin: 0.5rem;">Send
                    </button>
                </div>
            </div>
        </div>

        <div class="p-10">

            <h1>Picked Up Tasks</h1>

            <div class="pl-6 pr-6 flex items-center justify-between space-x-3">
                <h3 class="truncate text-3xl font-medium text-gray-900">Name</h3>
                <h3 class="truncate text-3xl font-medium text-gray-900">Picked Up</h3>
            </div>

            <ul role="list" class="divide-y divide-gray-200">
                <li v-for="item in tasks" :key="item.id" class="py-4">
                    <!-- Your content -->
                    <div class="flex-1 truncate">
                        <div class="flex items-center justify-between space-x-3">
                            <div>
                                <h3 class="truncate text-sm font-medium text-gray-900">{{ item.description }}</h3>
                            </div>
                            <div>
                                <Switch
                                    @click="emitEnabled(item)"
                                    id="toggle" v-model="item.completed"
                                    :class="[item.completed ? 'bg-indigo-600' : 'bg-gray-200', 'relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500']">
                                    <span class="sr-only">Use setting</span>
                                    <span aria-hidden="true"
                                          :class="[item.completed ? 'translate-x-5' : 'translate-x-0', 'pointer-events-none inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200']"/>
                                </Switch>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>

            <button @click="completed()"
                    class="mt-4 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Completed
            </button>

<!--            <pre>{{ tasks }}</pre>-->

        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <inertia-link
                class="relative inline-flex items-center px-4 py-2 rounded-l-md bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500"
                :href="route('service_stops.create', customer.id)"
            >
                <div
                    class="relative flex items-center space-x-3 rounded-lg bg-white px-6 py-5 focus-within:ring-2 focus-within:ring-indigo-500 focus-within:ring-offset-2 hover:border-gray-400">
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-medium text-gray-900">Create A Service Stop</p>
                    </div>
                </div>
            </inertia-link>
            <inertia-link
                class="relative inline-flex items-center px-4 py-2 rounded-l-md bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500"
                :href="route('task.create', customer.id)"
            >
                <div
                    class="relative flex items-center space-x-3 rounded-lg bg-white px-6 py-5 focus-within:ring-2 focus-within:ring-indigo-500 focus-within:ring-offset-2 hover:border-gray-400">
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-medium text-gray-900">Add A Task</p>
                    </div>
                </div>
            </inertia-link>
            <inertia-link
                class="relative inline-flex items-center px-4 py-2 rounded-l-md bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500"
                :href="route('service_stops', customer.id)"
            >
                <div
                    class="relative flex items-center space-x-3 rounded-lg bg-white px-6 py-5 focus-within:ring-2 focus-within:ring-indigo-500 focus-within:ring-offset-2 hover:border-gray-400">
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-medium text-gray-900">History</p>
                    </div>
                </div>
            </inertia-link>
            <inertia-link
                class="relative inline-flex items-center px-4 py-2 rounded-l-md bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500"
                :href="route('summary', customer.id)"
            >
                <div
                    class="relative flex items-center space-x-3 rounded-lg bg-white px-6 py-5 focus-within:ring-2 focus-within:ring-indigo-500 focus-within:ring-offset-2 hover:border-gray-400">
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-medium text-gray-900">Summary</p>
                    </div>
                </div>
            </inertia-link>
            <inertia-link
                class="relative inline-flex items-center px-4 py-2 rounded-l-md bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500"
                :href="route('service_stops.notes', customer.id)"
            >
                <div
                    class="relative flex items-center space-x-3 rounded-lg bg-white px-6 py-5 focus-within:ring-2 focus-within:ring-indigo-500 focus-within:ring-offset-2 hover:border-gray-400">
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-medium text-gray-900">Service Stop Notes</p>
                    </div>
                </div>
            </inertia-link>

        </div>

        <div style="margin-top: 1rem;">
            <div v-for="note in notes" :key="note.id" class="relative inline-flex items-center px-4 py-2 rounded-l-md bg-white text-sm font-medium
                text-gray-700 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-indigo-500
                focus:border-indigo-500">
                <div
                    class="relative flex items-center space-x-3 rounded-lg bg-white px-6 py-5 focus-within:ring-2 focus-within:ring-indigo-500 focus-within:ring-offset-2 hover:border-gray-400">
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-medium text-gray-900">{{ note.note }}</p>
                    </div>
                </div>
            </div>
        </div>


        <h1>Completed Tasks</h1>
        <div class="pl-6 pr-6 flex items-center justify-between space-x-3">
            <h3 class="truncate text-3xl font-medium text-gray-900">Name</h3>
            <h3 class="truncate text-3xl font-medium text-gray-900">Remove</h3>
        </div>

        <ul role="list" class="divide-y divide-gray-200">
            <li v-for="item in completedTasks" :key="item.id" class="py-4">
                <!-- Your content -->
                <div class="flex-1 truncate">
                    <div class="flex items-center justify-between space-x-3">
                        <div>
                            <h3 class="truncate text-sm font-medium text-gray-900">{{ item.description }}</h3>
                        </div>
                        <div>
                            <Switch
                                @click="emitEnabled(item)"
                                id="toggle" v-model="item.completed"
                                :class="[item.completed ? 'bg-indigo-600' : 'bg-gray-200', 'relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500']">
                                <span class="sr-only">Use setting</span>
                                <span aria-hidden="true"
                                      :class="[item.completed ? 'translate-x-5' : 'translate-x-0', 'pointer-events-none inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200']"/>
                            </Switch>
                        </div>
                    </div>
                </div>
            </li>
        </ul>

        <button @click="notCompleted()"
                class="mt-4 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            Not Completed
        </button>

<!--        <pre>{{ completedTasks }}</pre>-->

    </Layout>

</template>

<script>

import JetInput from '@/Jetstream/Input'
import SimpleTable from "../Shared/SimpleTable";
import Layout from "../Shared/Layout";
import {Link} from '@inertiajs/inertia-vue3'
import {Inertia} from "@inertiajs/inertia";

export default {
    name: 'CustomerIndex',
    components: {
        JetInput,
        Layout,
        SimpleTable,
        Link
    },
    mounted() {

    },
    data() {
        return {
            textMessage: {
                customerName: this.customer.first_name + " " + this.customer.last_name,
                customerPhoneNumber: null,
                textMessage: this.user.name + " at KPS Pools will be servicing your pool within 30 minutes.",
                textDialog: false,
                userPhoneNumber: this.user.phone_number
            }
        }
    },
    props: {
        customer: String,
        notes: String,
        address: String,
        user: String,
        tasks: Array,
        completedTasks: Array
    },
    methods: {

        completed() {
            Inertia.post('/task/completed', this.tasks)
        },

        notCompleted() {
            Inertia.post('/task/notCompleted', this.completedTasks)
        },

        emitEnabled(item) {
            item.completed = !item.completed
        },

        onMyWay() {

            if (this.customer.phone_number === '14807034902') {
                this.textMessage.customerPhoneNumber = '14806226441';
            } else if (this.customer.phone_number === '16238260681') {
                this.textMessage.customerPhoneNumber = '15209097558';
            } else if (this.customer.phone_number === '14803189416') {
                this.textMessage.customerPhoneNumber = '14803189419';
            } else if (this.customer.phone_number === '16023124131') {
                this.textMessage.customerPhoneNumber = '16026167672';
            } else {
                this.textMessage.customerPhoneNumber = this.customer.phone_number;
            }

            Inertia.post('/service_stops/sendText', this.textMessage)
            this.textMessage.textDialog = false
        }
    }
}

</script>

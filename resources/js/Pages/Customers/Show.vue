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
                                <dd class="mt-1 text-sm leading-6 text-gray-400 sm:col-span-2 sm:mt-0">
                                    {{ address[0].address_line_1 }}, {{ address[0].city }} {{ address[0].state }}
                                    {{ address[0].zip }}
                                </dd>
                            </div>
                            <div class="px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                <dt class="text-sm font-medium leading-6 text-white">Gate Code</dt>
                                <dd class="mt-1 text-sm leading-6 text-gray-400 sm:col-span-2 sm:mt-0">
                                    {{ address[0].community_gate_code }}
                                </dd>
                            </div>
                            <div class="px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                <dt class="text-sm font-medium leading-6 text-white">Phone Number</dt>
                                <dd class="mt-1 text-sm leading-6 text-gray-400 sm:col-span-2 sm:mt-0">
                                    {{ customer.phone_number }}
                                </dd>
                            </div>
                            <div v-if="user.is_admin === 1">
                                <div class="flex justify-between">
                                    <div class="px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                        <dt class="text-sm font-medium leading-6 text-white">Plan Price</dt>
                                        <dd class="mt-1 text-sm leading-6 text-gray-400 sm:col-span-2 sm:mt-0">
                                            {{ customer.plan_price }}
                                        </dd>
                                    </div>
                                    <div class="px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                        <dt class="text-sm font-medium leading-6 text-white">Chemicals Included</dt>
                                        <dd class="mt-1 text-sm leading-6 text-gray-400 sm:col-span-2 sm:mt-0">
                                            {{ customer.chemicals_included }}
                                        </dd>
                                    </div>
                                </div>
                                <div class="flex justify-between">
                                    <div class="px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                        <dt class="text-sm font-medium leading-6 text-white">Service Day</dt>
                                        <dd class="mt-1 text-sm leading-6 text-gray-400 sm:col-span-2 sm:mt-0">
                                            {{ customer.service_day }}
                                        </dd>
                                    </div>
                                    <div class="px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                        <dt class="text-sm font-medium leading-6 text-white">Serviceman</dt>
                                        <dd class="mt-1 text-sm leading-6 text-gray-400 sm:col-span-2 sm:mt-0">
                                            {{ serviceman.name }}
                                        </dd>
                                    </div>
                                    <div class="px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                        <dt class="text-sm font-medium leading-6 text-white">Terms</dt>
                                        <dd class="mt-1 text-sm leading-6 text-gray-400 sm:col-span-2 sm:mt-0">
                                            {{ customer.terms }}
                                        </dd>
                                    </div>
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

        <div class="py-6">

            <div>
                <div class="sm:hidden">
                    <div class="flex justify-between">
                        <div>Created: {{ createdObjects }}</div>
                        <div>Not Completed: {{ approvedObjects }}</div>
                        <div>Completed: {{ completedObjects }}</div>
                    </div>
                    <label for="tabs" class="sr-only">Select a tab</label>
                    <!-- Use an "onChange" listener to redirect the user to the selected tab URL. -->
                    <select id="tabs" name="tabs"
                            v-model="myTab"
                            @change="changeSmallTab()"
                            class="block w-full rounded-md border-gray-300 py-2 pl-3 pr-10 text-base focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm">
                        <option v-for="tab in tabs" :key="tab.name" :selected="tab.current">{{
                                tab.alternateName
                            }}
                        </option>
                    </select>
                </div>
                <div class="hidden sm:block">
                    <div class="border-b border-gray-200">
                        <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                            <a v-for="tab in tabs"
                               :key="tab.name"
                               :href="tab.href"
                               :data-item="tab"
                               @click="changeTab(tab)"
                               :class="[tab.current ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700', 'whitespace-nowrap border-b-2 py-4 px-1 text-sm font-medium']"
                               :aria-current="tab.current ? 'page' : undefined">
                                {{ tab.alternateName }}
                                <span
                                    class="inline-flex items-center rounded-md bg-pink-400/10 px-2 py-1 text-xs font-medium text-pink-400 ring-1 ring-inset ring-pink-400/20">{{
                                        tab.count
                                    }}</span>
                            </a>
                        </nav>
                    </div>
                </div>
            </div>

            <div v-show="showTab === 'Created'">
                <div v-if="tabs[0].count > 0" class="px-4 sm:px-6 lg:px-8">
                    <div class="mt-8 flow-root">
                        <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                            <div class="inline-block min-w-full py-2 align-middle">
                                <table class="min-w-full divide-y divide-gray-300">
                                    <thead>
                                    <tr>
                                        <th scope="col"
                                            class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                            Description
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 bg-white">
                                    <tr v-for="item in tasks" :key="item.task_id">
                                        <td v-if="item.status === 'created'"
                                            class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                            <div>{{ item.description }}</div>
                                            <div>_______________________</div>
                                            <div>{{ item.assigned }}</div>
                                        </td>
                                        <td v-if="item.status === 'created'"
                                            class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6 lg:pr-8">
                                            <button @click="remove(item)"
                                                    class="inline-flex justify-center py-1 px-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                Remove
                                            </button>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
            <div v-show="showTab === 'PickedUp'">
                <div v-if="tabs[1].count > 0" class="px-4 sm:px-6 lg:px-8">
                    <div class="mt-8 flow-root">
                        <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                            <div class="inline-block min-w-full py-2 align-middle">
                                <table class="min-w-full divide-y divide-gray-300">
                                    <thead>
                                    <tr>
                                        <th scope="col"
                                            class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                            Description
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 bg-white">
                                    <tr v-for="item in tasks" :key="item.task_id">
                                        <td v-if="item.status === 'pickedUp'"
                                            class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                            <div>{{ item.description }}</div>
                                            <div>_______________________</div>
                                            <div>{{ item.assigned }}</div>
                                        </td>
                                        <td v-if="item.status === 'pickedUp'"
                                            class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6 lg:pr-8">
                                            <div class="flex justify-between">
                                                <button @click="completed(item)"
                                                        class="nline-flex justify-center py-1 px-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                    Complete
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div v-show="showTab === 'Completed'">
                <div v-if="tabs[2].count > 0" class="px-4 sm:px-6 lg:px-8">
                    <div class="mt-8 flow-root">
                        <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                            <div class="inline-block min-w-full py-2 align-middle">
                                <table class="min-w-full divide-y divide-gray-300">
                                    <thead>
                                    <tr>
                                        <th scope="col"
                                            class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                            Description
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 bg-white">
                                    <tr v-for="item in tasks" :key="item.task_id">
                                        <td v-if="item.status === 'completed'"
                                            class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                            <div>{{ item.description }}</div>
                                            <div>_______________________</div>
                                            <div>{{ item.assigned }}</div>
                                        </td>
                                        <td v-if="item.status === 'completed'"
                                            class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6 lg:pr-8">
                                            <div class="flex justify-between">
                                                <button @click="notCompleted(item)"
                                                        class="inline-flex justify-center py-1 px-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                    Not Completed
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

<!--        <pre>{{ tasks }}</pre>-->

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
                v-if="user.is_admin"
                class="relative inline-flex items-center px-4 py-2 rounded-l-md bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500"
                :href="route('task.customerTasks', {'customerId': customer.id})"
            >
                <div
                    class="relative flex items-center space-x-3 rounded-lg bg-white px-6 py-5 focus-within:ring-2 focus-within:ring-indigo-500 focus-within:ring-offset-2 hover:border-gray-400">
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-medium text-gray-900">Needs Approval</p>
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
        this.updateCount();
    },
    beforeUpdate() {
        this.updateCount();
    },
    updated() {
        this.updateCount();
    },
    data() {
        return {
            showTab: 'PickedUp',
            myTab: 'Ready To Complete',
            textMessage: {
                customerName: this.customer.first_name + " " + this.customer.last_name,
                customerPhoneNumber: null,
                textMessage: this.user.name + " at KPS Pools will be servicing your pool within 30 minutes.",
                textDialog: false,
                userPhoneNumber: this.user.phone_number
            },
            tabs: [
                {name: 'Created', alternateName: 'New', href: '#', current: false, count: 0},
                {name: 'PickedUp', alternateName: 'Ready To Complete', href: '#', current: true, count: 0},
                {name: 'Completed', alternateName: 'Finished', href: '#', current: false, count: 0}
            ],
            createdObjects: 0,
            approvedObjects: 0,
            completedObjects: 0,
        }
    },
    props: {
        customer: String,
        notes: String,
        address: String,
        user: String,
        tasks: Array,
        serviceman: Array,
        completedTasks: Array
    },
    methods: {
        updateCount() {

            this.createdObjects = this.tasks.filter(task => task.status === 'created').length;
            this.approvedObjects = this.tasks.filter(task => task.status === 'pickedUp').length;
            this.completedObjects = this.tasks.filter(task => task.status === 'completed').length;

            this.tabs[0].count = this.createdObjects;
            this.tabs[1].count = this.approvedObjects;
            this.tabs[2].count = this.completedObjects;
        },
        changeTab(tab) {
            for (let i = 0; i < this.tabs.length; i++) {
                this.tabs[i].current = false
                if (this.tabs[i].name === tab.name) {
                    this.tabs[i].current = true
                    this.showTab = tab.name
                }
            }
        },
        changeSmallTab() {
            for (let i = 0; i < this.tabs.length; i++) {
                this.tabs[i].current = false
                if (this.tabs[i].alternateName === this.myTab) {
                    this.tabs[i].current = true

                    if (this.myTab === 'New') {
                        this.showTab = 'Created';
                    } else if (this.myTab === 'Ready To Complete') {
                        this.showTab = 'PickedUp';
                    } else if (this.myTab === 'Finished') {
                        this.showTab = 'Completed';
                    }

                }
            }
        },
        remove(item) {
            Inertia.post('/task/deleteItem', item)
        },
        completed(item) {
            Inertia.post('/task/completed', item)
        },

        notCompleted(item) {
            Inertia.post('/task/notCompleted', item)
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

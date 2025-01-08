<template>
    <layout
        :user="user"
    >

        <!--        Section for Admin users  -->


        <div>
            <div class="sm:hidden">
                <div class="flex justify-between">
                    <div>List: {{ createdObjects }}</div>
                    <div>Inventory: {{ approvedObjects }}</div>
                </div>
                <label for="tabs" class="sr-only">Select a tab</label>
                <!-- Use an "onChange" listener to redirect the user to the selected tab URL. -->
                <select id="tabs" name="tabs"
                        v-model="myTab"
                        @change="changeSmallTab()"
                        class="block w-full rounded-md border-gray-300 py-2 pl-3 pr-10 text-base focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm">
                    <option v-for="tab in tabs" :key="tab.name" :selected="tab.current">{{ tab.alternateName }}</option>
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


            <div class="py-6 px-4 sm:px-6 lg:px-8">
                <div class="mt-8 flow-root">
                    <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                        <div class="inline-block min-w-full py-2 align-middle">
                            <table class="min-w-full divide-y divide-gray-300">
                                <thead>
                                <tr>
                                    <th scope="col"
                                        class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6 lg:pl-8">
                                        Item
                                    </th>
                                </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white">
                                <tr v-for="item in tasks" :key="item.task_id">
                                    <td v-if="item.status === 'approved'"
                                        class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6 lg:pl-8">
                                        <div class="flex" style="flex-direction: column">
                                            <div class="py-1 text-base font-semibold leading-6 text-gray-900">{{ item.first_name }} {{ item.last_name }}</div>
                                            -----------------------------------
                                            <div v-if="item.scp_id">
                                                <h3 class="text-base font-semibold leading-6 text-gray-900">
                                                    {{ item.scp_id }}
                                                </h3>
                                                -----------------------------------
                                            </div>
                                            <div class="py-1">{{ item.description }}</div>
                                            -----------------------------------
                                            <Switch
                                                @click="emitEnabled(item)"
                                                id="toggle" v-model="item.pickedUp"
                                                :class="[item.pickedUp ? 'bg-indigo-600' : 'bg-gray-200', 'relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500']">
                                                <span class="sr-only">Use setting</span>
                                                <span aria-hidden="true"
                                                      :class="[item.pickedUp ? 'translate-x-5' : 'translate-x-0', 'pointer-events-none inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200']"/>
                                            </Switch>
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <button @click="store()"
                    class="mt-4 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Picked Up
            </button>

            <!--            <div v-for="t in tasks">-->
            <!--                <pre v-if="t.status === 'created'"> {{ t }}</pre>-->
            <!--            </div>-->

        </div>

        <div v-show="showTab === 'Approved'">


            <div class="py-6 px-4 sm:px-6 lg:px-8">
                <div class="mt-8 flow-root">
                    <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                        <div class="inline-block min-w-full py-2 align-middle">
                            <table class="min-w-full divide-y divide-gray-300">
                                <thead>
                                <tr>
                                    <th scope="col"
                                        class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6 lg:pl-8">
                                        Name
                                    </th>
                                </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white">
                                <tr v-for="item in tasks" :key="item.task_id">
                                    <td v-if="item.status === 'pickedUp'"
                                        class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6 lg:pl-8">
                                        <div class="flex" style="flex-direction: column">
                                            <div class="py-1">{{ item.first_name }} {{ item.last_name }}</div>
                                            <div class="py-1">{{ item.description }}</div>
                                            <Switch
                                                @click="emitEnabled(item)"
                                                id="toggle" v-model="item.pickedUp"
                                                :class="[item.pickedUp ? 'bg-indigo-600' : 'bg-gray-200', 'relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500']">
                                                <span class="sr-only">Use setting</span>
                                                <span aria-hidden="true"
                                                      :class="[item.pickedUp ? 'translate-x-5' : 'translate-x-0', 'pointer-events-none inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200']"/>
                                            </Switch>
                                        </div>

                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <button @click="remove()"
                    class="mt-4 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Remove
            </button>

        </div>

        <!--        <pre>{{ tasks }}</pre>-->

    </layout>

</template>

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
        tasks: Array,
        user: String
    },
    data() {
        return {
            showTab: 'Created',
            myTab: 'Pickup',
            approved: null,
            pickedUp: false,
            createdObjects: 0,
            approvedObjects: 0,
            tabs: [
                {name: 'Created', alternateName: 'Pickup', href: '#created', current: true, count: 0},
                {name: 'Approved', alternateName: 'Inventory', href: '#approval', current: false, count: 0}
            ]
        }
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
    methods: {
        updateCount() {

            this.createdObjects = this.tasks.filter(task => task.status === 'approved').length;
            this.approvedObjects = this.tasks.filter(task => task.status === 'pickedUp').length;

            this.tabs[0].count = this.createdObjects;
            this.tabs[1].count = this.approvedObjects;
        },
        changeTab(tab) {
            for (let i = 0; i < this.tabs.length; i++) {
                this.tabs[i].current = false

                // this.tabs[i].current = this.tabs[i].name === tab.name;

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

                    if (this.myTab === 'Pickup') {
                        this.showTab = 'Created';
                    } else if (this.myTab === 'Inventory') {
                        this.showTab = 'Approved';
                    }
                }
            }
        },
        emitEnabled(item) {
            item.pickedUp = !item.pickedUp
        },
        store() {
            Inertia.post('/task/pickedUp', this.tasks)
        },
        remove() {
            Inertia.post('/task/remove', this.tasks)
        }
    },
    computed: {
        errorClass() {
            return this.errors.timeIn ? 'text-red-600' : ''
        },
        approvedTasks() {
            let approved = [];
            for (let i = 0; i < this.tasks.length; i++) {
                if (this.tasks[i].status === 'approved') {
                    approved.push(this.tasks[i])
                }
            }
            return approved
        },
        inventoryTasks() {
            let pickedUp = [];
            for (let i = 0; i < this.tasks.length; i++) {
                if (this.tasks[i].status === 'pickedUp') {
                    pickedUp.push(this.tasks[i])
                }
            }
            return pickedUp
        }
    }
}
</script>

<style scoped>

</style>

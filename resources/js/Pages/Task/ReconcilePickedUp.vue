<template>
    <layout
        :user="user"
    >
        <task-reconcile-layout>


            <ul role="list" class="">
                <li v-for="item in tasks" :key="item.id" class="py-2">
                    <!-- Your content -->
                    <div class="border-b border-gray-200 bg-white px-4 py-5 sm:px-6">
                        <div class="flex justify-between">
                            <div class="flex" style="flex-direction: column;">
                                <h3 class="text-base font-semibold leading-6 text-gray-900">
                                    {{ item.first_name }} {{ item.last_name }}
                                </h3>
                                <label class="block text-sm font-medium leading-6 text-gray-900">
                                    {{ item.phone_number }}
                                </label>
                            </div>
                            <Link :href="route('customers.show', item.address_id)"
                                  class="sticky top-0 z-10 border-y border-b-blue-500 border-t-blue-400 bg-blue-200 px-3 py-2 text-sm font-semibold leading-6 text-gray-900"
                                  method="get" as="button">
                                <label class="block text-sm font-medium leading-6 text-gray-900">Customer
                                    Page</label>
                            </Link>
                            <h3 class="text-base font-semibold leading-6 text-gray-900">{{ item.name }}</h3>
                            <div class="flex flex-col">
                                <h3 class="text-base font-semibold leading-6 text-gray-900">{{ item.status }}</h3>
                                <h3 class="text-base font-semibold leading-6 text-gray-900">{{
                                        item.created_at
                                    }}</h3>
                            </div>
                        </div>
                        <div class="py-2">
                            <label for="description" class="block text-sm font-medium leading-6 text-gray-900">Description</label>
                            <div class="mt-2">
                                <input type="text" name="description" id="description"
                                       @blur="changeDescription(item)"
                                       class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1
                                       ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset
                                       focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                       v-model="item.description"/>
                            </div>
                        </div>
                        <div class="py-2 flex">
                            <div class="w-1/2">
                                <div class="flex justify-between">
                                    <label for="price"
                                           class="block text-sm font-medium leading-6 text-gray-900">Price</label>
                                </div>
                                <div class="mt-2">
                                    <input type="text" name="price" id="price"
                                           @blur="updatePrice(item)"
                                           class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1
                                       ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset
                                       focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                           v-model="item.price"/>
                                </div>
                            </div>
                            <div
                                class="py-2 pl-2 w-1/2">
                                <label for="status" class="block text-sm font-medium text-gray-700">Change
                                    Type</label>
                                <select id="status" name="status"
                                        v-model='item.type'
                                        @change="changeType(item)"
                                        class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option
                                        v-for="option in type" :value="option.name">
                                        {{ option.name }}
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="flex justify-between">
                            <div
                                class="py-2 pr-2 w-full">
                                <label for="serviceman" class="block text-sm font-medium text-gray-700">Assigned
                                    Serviceman</label>
                                <select id="serviceman" name="serviceman"
                                        v-model='item.assigned'
                                        @change="assignServiceman(item)"
                                        class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option
                                        v-for="option in servicemen" :value="option.name">
                                        {{ option.name }}
                                    </option>
                                </select>
                            </div>

                            <div
                                class="py-2 pl-2 w-full">
                                <label for="status" class="block text-sm font-medium text-gray-700">Change
                                    Status</label>
                                <select id="status" name="status"
                                        v-model='item.status'
                                        @change="changeStatus(item)"
                                        class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option
                                        v-for="option in status" :value="option.name">
                                        {{ option.name }}
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            <div>
                                <label v-if="item.sent" for="price"
                                       class="block text-sm font-medium leading-6 text-green-800">Approval
                                    Sent</label>
                                <button @click="requestApproval(item)"
                                        class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm
                                text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700
                                focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Request Approval
                                </button>
                            </div>

                            <!--                            <div v-if="item.sent"><span class="-->
                            <!--                            mt-4 text-sm rounded-md py-2 px-4 font-medium bg-green-800 text-white-->
                            <!--                            ">Approval Sent</span></div>-->

                            <button @click="deleteItem(item)"
                                    class="mt-4 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm
                                text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700
                                focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                Delete
                            </button>
                        </div>
                    </div>
                    <div v-if="item.deleted" class="rounded-md bg-red-50 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <XCircleIcon class="h-5 w-5 text-red-400" aria-hidden="true"/>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">You are about to delete this item.
                                    Do you
                                    wish to continue</h3>
                                <div class="flex justify-around mt-2 text-sm text-red-700">
                                    <button @click="doTheDeletion(item)"
                                            class="mt-4 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm
                                text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700
                                focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                        Yes
                                    </button>
                                    <button @click="cancelDeletion(item)"
                                            class="mt-4 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm
                                text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700
                                focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        No
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>


            <pre>{{ tasks }}</pre>


        </task-reconcile-layout>

        <!--        {{ tasks }}-->

    </layout>

</template>

<script>
import TaskReconcileLayout from "./TaskReconcileLayout.vue";
import Layout from "../Shared/Layout";
import DropDown from "../Shared/DropDown";
import Toggle from "../Shared/Toggle";
import LoadingButton from "../Shared/LoadingButton";
import {Inertia} from '@inertiajs/inertia'
import {reactive} from 'vue'
import {Link} from '@inertiajs/inertia-vue3'
import Label from "../../Jetstream/Label.vue";


export default {
    name: "PickedUp",
    components: {
        Label,
        DropDown,
        LoadingButton,
        Link,
        Layout,
        TaskReconcileLayout,
        Toggle
    },
    props: {
        tasks: Array,
        servicemen: Array,
        user: String
    },
    data() {
        return {
            showTab: 'Created',
            myTab: 'Pickup',
            servicemen: this.servicemen,
            status: [
                {
                    id: 1,
                    name: 'created',
                },
                {
                    id: 2,
                    name: 'approved',
                },
                {
                    id: 3,
                    name: 'denied',
                },
                {
                    id: 4,
                    name: 'diy',
                },
                {
                    id: 5,
                    name: 'completed',
                }
            ],
            type: [
                {
                    id: 1,
                    name: 'todo',
                },
                {
                    id: 2,
                    name: 'repair',
                },
                {
                    id: 2,
                    name: 'part',
                }
            ],
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

        approveItem(item) {
            item.approved = !item.approved
            Inertia.post('/task/approveItem', item)
        },
        changeStatus(item) {
            Inertia.post('/task/changeStatus', item)
        },
        changeType(item) {
            Inertia.post('/task/changeType', item)
        },
        changeDescription(item) {
            Inertia.post('/task/changeDescription', item)
        },
        assignServiceman(servicemanId) {
            Inertia.post('/task/assignServiceman', servicemanId)
        },
        requestApproval(item) {
            Inertia.post('/task/requestApprovalFromReconcile', item)
        },
        updatePrice(item) {
            Inertia.post('/task/updatePrice', item)
        },


        deleteItem(task) {
            task.deleted = true;
        },
        cancelDeletion(task) {
            task.deleted = false;
        },
        doTheDeletion(taskId) {
            Inertia.post('/task/deleteItemFromReconcile', {"task_id": taskId})
        },
        checkStatus(statuses, specificStatus) {
            for (const [key, value] of Object.entries(statuses)) {
                if (key === specificStatus && value) {
                    return true;
                }
            }
            return false;
        },
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

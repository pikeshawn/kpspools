<template>
    <layout
        :user="user"
    >
        <div class="max-w-6xl mx-auto p-6 bg-white shadow-md rounded-lg">
            <!-- Header Section -->
            <h2 class="text-2xl font-bold mb-4">View Tasks</h2>


            <!-- Filter Section -->
            <div class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm
                                              ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2
                                              focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                 style="padding: 1rem; background: white; margin-bottom: 1rem">

                <div class="flex justify-between">
                    <div>
                        <h3 class="text-lg font-bold mb-2">Selected Content</h3>
                        <p class="text-sm text-gray-600">Name: {{ selected.name }}</p>
                        <p class="text-sm text-gray-600">Address: {{ selected.address }}</p>
                        <p class="text-sm text-gray-600">Status: {{ selectedStatus }}</p>
                        <p v-if="user.is_admin" class="text-sm text-gray-600">Serviceman: {{ selectedServiceman }}</p>
                        <p class="text-sm text-gray-600">Type: {{ selectedType }}</p>
                        <!--                <p class="text-sm text-gray-600">Date: {{ selectedDateRange }}</p>-->
                    </div>

                    <div>
                        <h3 class="text-lg font-bold mb-2">Status Totals</h3>
                        <p class="text-sm text-gray-600">Created: {{ created }}</p>
                        <p class="text-sm text-gray-600">Created And Sent: {{ createdAndSent }}</p>
                        <p class="text-sm text-gray-600">Approved: {{ approved }}</p>
                        <p class="text-sm text-gray-600">PickedUp: {{ pickedUp }}</p>
                        <p class="text-sm text-gray-600">Completed: {{ completed }}</p>
                        <p class="text-sm text-gray-600">Invoiced: {{ invoiced }}</p>
                        <p class="text-sm text-gray-600">Paid: {{ paid }}</p>
                        <p class="text-sm text-gray-600">Removed: {{ removed }}</p>
                        <p class="text-sm text-gray-600">Denied: {{ denied }}</p>
                        <p class="text-sm text-gray-600">DIY: {{ diy }}</p>
                        <!--                <p class="text-sm text-gray-600">Date: {{ selectedDateRange }}</p>-->
                    </div>

                    <div>
                        <h3 class="text-lg font-bold mb-2">Type Totals</h3>
                        <p class="text-sm text-gray-600">Repair: {{ repair }}</p>
                        <p class="text-sm text-gray-600">Part: {{ part }}</p>
                        <p class="text-sm text-gray-600">Todo: {{ todo }}</p>
                        <!--                <p class="text-sm text-gray-600">Date: {{ selectedDateRange }}</p>-->
                    </div>

                </div>

                <button @click="getTasksFromSelectedCriteria()"
                        class="inline-block bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-700"
                        style="margin-top: 1rem"
                >
                    <label class="block text-sm font-medium leading-6 text-white">Submit</label>
                </button>
            </div>


            <div class="flex justify-between">
                <div>
                    <!-- To-Do Checkbox -->
                    <div class="mb-4 flex items-center">
                        <input v-model="allCustomers" type="checkbox"
                               class="h-5 w-5 text-blue-600 border-gray-300 rounded">
                        <label class="ml-2 text-sm text-gray-700">All Customers</label>
                    </div>

                    <!-- To-Do Checkbox -->
                    <div class="mb-4 flex items-center">
                        <input v-model="allAddresses" type="checkbox"
                               class="h-5 w-5 text-blue-600 border-gray-300 rounded">
                        <label class="ml-2 text-sm text-gray-700">All Addresses</label>
                    </div>
                    <!-- To-Do Checkbox -->
                    <div class="mb-4 flex items-center">
                        <input v-model="allStatuses" type="checkbox"
                               class="h-5 w-5 text-blue-600 border-gray-300 rounded">
                        <label class="ml-2 text-sm text-gray-700">All Statuses</label>
                    </div>

                    <!-- To-Do Checkbox -->
                    <div class="mb-4 flex items-center">
                        <input v-model="allTypes" type="checkbox" class="h-5 w-5 text-blue-600 border-gray-300 rounded">
                        <label class="ml-2 text-sm text-gray-700">All Types</label>
                    </div>

                    <!-- To-Do Checkbox -->
                    <div v-if="user.is_admin" class="mb-4 flex items-center">
                        <input v-model="allServicemen" type="checkbox"
                               class="h-5 w-5 text-blue-600 border-gray-300 rounded">
                        <label class="ml-2 text-sm text-gray-700">All Servicemen</label>
                    </div>
                </div>
            </div>


            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">

                <!-- Status Dropdown -->
                <select v-model="selectedStatus"
                        class="p-2 border rounded-lg focus:ring focus:ring-blue-300">
                    <option value="all">Select Status</option>
                    <option v-for="status in statuses" :key="status" :value="status">{{ status }}</option>
                </select>

                <div v-if="selectedStatus === 'created'">
                    <!-- To-Do Checkbox -->
                    <div class="mb-4 flex items-center">
                        <input v-model="sentForApproval" type="checkbox"
                               class="h-5 w-5 text-blue-600 border-gray-300 rounded">
                        <label class="ml-2 text-sm text-gray-700">Sent For Approval</label>
                    </div>

                    <!-- To-Do Checkbox -->
                    <div class="mb-4 flex items-center">
                        <input v-model="sentForApprovalBoth" type="checkbox"
                               class="h-5 w-5 text-blue-600 border-gray-300 rounded">
                        <label class="ml-2 text-sm text-gray-700">Approval Status Doesn't Matter</label>
                    </div>
                </div>

                <!-- Status Dropdown -->
                <select
                    v-if="user.is_admin"
                    v-model="selectedServiceman"
                    class="p-2 border rounded-lg focus:ring focus:ring-blue-300">
                    <option value="all">Select Serviceman</option>
                    <option v-for="guy in servicemen" :key="guy.id" :value="guy.id">{{ guy.name }}</option>
                </select>

                <!-- Type Dropdown -->
                <select v-model="selectedType"
                        class="p-2 border rounded-lg focus:ring focus:ring-blue-300">
                    <option value="todo">Todo</option>
                    <option value="repair">Repair</option>
                    <option value="part">Part</option>
                </select>

                <!--                &lt;!&ndash; Date Range &ndash;&gt;-->
                <!--                <select v-model="selectedDateRange" @change="fetchTasks"-->
                <!--                        class="p-2 border rounded-lg focus:ring focus:ring-blue-300">-->
                <!--                    <option value="last_month">Last Month</option>-->
                <!--                    <option value="this_year">This Year</option>-->
                <!--                    <option value="specific_range">Specific Range</option>-->
                <!--                </select>-->
                <!-- Description Input -->
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Description</label>
                    <input
                        @input="getCustomer($event.target.value)" v-model="targetCustomer" type="text" name="text"
                        placeholder="Enter customer last name..."
                        class="mt-1 w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none">
                </div>

            </div>

            <div v-for="customer in targetCustomers" :key="customer.id"
                 style="margin-bottom: 1rem"
            >
                <button
                    @click="setCustomer(customer)"
                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm
                                              ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2
                                              focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                    style="padding: 1rem; background: white"
                >
                    <p>{{ customer.name }}</p>
                    <p>{{ customer.address }}</p>
                </button>


                <!--                    <li @click="setTask(task)">-->
                <!--                        {{ task.description }}-->
                <!--                    </li>-->
            </div>

            <!-- Task Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div v-for="task in tasks" :key="task.id"
                     class="p-4 border rounded-lg shadow-lg bg-gray-50 hover:shadow-xl transition">
                    <h3 class="text-lg font-bold mb-2">{{ task.first_name }} {{ task.last_name }}</h3>

                    <!-- Labels -->
                    <p class="text-sm text-gray-600">Phone: {{ task.phone_number }}</p>
                    <p class="text-sm text-gray-600">Address: {{ task.address }}</p>
                    <p class="text-sm text-gray-600">Status: <span class="font-bold">{{ task.status }}</span></p>
                    <p class="text-sm text-gray-600">Name: <span class="font-bold">{{ task.name }}</span></p>

                    <inertia-link
                        :href="route('images', task.address_id)"
                    >
                        <div
                            class="inline-block bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                            <p class="text-sm font-medium text-gray-900">Images</p>
                        </div>
                    </inertia-link>

                    <!-- Description & Product Number -->
                    <div class="mt-3">
                        <label class="block font-medium">Description</label>
                        <input type="text" v-model="task.description" @blur="changeDescription(task)"
                               class="w-full p-2 border rounded-lg focus:ring focus:ring-blue-300">
                    </div>

                    <div class="mt-3">
                        <label class="block font-medium">Product Number</label>
                        <input type="text" v-model="task.product_number" @blur="changeProductNumber(task)"
                               class="w-full p-2 border rounded-lg focus:ring focus:ring-blue-300">
                    </div>

                    <!--                    <div class="mt-3">-->
                    <!--                        <label class="block font-medium">Cost</label>-->
                    <!--                        <input type="text" v-model="cost" @blur="changeProductNumber(task)"-->
                    <!--                               class="w-full p-2 border rounded-lg focus:ring focus:ring-blue-300">-->
                    <!--                        <button @click="updatePrice()"-->
                    <!--                                class="inline-block bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-700"-->
                    <!--                                style="margin-top: 1rem"-->
                    <!--                        >-->
                    <!--                            <label class="block text-sm font-medium leading-6 text-white">Update Price</label>-->
                    <!--                        </button>-->
                    <!--                    </div>-->

                    <!-- Assigned -->
                    <div class="mt-3">
                        <label class="block font-medium">Assigned</label>
                        <select v-model="task.assigned" @change="assignServiceman(task)"
                                class="w-full p-2 border rounded-lg focus:ring focus:ring-blue-300">
                            <option v-for="user in servicemen" :key="user.id" :value="user.id">{{ user.name }}</option>
                        </select>
                    </div>

                    <!-- Status -->
                    <div class="mt-3">
                        <label class="block font-medium">Status</label>
                        <select v-model="task.status" @change="changeStatus(task)"
                                class="w-full p-2 border rounded-lg focus:ring focus:ring-blue-300">
                            <option v-for="status in statuses" :key="status" :value="status">{{ status }}</option>
                        </select>
                    </div>


                    <div v-if="task.status === 'created'" class="flex justify-between">
                        <div class="mt-4 m-1">
                            <label class="block font-medium text-green-900" v-if="task.sent === 1">Sent</label>
                            <button @click="requestApproval(task)"
                                    class="inline-block bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                                Send For Approval
                            </button>
                        </div>
                        <!--                        <div class="mt-4 m-1">-->
                        <!--                            <button @click="sendTripMessage(task)"-->
                        <!--                                    class="inline-block bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-700">-->
                        <!--                                Send Trip Message-->
                        <!--                            </button>-->
                        <!--                        </div>-->
                    </div>

                    <!-- Task Type -->
                    <div class="mt-3">
                        <label class="block font-medium">Type</label>
                        <div class="flex space-x-4">
                            <label class="flex items-center space-x-2">
                                <input type="radio" v-model="task.type" value="todo" @change="changeType(task)">
                                <span>Todo</span>
                            </label>
                            <label class="flex items-center space-x-2">
                                <input type="radio" v-model="task.type" value="repair" @change="changeType(task)">
                                <span>Repair</span>
                            </label>
                            <label class="flex items-center space-x-2">
                                <input type="radio" v-model="task.type" value="part" @change="changeType(task)">
                                <span>Part</span>
                            </label>
                        </div>
                    </div>

                    <!-- Price & Sub Rate -->
                    <div v-if="user.is_admin" class="mt-3">
                        <label class="block font-medium">Price</label>
                        <input type="text" v-model="task.price" @blur="changePrice(task)"
                               class="w-full p-2 border rounded-lg focus:ring focus:ring-blue-300">
                        <div v-if="true">
                            <label class="mt-2 flex items-center space-x-2" v-if="task.jobType">Job Type Exists</label>
                            <label class="mt-2 flex items-center space-x-2" v-else>No Job Type</label>
                            <div class="flex justify-around">
                                <button @click="addJobType(task, true)"
                                        class="inline-block bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-700"
                                        style="margin-top: 1rem"
                                >
                                    <label class="block text-sm font-medium leading-6 text-white">Add</label>
                                </button>
                                <button @click="addJobType(task, false)"
                                        class="inline-block bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-700"
                                        style="margin-top: 1rem"
                                >
                                    <label class="block text-sm font-medium leading-6 text-white">Delete</label>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3">
                        <div v-if="user.is_admin">
                            <label class="block font-medium">Sub Rate</label>
                            <input type="text" v-model="task.sub_rate" @blur="changeSubRate(task)"
                                   class="w-full p-2 border rounded-lg focus:ring focus:ring-blue-300">
                        </div>
                        <div class="flex justify-between" v-else>
                            <label class="block font-medium">Sub Rate</label>
                            <label class="block font-medium">$ {{ task.sub_rate }}</label>
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <!-- Customer Page Link -->
                        <Link :href="route('customers.show', task.address_id)"
                              class="inline-block bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-700"
                              style="margin-top: 1rem"
                              method="get" as="button"
                        >
                            <label class="block text-sm font-medium leading-6 text-white">Customer Page</label>
                        </Link>
                        <button @click="deleteItem(task)"
                                class="inline-block bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-700"
                                style="margin-top: 1rem"
                        >
                            <label class="block text-sm font-medium leading-6 text-white">Delete</label>
                        </button>
                    </div>

                    <div v-if="task.deleted" class="rounded-md bg-red-50 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <XCircleIcon class="h-5 w-5 text-red-400" aria-hidden="true"/>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">You are about to delete this item.
                                    Do you
                                    wish to continue</h3>
                                <div class="flex justify-around mt-2 text-sm text-red-700">
                                    <button @click="doTheDeletion(task)"
                                            class="mt-4 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm
                                text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700
                                focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                        Yes
                                    </button>
                                    <button @click="cancelDeletion(task)"
                                            class="mt-4 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm
                                text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700
                                focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        No
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>


            </div>

        </div>
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
    name: "View",
    components: {
        DropDown,
        LoadingButton,
        Link,
        Layout,
        Toggle
    },
    props: {
        user: Array,
        servicemen: Array,
        created: String,
        createdAndSent: Number,
        approved: Number,
        pickedUp: Number,
        completed: Number,
        invoiced: Number,
        paid: Number,
        removed: Number,
        denied: Number,
        diy: Number,
        repair: Number,
        part: Number,
        todo: Number
    },
    data() {
        return {
            selectedServiceman: "",
            statuses: ["created", "approved", "pickedUp", "completed", "invoiced", "paid", "remove", "denied", "diy"],
            users: [],
            tasks: [],
            selectedCustomer: "",
            cost: null,
            sentForApproval: false,
            sentForApprovalBoth: true,
            addToJobType: false,
            allAddresses: true,
            allCustomers: false,
            allStatuses: false,
            allTypes: false,
            allServicemen: false,
            selectedStatus: 'all',
            selectedType: "repair",
            selectedDateRange: "this_year",
            selected: [],
            csrfToken: null,
            targetCustomer: '',
            targetCustomers: []
        }
    },
    methods: {

        changeDescription(item) {
            Inertia.post('/task/changeDescription', item)
        },
        changeProductNumber(item) {
            Inertia.post('/task/changeProductNumber', item)
        },
        assignServiceman(servicemanId) {
            Inertia.post('/task/assignServiceman', servicemanId)
        },
        changeStatus(item) {
            Inertia.post('/task/changeStatus', item)
        },
        changeSubRate(item) {
            Inertia.post('/task/changeSubRate', item)
        },
        changeType(item) {
            Inertia.post('/task/changeType', item)
        },
        changePrice(item) {
            Inertia.post('/task/updatePrice', item)
        },
        requestApproval(item) {
            Inertia.post('/task/requestApproval', item)
        },
        sendTripMessage(item) {
            Inertia.post('/task/sendTripMessage', item)
        },
        addJobType(item, addDelete) {
            Inertia.post('/task/addJobType', {
                "item": item,
                "addJobType": addDelete,
            })
        },
        deleteItem(item) {
            item.deleted = true;
        },
        cancelDeletion(item) {
            item.deleted = false;
        },
        doTheDeletion(item) {
            Inertia.post('/task/deleteItem', item)
            this.getTasksFromSelectedCriteria()
        },

        updatePrice() {
            let jobRate = 100;
        },

        getCustomer(name) {

            console.debug(name)
            // debugger;

            this.customer = name

            // Inertia.post('/customers/getNames', {'name': name})
            fetch('/customers/getCustomer', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json', // Specify the content type
                    'X-CSRF-TOKEN': this.csrfToken
                },
                // Include CSRF token
                body: JSON.stringify({'customer': this.targetCustomer})
            }).then(function (response) {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json(); // Parse the JSON in the response
            }.bind(this))
                .then(function (data) {
                    this.targetCustomers = data; // Access the Vue instance here
                }.bind(this))
                .catch(function (error) {
                    console.error('Error:', error); // Handle errors
                }.bind(this));

        },

        setCustomer(customer) {
            this.targetCustomer = customer.name;
            this.selected = customer;
            this.targetCustomers = [];
        },

        getTasksFromSelectedCriteria() {

            let input = {
                'customer': 'all',
                'address': 'all',
                'address_id': null,
                'customer_id': null,
                'status': 'all',
                'serviceman': 'all',
                'type': 'all',
                'sent': 'both',
                'active': 1,
                'sold': 1
            }

            if (!this.sentForApprovalBoth) {
                input.sent = this.sentForApproval
            } else {
                input.sent = 'both'
            }

            if (this.selected.name) {
                input.customer = this.selected.name;
                input.address = this.selected.address;
            }

            if (this.selected.address) {
                input.address_id = this.selected.addressId;
                input.address = null
            }

            if (this.allAddresses) {
                input.address = 'all';
            }

            if (this.selected.customerId) {
                input.customer_id = this.selected.customerId;
            }

            if (this.selectedStatus) {
                input.status = this.selectedStatus;
            }

            if (this.selectedServiceman) {
                input.serviceman = this.selectedServiceman;
            }

            if (this.selectedType) {
                input.type = this.selectedType;
            }

            if (this.allStatuses) {
                input.status = 'all'
            }

            if (this.allCustomers) {
                input.customer = 'all'
            }

            if (this.allAddresses) {
                input.address = 'all'
            }

            if (this.allTypes) {
                input.type = 'all'
            }

            if (this.allServicemen) {
                input.serviceman = 'all'
            }

            // Inertia.post('/customers/getNames', {'name': name})
            fetch('/tasks/getTasksFromSelectedCriteria', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json', // Specify the content type
                    'X-CSRF-TOKEN': this.csrfToken
                },
                // Include CSRF token
                body: JSON.stringify(input)
            }).then(function (response) {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json(); // Parse the JSON in the response
            }.bind(this))
                .then(function (data) {
                    this.tasks = data; // Access the Vue instance here
                }.bind(this))
                .catch(function (error) {
                    console.error('Error:', error); // Handle errors
                }.bind(this));

        }
    },
    mounted() {
        this.csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    },
}
</script>

<style scoped>

</style>

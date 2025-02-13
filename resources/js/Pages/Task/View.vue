<template>
    <layout
        :user="user"
    >
        <div class="max-w-6xl mx-auto p-6 bg-white shadow-md rounded-lg">
            <!-- Header Section -->
            <h2 class="text-2xl font-bold mb-4">Task/View</h2>

            <!-- Filter Section -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <!-- Customer Dropdown -->
                <select v-model="selectedCustomer" @change="fetchTasks"
                        class="p-2 border rounded-lg focus:ring focus:ring-blue-300">
                    <option value="">All Customers</option>
                    <option v-for="customer in customers" :key="customer.id" :value="customer.id">
                        {{ customer.name }}
                    </option>
                </select>

                <!-- Status Dropdown -->
                <select v-model="selectedStatus" @change="fetchTasks"
                        class="p-2 border rounded-lg focus:ring focus:ring-blue-300">
                    <option value="">All Statuses</option>
                    <option v-for="status in statuses" :key="status" :value="status">{{ status }}</option>
                </select>

                <!-- Type Dropdown -->
                <select v-model="selectedType" @change="fetchTasks"
                        class="p-2 border rounded-lg focus:ring focus:ring-blue-300">
                    <option value="todo">Todo</option>
                    <option value="repair">Repair</option>
                </select>

                <!-- Date Range -->
                <select v-model="selectedDateRange" @change="fetchTasks"
                        class="p-2 border rounded-lg focus:ring focus:ring-blue-300">
                    <option value="last_month">Last Month</option>
                    <option value="this_year">This Year</option>
                    <option value="specific_range">Specific Range</option>
                </select>
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

                    <div
                        v-if="task.status === 'created' && task.sent === 0"
                        class="mt-4">
                        <button @click="requestApproval(task)" class="inline-block bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                            Send For Approval
                        </button>
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
                    <div class="mt-3">
                        <label class="block font-medium">Price</label>
                        <input type="text" v-model="task.price" @blur="changePrice(task)"
                               class="w-full p-2 border rounded-lg focus:ring focus:ring-blue-300">
                    </div>

                    <div class="mt-3">
                        <label class="block font-medium">Sub Rate</label>
                        <input type="text" v-model="task.sub_rate" @blur="changeSubRate(task)"
                               class="w-full p-2 border rounded-lg focus:ring focus:ring-blue-300">
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
        tasks: Array,
        servicemen: Array,
        user: String
    },
    data() {
        return {
            customers: [],

            statuses: ["created", "approved", "pickedUp", "completed", "invoiced", "paid", "remove", "denied", "diy"],
            users: [],
            selectedCustomer: "",
            selectedStatus: "",
            selectedType: "repair",
            selectedDateRange: "this_year",
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
        deleteItem(item) {
            item.deleted = true;
        },
        cancelDeletion(item) {
            item.deleted = false;
        },
        doTheDeletion(item) {
            Inertia.post('/task/deleteItem', item)
        },


        async fetchTasks() {
            // Fetch tasks based on filters
            try {
                const response = await fetch("/api/tasks");
                this.tasks = await response.json();
            } catch (error) {
                console.error("Error fetching tasks:", error);
            }
        },
        async fetchCustomers() {
            // Fetch customers
            try {
                const response = await fetch("/api/customers");
                this.customers = await response.json();
            } catch (error) {
                console.error("Error fetching customers:", error);
            }
        },
        async fetchUsers() {
            // Fetch assigned users (service technicians)
            try {
                const response = await fetch("/api/users");
                this.users = await response.json();
            } catch (error) {
                console.error("Error fetching users:", error);
            }
        },
        async updateTask(id, field, value) {
            // Update task in the backend
            try {
                await fetch(`/api/tasks/${id}`, {
                    method: "PUT",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({ [field]: value }),
                });
            } catch (error) {
                console.error("Error updating task:", error);
            }
        },
    },
    mounted() {
        // this.fetchTasks();
        // this.fetchCustomers();
        // this.fetchUsers();
    },
}
</script>

<style scoped>

</style>

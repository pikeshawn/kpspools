<template>
    <layout
        :user="user"
        :addressId="addressId"
    >
        <div class="min-h-screen flex items-center justify-center bg-gray-100">
            <div class="bg-white shadow-lg rounded-lg p-6 max-w-lg w-full">

                <div class="flex justify-between">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-4">Create Task</h2>
                    <div>
                        <h3 class="text font-semibold text-gray-800 mb-1">{{ customer.first_name }}
                            {{ customer.last_name }}</h3>
                        -------------
                        <h3 class="text font-semibold text-gray-800 mb-1">{{ assignedServiceman }}</h3>
                    </div>
                </div>

                <!-- Description Input -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Description</label>
                    <input
                        @input="getTasks($event.target.value)" v-model="form.description" type="text" name="text"
                        placeholder="Enter task details..."
                        class="mt-1 w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none">
                </div>
                <div v-if="!form.toDo" class="flex justify-between items-center mb-3">
                    <label class="block text-sm font-medium text-gray-700">Repair Rate: ${{ getSubRate() }}</label>
                    <div class="flex justify-end">
                        <button @click="form.description = ''; form.taskItems = '';"
                                style="margin-bottom: 1rem"
                                class="px-4 py-2 bg-red-100 text-gray-800 rounded-lg hover:bg-gray-400 transition">
                            Clear Description
                        </button>
                    </div>
                </div>

                <div class="mb-2">
                    <div v-for="task in form.taskItems" :key="task.id">
                        <button
                            @click="setTask(task)"
                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm
                                              ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2
                                              focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                            style="padding: 1rem; background: white"
                        >
                            <p>{{ task.description }}</p>
                            <p>{{ task.product_number }}</p>
                            <p v-if="task.type === 'scpItem'">SCP Price {{ task.price }}</p>
                        </button>


                        <!--                    <li @click="setTask(task)">-->
                        <!--                        {{ task.description }}-->
                        <!--                    </li>-->
                    </div>
                </div>

                <div class="flex justify-between">
                    <!-- Quantity Input -->
                    <div class="mb-4 w-1/2">
                        <label class="block text-sm font-medium text-gray-700">Quantity</label>
                        <input
                            @input="updatePrice()"
                            v-model="form.quantity"
                            type="number"
                            min="1"
                            placeholder="Enter quantity..."
                            class="mt-1 w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none">
                    </div>

                    <!-- Assign to Service Guy Dropdown -->
                    <div class="mb-4 w-1/2">
                        <label class="block text-sm font-medium text-gray-700">Time To Complete</label>
                        <select v-model="form.completionTime"
                                @change="updatePrice()"
                                class="mt-1 w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none">
                            <option value="" disabled>Estimated Time...</option>
                            <option v-for="time in timeIntervals" :key="time.id" :value="time.id">
                                {{ time.name }}
                            </option>
                        </select>
                    </div>
                </div>

                <!--                &lt;!&ndash; Update Serviceman Repair Rate Checkbox &ndash;&gt;-->
                <!--                <div class="mb-4 flex items-center">-->
                <!--                    <input v-model="form.verbalApproval" type="checkbox"-->
                <!--                           class="h-5 w-5 text-blue-600 border-gray-300 rounded">-->
                <!--                    <label class="ml-2 text-sm text-gray-700">Verbal Approval</label>-->
                <!--                </div>-->

                <!-- To-Do Checkbox -->
                <div class="mb-4 flex items-center">
                    <input v-model="form.toDo" type="checkbox" class="h-5 w-5 text-blue-600 border-gray-300 rounded">
                    <label class="ml-2 text-sm text-gray-700">To-Do</label>
                </div>

                <!--                &lt;!&ndash; To-Do Checkbox &ndash;&gt;-->
                <!--                <div class="mb-4 flex items-center">-->
                <!--                    <input v-model="form.separateTrip"-->
                <!--                           @change="add()"-->
                <!--                           type="checkbox" class="h-5 w-5 text-blue-600 border-gray-300 rounded">-->
                <!--                    <label class="ml-2 text-sm text-gray-700">Separate Trip</label>-->
                <!--                </div>-->

                <!-- Assign to Service Guy Dropdown -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Assign to Service Guy</label>
                    <select v-model="form.serviceman"
                            class="mt-1 w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none">
                        <option value="" disabled>Select a person</option>
                        <option v-for="guy in users" :key="guy.id" :value="guy.id">
                            {{ guy.name }}
                        </option>
                    </select>
                </div>

                <!-- Assign to Another Company Dropdown -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Assign to Another Company</label>
                    <select v-model="form.subcontractor"
                            class="mt-1 w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none">
                        <option value="" disabled>Select a company</option>
                        <option v-for="company in subcontractors" :key="company.id" :value="company.id">
                            {{ company.company_name }}
                        </option>
                    </select>
                </div>

                <div class="flex justify-end mt-6">
                    <button @click="cancelCompany()"
                            style="margin-bottom: 1rem"
                            class="px-4 py-2 bg-red-100 text-gray-800 rounded-lg hover:bg-gray-400 transition">
                        Cancel Company
                    </button>
                </div>

                <div v-if="user.is_admin">

                    <!-- Repair Rate Input -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Repair Rate</label>
                        <input
                            v-model="form.repairRate"
                            type="number"
                            step="0.01"
                            min="0"
                            placeholder="Enter serviceman repair rate..."
                            class="mt-1 w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none">
                    </div>

                    <!-- Percentage Buttons -->
                    <div class="flex space-x-1" style="margin-bottom: 1rem;">
                        <button
                            v-for="percent in percentages"
                            :key="percent"
                            @click="applyPercentage(percent)"
                            class="px-2 py-1 text-xs font-medium text-white bg-blue-500 rounded hover:bg-blue-600 transition"
                        >
                            +{{ percent }}%
                        </button>
                    </div>

                    <!-- Update Serviceman Repair Rate Checkbox -->
                    <div class="mb-4 flex items-center">
                        <input v-model="form.updateServicemanRepairRate" type="checkbox"
                               class="h-5 w-5 text-blue-600 border-gray-300 rounded">
                        <label class="ml-2 text-sm text-gray-700">Update Repairman Rate</label>
                    </div>

                    <!-- Cost -->
                    <div v-if="form.selectedTask" class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 flex justify-between">
                            <div>Cost - Per Unit - Pre Tax</div>
                            <div>$ {{ form.selectedTask.price }}</div>
                        </label>
                    </div>

                    <!-- Repair Rate Input -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Job Rate</label>
                        <input
                            v-model="form.jobRate"
                            type="number"
                            step="0.01"
                            min="0"
                            placeholder="Enter job repair rate..."
                            class="mt-1 w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none">
                    </div>

                    <!-- Percentage Buttons -->
                    <div class="flex space-x-1" style="margin-bottom: 1rem;">
                        <button
                            v-for="percent in percentages"
                            :key="percent"
                            @click="applyPercentageToJobRate(percent)"
                            class="px-2 py-1 text-xs font-medium text-white bg-blue-500 rounded hover:bg-blue-600 transition"
                        >
                            +{{ percent }}%
                        </button>
                    </div>

                    <!-- Update Standard Rate Checkbox -->
                    <div class="mb-4 flex items-center">
                        <input v-model="form.updateStandardRate" type="checkbox"
                               class="h-5 w-5 text-blue-600 border-gray-300 rounded">
                        <label class="ml-2 text-sm text-gray-700">Update Standard Rate</label>
                    </div>
                </div>

                <!-- Buttons Section -->
                <div class="flex justify-end mt-6">
                    <button @click="submitTask"
                            class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition">
                        Submit Task
                    </button>
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
    components: {
        DropDown,
        LoadingButton,
        Link,
        Layout,
        Toggle
    },
    props: {
        user: Array,
        users: Array,
        subcontractors: Array,
        addressId: String,
        customerId: String,
        customer: String,
        customerName: String,
        assignedServiceman: String,
        tasks: Array

    },

    data() {
        return {
            form: {
                completionTime: 0,
                address_id: '',
                separateTrip: false,
                customer_id: '',
                description: '',
                jobRate: 0,
                name: null,
                price: 0,
                product_number: '',
                quantity: 1,
                initialRepairRate: 0,
                repairRate: 0,
                selectedTask: null,
                selectedTaskDescription: null,
                serviceman: null,
                source: '',
                status: 'created',
                subcontractor: null,
                taskItems: null,
                todoAssignee: null,
                updateStandardRate: false,
                verbalApproval: false,
                toDo: false,
                updateServicemanRepairRate: false
            },

            tripCharge: 80,
            timeIntervals: [
                {
                    "id": 0,
                    "multiplier": .25,
                    "name": "less than 15 min",
                },
                {
                    "id": 1,
                    "multiplier": .5,
                    "name": "15 to 30 min",
                },
                {
                    "id": 2,
                    "multiplier": 1,
                    "name": "30 to 60 min",
                },
                {
                    "id": 3,
                    "multiplier": 2,
                    "name": "1 - 2 hours",
                },
                {
                    "id": 4,
                    "multiplier": 3,
                    "name": "2 - 3 hrs",
                },
                {
                    "id": 5,
                    "multiplier": 4,
                    "name": "3 - 4 hrs",
                },
                {
                    "id": 6,
                    "multiplier": 5,
                    "name": "4 - 5 hrs",
                },
                {
                    "id": 7,
                    "multiplier": 6,
                    "name": "5 to 6 hrs",
                }
            ],

            markup: [],

            csrfToken: null,
            percentages: [10, 20, 30, 40, 50], // Available percentage options

            description: "",
            quantity: 1,
            todoChecked: false,
            selectedServiceGuy: "",
            selectedCompany: "",
            repairRate: 0
        };
    },

    mounted() {
        this.form.serviceman = this.user.id;
        this.csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    },

    methods: {
        applyPercentage(percent) {
            if (this.form.repairRate || this.form.selectedTask.price) {
                this.form.repairRate = parseFloat(this.form.repairRate);
                this.form.selectedTask.price = parseFloat(this.form.selectedTask.price);
                this.form.repairRate = this.form.selectedTask.price + (this.form.selectedTask.price * .08);
                this.form.repairRate = this.form.repairRate * (percent / 100);
                this.form.repairRate = this.form.repairRate.toFixed(2);
            }
        },

        applyPercentageToJobRate(percent) {
            if (this.form.jobRate || this.form.selectedTask.price) {
                this.form.jobRate = parseFloat(this.form.jobRate);
                this.form.selectedTask.price = parseFloat(this.form.selectedTask.price);
                this.form.jobRate = this.form.selectedTask.price + (this.form.selectedTask.price * .08);
                this.form.jobRate = this.form.jobRate * (1 + percent / 100) + this.form.repairRate;
                this.form.jobRate = parseFloat(this.form.jobRate);
                this.form.jobRate = this.form.jobRate.toFixed(2);
            }
        },

        submitTask() {

            if (!this.form.repairRate) {
                this.form.repairRate = 0;
            }


            this.form.address_id = this.addressId
            this.form.customer_id = this.customerId
            this.form.assigned = this.user.id
            Inertia.post('/task/store', this.form)
        },

        getTasks(name) {

            console.debug(name)
            // debugger;

            this.form.name = name

            // debugger

            if (!this.form.repairRate) {
                if (this.form.initialRepairRate > 0) {
                    this.form.repairRate = this.form.initialRepairRate
                }
            }

            // Inertia.post('/customers/getNames', {'name': name})
            fetch('/task/getTaskItems', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json', // Specify the content type
                    'X-CSRF-TOKEN': this.csrfToken
                },
                // Include CSRF token
                body: JSON.stringify({'name': this.form.name, 'serviceman': this.form.serviceman})
            }).then(function (response) {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json(); // Parse the JSON in the response
            }.bind(this))
                .then(function (data) {
                    this.form.taskItems = data; // Access the Vue instance here
                }.bind(this))
                .catch(function (error) {
                    console.error('Error:', error); // Handle errors
                }.bind(this));

        },

        updatePrice() {
            if (this.form.source === 'scpItem') {
                this.form.repairRate = this.techRate()
                this.form.jobRate = this.setJobRate(this.form.selectedTask.price, this.form.quantity)
            } else if (this.form.source === 'taskItem') {
                this.form.repairRate = this.techRate()
                this.form.jobRate = this.form.selectedTask.jobRate * this.form.quantity
            }
        },

        setTask(task) {
            if (task.type === 'scpItem') {
                this.form.selectedTask = task
                this.form.description = task.description
                this.form.product_number = task.product_number
                this.form.price = task.price
                this.form.repairRate = this.techRate()
                this.form.jobRate = this.setJobRate(task.price, this.form.quantity)
                this.form.source = task.type
                this.form.taskItems = null
                this.form.jobRate = this.form.jobRate.toFixed(2);
            } else {
                this.form.selectedTask = task
                this.form.description = task.description
                this.form.product_number = task.product_number
                this.form.price = null
                this.form.repairRate = task.repairmanRate
                this.form.jobRate = task.price
                this.form.source = task.type
                this.form.taskItems = null
            }

        },

        getSubRate() {
            if (this.form.selectedTask && this.form.selectedTask.type) {
                if (this.form.selectedTask.type === 'scpItem') {
                    return this.techRate();
                } else {
                    return this.form.repairRate
                }
            }
        },

        setJobRate(price, quantity) {
            let cost = price * quantity









            if (this.form.separateTrip) {
                return this.markUp(cost) + this.laborRate() + this.tripCharge;
            } else {
                return this.markUp(cost) + this.techRate()
            }
        },

        techRate() {
            return this.timeIntervals[this.form.completionTime].multiplier * 65;
        },

        laborRate() {
            return this.techRate() * 2;
        },

        markUp(price) {
            if (price <= 50) {
                return price * 2.08
            } else if (price <= 100) {
                return price * 1.83
            } else if (price <= 500) {
                return price * 1.73
            } else if (price <= 1000) {
                return price * 1.53
            } else if (price > 1000) {
                return price * 1.43
            }
        },

        cancelCompany() {
            this.form.subcontractor = null;
        }
    }
}
</script>

<style scoped>

</style>

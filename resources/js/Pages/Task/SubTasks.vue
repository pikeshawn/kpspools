<template>
    <layout
        :user="user"
    >

        <div v-if="user.is_admin">

            <h1 class="text-2xl font-bold mb-4">SubTasks</h1>

            <!-- Filters -->
            <div class="mb-4 flex space-x-4">
                <select v-model="selectedSub" class="border p-2 rounded">
                    <option value="all">All Subs</option>
                    <option v-for="sub in subs" :key="sub.id" :value="sub.id">
                        {{ sub.name }}
                    </option>
                </select>

                <select v-model="selectedStatus" class="border p-2 rounded">
                    <option value="all">All Statuses</option>
                    <option v-for="status in statuses" :key="status" :value="status">
                        {{ status }}
                    </option>
                </select>

                <input type="date" v-model="startDate" class="border p-2 rounded">
                <input type="date" v-model="endDate" class="border p-2 rounded">

                <button @click="fetchTheTasks" class="bg-blue-500 text-white px-4 py-2 rounded">
                    Apply Filters
                </button>
            </div>

            <!-- Summary Section -->
            <div class="mb-4 p-4 bg-gray-100 rounded">
<!--                            <p><strong>Total Owed for Selected Tasks:</strong> ${{ totalOwed.toFixed(2) }}</p>-->
<!--                            <p><strong>Total Owed for All Unpaid Tasks:</strong> ${{ totalUnpaid.toFixed(2) }}</p>-->
            </div>

            <!-- Tasks List -->
            <div class="mb-6">
                <h2 class="text-xl font-semibold">{{ subTasks.name }}</h2>
                <table class="w-full border-collapse border border-gray-300 mt-2">
                    <thead>
                    <tr class="bg-gray-200">
                        <th class="border p-2">Task</th>
                        <th class="border p-2">Status</th>
                        <th class="border p-2">Type</th>
                        <th class="border p-2">Price</th>
                        <th class="border p-2">Quantity</th>
                        <th class="border p-2">Total</th>
                        <th class="border p-2">Paid</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="task in subTasks.tasks" :key="task.id">
                        <td class="border p-2">{{ task.description }}</td>
                        <td class="border p-2">{{ task.status }}</td>
                        <td class="border p-2">{{ task.type }}</td>
                        <td class="border p-2">${{ task.price.toFixed(2) }}</td>
                        <td class="border p-2">{{ task.quantity }}</td>
                        <td class="border p-2">${{ (task.price * task.quantity).toFixed(2) }}</td>
                        <td class="border p-2">{{ task.sub.beenPaid ? "Yes" : "No" }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>


            <div class="p-4">
                <h1 class="text-2xl font-bold mb-4">Tasks for {{ subTasks.name }}</h1>

                <div class="space-y-4">
                    <div v-for="task in subTasks.tasks" :key="task.id"
                         class="bg-white shadow-md rounded-lg p-4 border border-gray-200">

                        <!-- Task Header -->
                        <div class="flex justify-between items-center border-b pb-2 mb-3">
                            <h2 class="text-lg font-semibold">{{ task.description }}</h2>
                            <span class="text-sm px-2 py-1 rounded" :class="statusClasses(task.status)">
            {{ task.status }}
          </span>
                        </div>

                        <!-- Task Details -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
                            <div>
                                <p><strong>Type:</strong> {{ task.type }}</p>
                                <p><strong>Price:</strong> ${{ task.price.toFixed(2) }}</p>
                                <p><strong>Quantity:</strong> {{ task.quantity }}</p>
                            </div>
                            <div>
                                <p><strong>Created:</strong> {{ formatDate(task.created_at) }}</p>
                                <p><strong>Last Updated:</strong> {{ formatDate(task.updated_at) }}</p>
                                <p><strong>Approval Sent:</strong> {{ task.approvalSent ? "Yes" : "No" }}</p>
                            </div>
                        </div>

                        <!-- Customer Info -->
                        <div class="mt-3 p-3 bg-gray-50 rounded">
                            <p><strong>Customer:</strong>
                                <a :href="task.customer.link" class="text-blue-500 underline">{{
                                        task.customer.name
                                    }}</a>
                            </p>
                            <p><strong>Address:</strong> {{ task.customer.address }}</p>
                        </div>

                        <!-- Payment Info -->
                        <div class="mt-3 flex justify-between items-center">
                            <p>
                                <strong>Paid:</strong>
                                <span :class="task.sub.beenPaid ? 'text-green-600' : 'text-red-500'">
              {{ task.sub.beenPaid ? "Yes" : "No" }}
            </span>
                            </p>
                            <p><strong>Rate:</strong> ${{ task.sub.rate.toFixed(2) }}</p>
                        </div>

                        <!-- Status History -->
                        <div class="mt-4">
                            <h3 class="text-sm font-semibold mb-2">Status History</h3>
                            <div class="space-y-1">
                                <div v-for="status in task.statuses" :key="status.status_date"
                                     class="flex justify-between text-xs">
                                    <span class="font-medium">{{ status.status }}</span>
                                    <span class="text-gray-600">{{ formatDate(status.status_date) }}</span>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

<!--            <pre>{{ taskResponse }}</pre>-->

        </div>

    </layout>

</template>

<script>
import Layout from "../Shared/Layout";
import {ref, computed, onMounted} from "vue";
import {usePage, router} from '@inertiajs/inertia-vue3';
import {Inertia} from '@inertiajs/inertia';

export default {
    name: "SubTasks",
    components: {
        Layout
    },
    props: {
        statuses: Array,
        user: Array,
        subs: Array
    },
    data() {
        return {
            startDate: true,
            selectedStatus: true,
            endDate: true,
            isAdmin: true,
            selectedSub: null,
            csrfToken: null,
            subTasks: {
                name: null
            },
            taskResponse: null,
            totalUnpaid: null
        }
    },
    mounted() {
        this.csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    },
    beforeUpdate() {

    },
    updated() {
    },
    methods: {

        formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString("en-US", {
                year: "numeric",
                month: "short",
                day: "numeric",
            });
        },
        statusClasses(status) {
            const statusColors = {
                completed: "bg-green-100 text-green-800",
                created: "bg-blue-100 text-blue-800",
                approved: "bg-yellow-100 text-yellow-800",
                pickedUp: "bg-purple-100 text-purple-800",
                denied: "bg-red-100 text-red-800",
            };
            return statusColors[status] || "bg-gray-100 text-gray-800";
        },


        fetchData() {
            let subNameVal = this.getSubName()
            Inertia.get("/tasks/getTasks", {
                sub: this.selectedSub,
                subName: subNameVal,
                status: this.selectedStatus,
                startDate: this.startDate,
                endDate: this.endDate
            }, {
                preserveState: true,
                onSuccess: (response) => {
                    subTasks.value = response.props.subTasks;
                    totalUnpaid.value = response.props.totalUnPaidTasks;
                }
            });
        },


        fetchTheTasks() {
            // Perform the POST request

            // this.$inertia.get('/customers/getCustomersForDay/' + day);

            // Inertia.get('/customers/getCustomersForDay/' + day)

            // Inertia.post('/customers/getNames', {'name': name})
            // console.log(this.csrfToken); // Use this token in your component
            let subNameVal = this.getSubName()
            fetch('/tasks/getTasks', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json', // Specify the content type
                    'X-CSRF-TOKEN': this.csrfToken
                },
                // Include CSRF token
                body: JSON.stringify({
                    sub: this.selectedSub,
                    subName: subNameVal,
                    status: this.selectedStatus,
                    startDate: this.startDate,
                    endDate: this.endDate
                })
            }).then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }

                // debugger;

                // this.taskResponse = response.json();

                // this.subTasks = response.props.subTasks;
                // this.totalUnpaid = response.props.totalUnPaidTasks;

                // console.log(response.json()) // Parse the JSON in the response
                return response.json(); // Parse the JSON in the response
            })
                .then(data => {
                    // console.log('Success:', data); // Handle the success case
                    this.taskResponse = data;
                    this.subTasks = data;
                })
                .catch((error) => {
                    console.error('Error:', error); // Handle errors
                });

        },


        getSubName() {
            for (let i = 0; i < this.subs.length - 1; i++) {
                if (this.subs[i].id === this.selectedSub) {
                    return this.subs[i].name
                }
            }
        }
    },
    computed: {
        totalOwed() {
            return subTasks.value.reduce((sum, sub) => {
                return sum + sub.tasks.reduce((taskSum, task) => {
                    return !task.sub.beenPaid ? taskSum + task.sub.rate : taskSum;
                }, 0);
            }, 0);
        }
    }
}
</script>

<style scoped>
.filters {
    display: flex;
    gap: 10px;
    margin-bottom: 20px;
}

.summary {
    font-weight: bold;
    margin-bottom: 20px;
}
</style>

<template>
    <layout>
        <div class="max-w-4xl mx-auto p-6 bg-white shadow-md rounded-lg">
            <!-- Summary Section -->
            <div class="mb-6">
                <h2 class="text-xl font-semibold mb-4">Summary</h2>

                <div v-for="s in totalPayments" class="flex justify-between">
<!--                <div v-if="user.is_admin" v-for="s in totalPayments" class="flex justify-between">-->
                    <!-- Service Stops -->
                    <h2 class="text text-black font-semibold mb-4">{{ s.servicemanName }}</h2>
                    <div>
                        <button @click="toggleTable('serviceStops', s.servicemanId)" class="text-blue-600 hover:underline text-lg font-bold">
                            {{ s.totalPendingServiceStops }} Service Stops = ${{ s.totalServiceStopAmount }}
                        </button>
                    </div>

                    <!-- Repairs -->
                    <div>
                        <button @click="toggleTable('repairs', s.servicemanId)" class="text-blue-600 hover:underline text-lg font-bold">
                            {{ s.totalPendingRepairs }} Repairs: ${{ s.totalRepairAmount }}
                        </button>
                    </div>

                    <!-- Bucket (Only if totalBucketAmount > 0) -->
                    <div v-if="s.totalBucketAmount > 0">
                        <button class="text-black-600 text-lg font-bold">
                            {{ s.totalNumberOfPoolsContributingToBucket }} Bucket: ${{ s.totalBucketAmount }}
                        </button>
                    </div>
                </div>

            </div>

            <!-- Service Stops Table -->
            <div v-if="selectedTable === 'serviceStops'">
                <h3 class="text-lg font-semibold mb-2">Service Stops</h3>
                <table class="w-full border-collapse border border-gray-300">
                    <thead>
                    <tr class="bg-gray-100">
                        <th class="border p-3 cursor-pointer">
                            Customer Name
                            <div class="flex justify-around"><button @click="fetchServiceStops('name', 'asc')">🔼</button><button @click="fetchServiceStops('name', 'desc')">🔽</button></div>
                        </th>
                        <th class="border p-3 cursor-pointer" @click="sortTable('date_service')">
                            Date Serviced
                            <div class="flex justify-around"><button @click="fetchServiceStops('date', 'asc')">🔼</button><button @click="fetchServiceStops('date', 'desc')">🔽</button></div>
                        </th>
                        <th class="border p-2">Rate</th>
                        <th class="border p-2">Status</th>
                        <th class="border p-2">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="stop in serviceStops" :key="stop.id" class="text-center">
                        <td class="border p-2">{{ stop.customer_name }}</td>
                        <td class="border p-2">{{ stop.date_service }}</td>
                        <td class="border p-2">${{ stop.rate }}</td>
                        <td class="border p-2">{{ stop.status }}</td>
                        <td class="border p-2">
                            <a :href="stop.link" class="text-blue-500 hover:underline">Edit</a>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <!-- Repairs Table -->
            <div v-if="selectedTable === 'repairs'">
                <h3 class="text-lg font-semibold mb-2">Repairs</h3>
                <table class="w-full border-collapse border border-gray-300">
                    <thead>
                    <tr class="bg-gray-100">
                        <th class="border p-2">
                            Customer Name
<!--                            <div class="flex justify-around text-gray-50 text-sm mb-1"><div class="w-1/2 border-2" @click="fetchRepairs('name', 'asc')">ASC</div><div class="w-1/2 border-2" @click="fetchRepairs('name', 'desc')">DESC</div></div>-->
                            <div class="flex justify-around"><div @click="fetchRepairs('name', 'asc')">🔼</div><div @click="fetchRepairs('name', 'desc')">🔽</div></div>
                        </th>
                        <th class="border p-2">
                            Date Serviced
                            <div class="flex justify-around"><div @click="fetchRepairs('date', 'asc')">🔼</div><div @click="fetchRepairs('date', 'desc')">🔽</div></div>
                        </th>
                        <th class="border p-2">Rate</th>
                        <th class="border p-2">Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="repair in repairs" :key="repair.id" class="text-center">
                        <td class="border p-2">{{ repair.customer_name }}</td>
                        <td class="border p-2">{{ repair.date_service }}</td>
                        <td class="border p-2">${{ repair.paid_amount }}</td>
                        <td class="border p-2">{{ repair.status }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>

        </div>

    </layout>

</template>

<script>
import Layout from "../Shared/Layout.vue";
import DropDown from "../Shared/DropDown.vue";
import Toggle from "../Shared/Toggle.vue";
import LoadingButton from "../Shared/LoadingButton.vue";
import {Inertia} from '@inertiajs/inertia'
import {reactive} from 'vue'
import {Link} from '@inertiajs/inertia-vue3'
import Button from "../../Jetstream/Button.vue";

export default {
    name: 'CurrentPaycheck',
    props: {
        // user: Object,
        totalPendingServiceStops: Number,
        totalServiceStopAmount: Number,
        totalPendingRepairs: Number,
        totalRepairAmount: Number,
        totalNumberOfPoolsContributingToBucket: Number,
        totalBucketAmount: Number,
        totalPayments: Array
    },
    components: {
        Button,
        Layout
    },
    data() {
        return {
            selectedTable: null,
            serviceStops: [],
            repairs: [],
            sortColumn: 'date_service', // Default sorting column
            sortDirection: 'desc', // Default sorting direction
        };
    },
    methods: {
        async fetchServiceStops(column, direction, userId) {
            this.serviceStops = null
            try {
                const response = await fetch("/payments/serviceStops/" + column + "/" + direction + "/" + userId);
                this.serviceStops = await response.json();
            } catch (error) {
                console.error("Error fetching Service Stops:", error);
            }
        },

        async fetchRepairs(column, direction, userId) {
            this.repairs = null;
            try {
                const response = await fetch("/payments/repairs/" + column + "/" + direction + "/" + userId);
                this.repairs = await response.json();
            } catch (error) {
                console.error("Error fetching Repairs:", error);
            }
        },
        toggleTable(table, userId) {
            this.selectedTable = this.selectedTable === table ? null : table;
            if (table === "serviceStops") {
                this.fetchServiceStops('name', 'desc', userId);
            }
            if (table === "repairs") {
                this.fetchRepairs('name', 'desc', userId);
            }
        },
    },
};
</script>

<style scoped>

</style>

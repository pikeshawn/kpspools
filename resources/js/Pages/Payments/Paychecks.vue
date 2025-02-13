<template>
    <layout
        :user="user"
        :addressId="currentUrl"
    >
        <div class="max-w-4xl mx-auto p-6 bg-white shadow-md rounded-lg">
            <!-- Summary Section -->
            <div class="mb-6">
                <h2 class="text-xl font-semibold mb-4">Summary</h2>

                <!-- Service Stops -->
                <div>
                    <button @click="toggleTable('serviceStops')" class="text-blue-600 hover:underline text-lg font-bold">
                       {{ totalPendingServiceStops }} Service Stops = ${{ totalServiceStopAmount }}
                    </button>
                </div>

                <!-- Repairs -->
                <div>
                    <button @click="toggleTable('repairs')" class="text-blue-600 hover:underline text-lg font-bold">
                        {{ totalPendingRepairs }} Repairs: ${{ totalRepairAmount }}
                    </button>
                </div>

                <!-- Bucket (Only if totalBucketAmount > 0) -->
                <div v-if="totalBucketAmount > 0">
                    <button class="text-black-600 text-lg font-bold">
                        {{ totalNumberOfPoolsContributingToBucket }} Bucket: ${{ totalBucketAmount }}
                    </button>
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
                            <div class="flex justify-around text-gray-50 text-sm mb-1"><div class="w-1/2 border-2" @click="fetchServiceStops('name', 'asc')">ASC</div><div class="w-1/2 border-2" @click="fetchServiceStops('name', 'desc')">DESC</div></div>
                        </th>
                        <th class="border p-3 cursor-pointer" @click="sortTable('date_service')">
                            Date Serviced
                            <div class="flex justify-around text-gray-50 text-sm mb-1"><div class="w-1/2 border-2" @click="fetchServiceStops('date', 'asc')">ASC</div><div class="w-1/2 border-2" @click="fetchServiceStops('date', 'desc')">DESC</div></div>
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
                            <div class="flex justify-around text-gray-50 text-sm mb-1"><div class="w-1/2 border-2" @click="fetchRepairs('name', 'asc')">ASC</div><div class="w-1/2 border-2" @click="fetchRepairs('name', 'desc')">DESC</div></div>
                        </th>
                        <th class="border p-2">
                            Date Serviced
                            <div class="flex justify-around text-gray-50 text-sm mb-1"><div class="w-1/2 border-2" @click="fetchRepairs('date', 'asc')">ASC</div><div class="w-1/2 border-2" @click="fetchRepairs('date', 'desc')">DESC</div></div>
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
import Layout from "../Shared/Layout";
import DropDown from "../Shared/DropDown";
import Toggle from "../Shared/Toggle";
import LoadingButton from "../Shared/LoadingButton";
import {Inertia} from '@inertiajs/inertia'
import {reactive} from 'vue'
import {Link} from '@inertiajs/inertia-vue3'

export default {
    name: 'CurrentPaycheck',
    props: {
        totalPendingServiceStops: Number,
        totalServiceStopAmount: Number,
        totalPendingRepairs: Number,
        totalRepairAmount: Number,
        totalNumberOfPoolsContributingToBucket: Number,
        totalBucketAmount: Number,
    },
    components: {
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
        async fetchServiceStops(column, direction) {
            this.serviceStops = null
            try {
                const response = await fetch("/payments/serviceStops/" + column + "/" + direction);
                this.serviceStops = await response.json();
            } catch (error) {
                console.error("Error fetching Service Stops:", error);
            }
        },

        async fetchRepairs(column, direction) {
            try {
                const response = await fetch("/payments/repairs/" + column + "/" + direction);
                this.repairs = await response.json();
            } catch (error) {
                console.error("Error fetching Repairs:", error);
            }
        },
        toggleTable(table) {
            this.selectedTable = this.selectedTable === table ? null : table;
            if (table === "serviceStops" && this.serviceStops.length === 0) {
                this.fetchServiceStops('name', 'desc');
            }
            if (table === "repairs" && this.repairs.length === 0) {
                this.fetchRepairs();
            }
        },
    },
};
</script>

<style scoped>

</style>

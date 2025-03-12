<template>
    <layout
        :user="user"
    >
        <div class="p-6 max-w-4xl mx-auto">
            <h2 class="text-2xl font-bold mb-4">Bulk Transfer Pools</h2>

            <!-- Select Serviceman and Day -->
            <div class="mb-4">
                <label class="block font-semibold">Select Serviceman</label>
                <select v-model="selectedServiceman" class="border p-2 rounded w-full">
                    <option v-for="serviceman in servicemen" :key="serviceman.id"
                            :value="serviceman.id">
                        {{ serviceman.name }}
                    </option>
                </select>
            </div>

            <div class="mb-4">
                <label class="block font-semibold">Select Day of Route</label>
                <select v-model="selectedDay" class="border p-2 rounded w-full">
                    <option v-for="day in daysOfWeek" :key="day" :value="day">
                        {{ day }}
                    </option>
                </select>
            </div>

            <div class="mb-4">
                <label class="block font-semibold">Filter Options</label>
                <select v-model="filterOption" class="border p-2 rounded w-full">
                    <option value="all">All</option>
                    <option value="completed">Completed</option>
                    <option value="uncompleted">Uncompleted</option>
                </select>
            </div>

            <button @click="fetchPools" class="bg-blue-500 text-white px-4 py-2 rounded">Get Pools</button>

            <!-- Pools List -->
            <div v-if="pools.length" class="mt-6">
                <div class="flex items-center">
                    <input type="checkbox" v-model="selectAll" @change="toggleAll" class="mr-2">
                    <label class="font-semibold">Select All</label>
                </div>

                <div v-for="pool in pools" :key="pool.addressId"
                     class="border p-3 rounded mt-2 flex justify-between items-center">
                    <div>
                        <p class="font-semibold">{{ pool.customerName }}</p>
                        <p class="text-gray-600">{{ pool.servicemanName }}</p>
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" v-model="selectedPools" :value="pool.addressId" class="mr-2">
                        <select v-model="pool.service_day" class="border p-2 rounded">
                            <option v-for="day in daysOfWeek" :key="day" :value="day">
                                {{ day }}
                            </option>
                        </select>
                    </div>
                </div>

                <!-- Transfer Section -->
                <div class="mt-6">
                    <label class="block font-semibold">Transfer To</label>
                    <select v-model="transferToServiceman" class="border p-2 rounded w-full">
                        <option v-for="serviceman in servicemen" :key="serviceman.id"
                                :value="serviceman.id">
                            {{ serviceman.name }}
                        </option>
                    </select>
                </div>

                <button @click="transferPools" class="bg-green-500 text-white px-4 py-2 rounded mt-4">Transfer Pools
                </button>
            </div>
        </div>


    </layout>

</template>


<script>
import {Link} from '@inertiajs/inertia-vue3'
import Layout from "../Shared/Layout.vue";


export default {
    name: "BulkTransfer",
    components: {
        Link,
        Layout
    },

    props: {
        servicemen: Array
    },
    data() {
        return {
            selectedServiceman: null,
            selectedDay: null,
            filterOption: "all",
            pools: [],
            selectedPools: [],
            transferToServiceman: null,
            daysOfWeek: ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"],
            selectAll: false
        };
    },
    methods: {
        async fetchPools() {
            if (!this.selectedServiceman || !this.selectedDay) {
                alert("Please select a serviceman and a day.");
                return;
            }

            try {
                const response = await axios.get("/transfer/getList", {
                    params: {
                        servicemanId: this.selectedServiceman,
                        dayOfRoute: this.selectedDay,
                        options: this.filterOption
                    }
                });

                this.pools = response.data;
            } catch (error) {
                console.error("Error fetching pools:", error);
            }
        },
        toggleAll() {
            this.selectedPools = this.selectAll ? this.pools.map(pool => pool.addressId) : [];
        },
        async transferPools() {
            if (!this.transferToServiceman || this.selectedPools.length === 0) {
                alert("Please select a serviceman and pools to transfer.");
                return;
            }

            try {
                await axios.post("/transfer/doTransfer", {
                    servicemanId: this.transferToServiceman,
                    addressIds: this.selectedPools
                });

                alert("Transfer successful");
                this.pools = [];
                this.selectedPools = [];
            } catch (error) {
                console.error("Error transferring pools:", error);
            }
        }
    }

}
</script>

<style scoped>

</style>

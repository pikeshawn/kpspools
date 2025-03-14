<template>
    <layout
        :user="user"
        :addressId="serviceStops[0].address_id"
    >

        <!--
            - this page needs to display tiled rows of json result from the
             variable serviceStops
            - should be done in tailwind with a modern look
            - vue js component
            - the row data looks like this
            - [
          {
            "id": 12002,
            "user_id": 5,
            "customer_id": 131,
            "address_id": 131,
            "ph_level": 7.6,
            "chlorine_level": 3,
            "checked_chems": 1,
            "tabs_whole_mine": 0,
            "tabs_crushed_mine": 0,
            "tabs_whole_theirs": 0,
            "tabs_crushed_theirs": 0,
            "liquid_chlorine": 1,
            "liquid_acid": 0,
            "time_in": "2024-02-02 09:26:00",
            "time_out": "2024-02-02 09:43:00",
            "service_time": "00:17:00",
            "vacuum": 0,
            "brush": 1,
            "empty_baskets": 1,
            "created_at": "2024-02-02T09:43:37.000000Z",
            "updated_at": "2024-02-02T09:43:37.000000Z",
            "backwash": 0,
            "powder_chlorine": 0,
            "notes": null,
            "service_type": "Service Stop",
            "salt_level": "not checked",
            "super_black_algaecide": null,
            "no_phos": null
          },

          I would like to have a summary section that will do some calculations on the input
          - Average ph_level
          - Average chlorine_level
          - average salt_level
          - total tabs_whole_mine
          - total tabs_crushed_mine
          - total tabs_whole_theirs
          - total tabs_crushed_theirs
          - total liquid_chlorine
          - total liquid_acid
          - total service_time
          - total super_black_algaecide
          - total no_phos
          - count vacuum
          - count brush
          - count empty_baskets
          - count backwash
          - count powder_chlorine


        -->

        <div class="min-h-screen bg-gray-100 p-6">
            <h1 class="text-2xl font-bold text-gray-800 mb-6">Service Stops</h1>
            <h1 class="text-1xl font-bold text-gray-800 mb-2">{{ serviceStops[0].customer_name }}</h1>
            <h1 class="text-1xl font-bold text-gray-800 mb-2">{{ serviceStops[0].address  }}</h1>

            <!-- Summary Section -->
            <div class="bg-white shadow-lg rounded-lg p-6 mb-6 border border-gray-200">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Summary</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    <div class="bg-gray-100 p-3 rounded-md text-sm">
                        <span class="font-semibold">Avg pH Level:</span> {{ summary.avgPh }}
                    </div>
                    <div class="bg-gray-100 p-3 rounded-md text-sm">
                        <span class="font-semibold">Avg Chlorine Level:</span> {{ summary.avgChlorine }}
                    </div>
                    <div class="bg-gray-100 p-3 rounded-md text-sm">
                        <span class="font-semibold">Avg Salt Level:</span> {{ summary.avgSalt }}
                    </div>
                    <div class="bg-gray-100 p-3 rounded-md text-sm">
                        <span class="font-semibold">Total Service Time:</span> {{ summary.formattedServiceTime }}
                    </div>
                    <div class="bg-gray-100 p-3 rounded-md text-sm">
                        <span class="font-semibold">Total Tabs (Mine - Whole):</span> {{ summary.totalTabsMineWhole }}
                    </div>
                    <div class="bg-gray-100 p-3 rounded-md text-sm">
                        <span class="font-semibold">Total Tabs (Mine - Crushed):</span> {{ summary.totalTabsMineCrushed }}
                    </div>
                    <div class="bg-gray-100 p-3 rounded-md text-sm">
                        <span class="font-semibold">Total Tabs (Theirs - Whole):</span> {{ summary.totalTabsTheirsWhole }}
                    </div>
                    <div class="bg-gray-100 p-3 rounded-md text-sm">
                        <span class="font-semibold">Total Tabs (Theirs - Crushed):</span> {{ summary.totalTabsTheirsCrushed }}
                    </div>
                    <div class="bg-gray-100 p-3 rounded-md text-sm">
                        <span class="font-semibold">Total Liquid Chlorine:</span> {{ summary.totalLiquidChlorine }}
                    </div>
                    <div class="bg-gray-100 p-3 rounded-md text-sm">
                        <span class="font-semibold">Total Liquid Acid:</span> {{ summary.totalLiquidAcid }}
                    </div>
                    <div class="bg-gray-100 p-3 rounded-md text-sm">
                        <span class="font-semibold">Total Super Black Algaecide:</span> {{ summary.totalSuperBlackAlgaecide }}
                    </div>
                    <div class="bg-gray-100 p-3 rounded-md text-sm">
                        <span class="font-semibold">Total No Phos:</span> {{ summary.totalNoPhos }}
                    </div>
                    <div class="bg-gray-100 p-3 rounded-md text-sm">
                        <span class="font-semibold">Vacuum Count:</span> {{ summary.countVacuum }}
                    </div>
                    <div class="bg-gray-100 p-3 rounded-md text-sm">
                        <span class="font-semibold">Brush Count:</span> {{ summary.countBrush }}
                    </div>
                    <div class="bg-gray-100 p-3 rounded-md text-sm">
                        <span class="font-semibold">Empty Baskets Count:</span> {{ summary.countEmptyBaskets }}
                    </div>
                    <div class="bg-gray-100 p-3 rounded-md text-sm">
                        <span class="font-semibold">Backwash Count:</span> {{ summary.countBackwash }}
                    </div>
                    <div class="bg-gray-100 p-3 rounded-md text-sm">
                        <span class="font-semibold">Powder Chlorine Count:</span> {{ summary.countPowderChlorine }}
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                <div
                    v-for="stop in serviceStops"
                    :key="stop.id"
                    class="bg-white p-4 shadow-lg rounded-lg border border-gray-200"
                >
                    <h2 class="text-lg font-semibold text-blue-600">
                        Service Stop #{{ stop.id }}
                    </h2>
                    <p class="text-gray-600 text-sm">Service Man: {{ stop.serviceman_name }}</p>
                    <p class="text-gray-600 text-sm">Customer ID: {{ stop.customer_id }}</p>
                    <p class="text-gray-600 text-sm">Address ID: {{ stop.address_id }}</p>
                    <p class="text-gray-600 text-sm">Service Type: {{ stop.service_type }}</p>

                    <div class="mt-3 text-sm">
                        <p class="text-gray-700">
                            <span class="font-semibold">Time In:</span> {{ stop.time_in }}
                        </p>
                        <p class="text-gray-700">
                            <span class="font-semibold">Time Out:</span> {{ stop.time_out }}
                        </p>
                        <p class="text-gray-700">
                            <span class="font-semibold">Service Time:</span> {{ stop.service_time }}
                        </p>
                    </div>

                    <div class="mt-3">
                        <p class="text-gray-700">
                            <span class="font-semibold">pH Level:</span> {{ stop.ph_level }}
                        </p>
                        <p class="text-gray-700">
                            <span class="font-semibold">Chlorine Level:</span> {{ stop.chlorine_level }}
                        </p>
                        <p class="text-gray-700">
                            <span class="font-semibold">Salt Level:</span> {{ stop.salt_level }}
                        </p>
                    </div>

                    <div class="mt-3">
                        <div class="px-3 py-1 text-xs font-medium rounded-full m-1"
                             :class="stop.vacuum ? 'bg-green-200 text-green-800' : 'bg-red-200 text-red-800'">
                            Vacuum {{ stop.vacuum ? '✔' : '✘' }}
                        </div>
                        <div class="px-3 py-1 text-xs font-medium rounded-full m-1"
                             :class="stop.brush ? 'bg-green-200 text-green-800' : 'bg-red-200 text-red-800'">
                            Brush {{ stop.brush ? '✔' : '✘' }}
                        </div>
                        <div class="px-3 py-1 text-xs font-medium rounded-full m-1"
                             :class="stop.empty_baskets ? 'bg-green-200 text-green-800' : 'bg-red-200 text-red-800'">
                            Baskets {{ stop.empty_baskets ? '✔' : '✘' }}
                        </div>
                    </div>

                    <p v-if="stop.notes" class="mt-3 text-sm text-gray-500">
                        <span class="font-semibold">Notes:</span> {{ stop.notes }}
                    </p>
                </div>
            </div>
        </div>

        <pre>{{ serviceStops }}</pre>

    </layout>

</template>

<script>
import {Link} from '@inertiajs/inertia-vue3'
import Layout from "../Shared/Layout.vue";

export default {
    name: "Customer",
    components: {
        Layout,
        Link
    },
    props: {
        serviceStops: Array,
        user: Object
    },
    data() {
        return {
            reports: [
                {name: 'Accrual', route: 'reports.accrual'}
            ],
        };
    },
    computed: {
        // Compute the summary data
        summary() {
            if (!this.serviceStops || this.serviceStops.length === 0) {
                return {};
            }

            const totalCount = this.serviceStops.length;

            // Initialize totals
            let totalPh = 0, totalChlorine = 0, totalSalt = 0;
            let totalTabsMineWhole = 0, totalTabsMineCrushed = 0;
            let totalTabsTheirsWhole = 0, totalTabsTheirsCrushed = 0;
            let totalLiquidChlorine = 0, totalLiquidAcid = 0;
            let totalSuperBlackAlgaecide = 0, totalNoPhos = 0;
            let countVacuum = 0, countBrush = 0, countEmptyBaskets = 0;
            let countBackwash = 0, countPowderChlorine = 0;
            let totalServiceTimeMinutes = 0;

            this.serviceStops.forEach(stop => {
                totalPh += stop.ph_level || 0;
                totalChlorine += stop.chlorine_level || 0;
                totalSalt += stop.salt_level !== "not checked" ? parseFloat(stop.salt_level) || 0 : 0;

                totalTabsMineWhole += stop.tabs_whole_mine || 0;
                totalTabsMineCrushed += stop.tabs_crushed_mine || 0;
                totalTabsTheirsWhole += stop.tabs_whole_theirs || 0;
                totalTabsTheirsCrushed += stop.tabs_crushed_theirs || 0;

                totalLiquidChlorine += stop.liquid_chlorine || 0;
                totalLiquidAcid += stop.liquid_acid || 0;

                totalSuperBlackAlgaecide += stop.super_black_algaecide || 0;
                totalNoPhos += stop.no_phos || 0;

                countVacuum += stop.vacuum ? 1 : 0;
                countBrush += stop.brush ? 1 : 0;
                countEmptyBaskets += stop.empty_baskets ? 1 : 0;
                countBackwash += stop.backwash ? 1 : 0;
                countPowderChlorine += stop.powder_chlorine ? 1 : 0;

                // Convert service time to minutes
                if (stop.service_time) {
                    const timeParts = stop.service_time.split(":");
                    totalServiceTimeMinutes += parseInt(timeParts[0]) * 60 + parseInt(timeParts[1]);
                }
            });

            // Compute averages
            const avgPh = totalCount > 0 ? (totalPh / totalCount).toFixed(2) : 0;
            const avgChlorine = totalCount > 0 ? (totalChlorine / totalCount).toFixed(2) : 0;
            const avgSalt = totalCount > 0 ? (totalSalt / totalCount).toFixed(2) : "N/A";

            // Convert service time to hours & minutes
            const hours = Math.floor(totalServiceTimeMinutes / 60);
            const minutes = totalServiceTimeMinutes % 60;
            const formattedServiceTime = `${hours}h ${minutes}m`;

            return {
                avgPh,
                avgChlorine,
                avgSalt,
                totalTabsMineWhole,
                totalTabsMineCrushed,
                totalTabsTheirsWhole,
                totalTabsTheirsCrushed,
                totalLiquidChlorine,
                totalLiquidAcid,
                totalSuperBlackAlgaecide,
                totalNoPhos,
                countVacuum,
                countBrush,
                countEmptyBaskets,
                countBackwash,
                countPowderChlorine,
                formattedServiceTime
            };
        }
    },
    methods: {},
}
</script>

<style scoped>

</style>

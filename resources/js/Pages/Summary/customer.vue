<template>
    <layout
        title="Summary"
        :user="user"
    >
<!--        <h1 class="text-lg font-medium leading-6 text-gray-900 mb-4 capitalize font-bold text-4xl">{{ totalTabPrice }}</h1>-->

        <h1 class="text-lg font-medium leading-6 text-gray-900 mb-4 capitalize font-bold text-4xl">{{ customer_name }}</h1>

        <h4 class="text-sm font-medium leading-6 text-gray-900 mb-4 capitalize font-bold text-2xl">Total Time</h4>
        <h4 v-if="totalAverageServiceTime" class="text-sm font-medium leading-6 text-gray-900 mb-4 capitalize font-bold">All Time: {{ totalStopTime }} minutes</h4>
        <h4 v-if="totalAverageServiceTime" class="text-sm font-medium leading-6 text-gray-900 mb-4 capitalize font-bold">Service Stops: {{ totalServiceTime }} minutes</h4>
        <h4 v-if="totalAverageServiceTime" class="text-sm font-medium leading-6 text-gray-900 mb-4 capitalize font-bold">Repair: {{ totalRepairTime }} minutes</h4>
        <h4 v-if="totalAverageServiceTime" class="text-sm font-medium leading-6 text-gray-900 mb-4 capitalize font-bold">Clear Green Pool: {{ totalGreenPoolTime }} minutes</h4>

        <h4 v-if="totalTabs"  class="text-sm font-medium leading-6 text-gray-900 mb-4 capitalize font-bold">Tabs: {{ totalTabs }} </h4>
        <h4 v-if="totalLiquidChlorine"  class="text-sm font-medium leading-6 text-gray-900 mb-4 capitalize font-bold">Liquid Chlorine: {{ totalLiquidChlorine }} </h4>
        <h4 v-if="totalMuriaticAcid"  class="text-sm font-medium leading-6 text-gray-900 mb-4 capitalize font-bold">Muriatic Acid: {{ totalMuriaticAcid }} </h4>


        <h4 class="text-sm font-medium leading-6 text-gray-900 mb-4 capitalize font-bold text-2xl">Total Stops</h4>
        <h4 v-if="totalAverageServiceTime" class="text-sm font-medium leading-6 text-gray-900 mb-4 capitalize font-bold">All Time: {{ totalStops }}</h4>
        <h4 v-if="totalAverageServiceTime" class="text-sm font-medium leading-6 text-gray-900 mb-4 capitalize font-bold">Service Stops: {{ totalServiceStops }}</h4>
        <h4 v-if="totalAverageServiceTime" class="text-sm font-medium leading-6 text-gray-900 mb-4 capitalize font-bold">Repair: {{ totalRepairStops }}</h4>
        <h4 v-if="totalAverageServiceTime" class="text-sm font-medium leading-6 text-gray-900 mb-4 capitalize font-bold">Clear Green Pool: {{ totalGreenPoolStops }}</h4>

        <h4 class="text-sm font-medium leading-6 text-gray-900 mb-4 capitalize font-bold text-2xl">Average Time Per Stop</h4>
        <h4 class="text-sm font-medium leading-6 text-gray-900 mb-4 capitalize font-bold">All Time : {{ totalAverageTime }} minutes</h4>
        <h4 v-if="totalAverageServiceTime" class="text-sm font-medium leading-6 text-gray-900 mb-4 capitalize font-bold">All Time - Service Stop : {{ totalAverageServiceTime }} minutes</h4>
        <h4 v-if="totalAverageRepairTime" class="text-sm font-medium leading-6 text-gray-900 mb-4 capitalize font-bold">All Time - Repair: {{ totalAverageRepairTime }} minutes</h4>
        <h4 v-if="totalAverageClearGreenPoolTime"  class="text-sm font-medium leading-6 text-gray-900 mb-4 capitalize font-bold">All Time - Clear Green Pool: {{ totalAverageClearGreenPoolTime }} minutes</h4>

    </layout>
</template>

<script>

import Layout from "../Shared/Layout";

export default {
    name: 'CustomerSummary',
    components: {
        Layout
    },
    data() {
        return {

        }
    },
    methods: {

    },
    props: {
        totalTabPrice: String,
        customer_name: String,
        totalStopTime: String,
        totalServiceTime: String,
        totalRepairTime: String,
        totalGreenPoolTime: String,

        totalStops: String,
        totalServiceStops: String,
        totalRepairStops: String,
        totalGreenPoolStops: String,

        totalAverageTime: String,
        totalAverageServiceTime: String,
        totalAverageRepairTime: String,
        totalAverageClearGreenPoolTime: String,
        totalTabs: String,
        totalLiquidChlorine: String,
        totalMuriaticAcid: String,
        user: Object
    }
}
</script>

<style scoped>

.completed {
    color: green;
}

.notCompleted {
    color: red;
}

</style>

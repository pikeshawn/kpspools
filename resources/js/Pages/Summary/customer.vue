<template>
    <layout
        title="Summary"
    >
        <h1 class="text-lg font-medium leading-6 text-gray-900 mb-4 capitalize font-bold text-4xl">{{ customer_name }}</h1>

        <h4 class="text-sm font-medium leading-6 text-gray-900 mb-4 capitalize font-bold text-2xl">Total Time</h4>
        <h4 v-if="totalAverageServiceTime" class="text-sm font-medium leading-6 text-gray-900 mb-4 capitalize font-bold">All Time: {{ totalStopTime }}</h4>
        <h4 v-if="totalAverageServiceTime" class="text-sm font-medium leading-6 text-gray-900 mb-4 capitalize font-bold">Service Stops: {{ totalServiceTime }}</h4>
        <h4 v-if="totalAverageServiceTime" class="text-sm font-medium leading-6 text-gray-900 mb-4 capitalize font-bold">Repair: {{ totalRepairTime }}</h4>
        <h4 v-if="totalAverageServiceTime" class="text-sm font-medium leading-6 text-gray-900 mb-4 capitalize font-bold">Clear Green Pool: {{ totalGreenPoolTime }}</h4>


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

<template>
    <layout
        title="Service Stops"
    >
        <h1 class="text-lg font-medium leading-6 text-gray-900 mb-4 capitalize font-bold text-4xl">{{ customer_name }}</h1>

        <inertia-link
                :href="route('service_stops.create', customer_id)"
                class="mb-2.5 inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            >
                Add Stop
            </inertia-link>
            <simple-table
                :headers="service_stop_headers"
                :objectArray="service_stops"
            ></simple-table>

    </layout>
</template>

<script>
import SimpleTable from "../Shared/SimpleTable";
import Layout from "../Shared/Layout";

export default {
    name: "Index",
    components: {
        SimpleTable,
        Layout
    },
    data() {
        return {
            service_stop_headers: [
                {
                    name: 'id',
                    key: 'id'
                },
                {
                    name: 'ph level',
                    key: 'ph_level'
                },
                {
                    name: 'chlorine level',
                    key: 'chlorine_level'
                },
                {
                    name: 'tabs whole mine',
                    key: 'tabs_whole_mine'
                },
                {
                    name: 'liquid chlorine',
                    key: 'liquid_chlorine'
                },
                {
                    name: 'liquid acid',
                    key: 'liquid_acid'
                },
                {
                    name: 'time in',
                    key: 'time_in'
                },
                {
                    name: 'time out',
                    key: 'time_out'
                },
                {
                    name: 'service time',
                    key: 'service_time'
                },
                {
                    name: 'backwash',
                    key: 'backwash'
                },
                {
                    name: 'notes',
                    key: 'notes'
                }
            ],
            service_stop_keys: [
                'id',
                'ph_level',
                'chlorine_level',
                'tabs_whole_mine',
                'tabs_crushed_mine',
                'tabs_whole_theirs',
                'tabs_crushed_theirs',
                'liquid_chlorine',
                'liquid_acid',
                'time_in',
                'time_out',
                'service_time',
                'vacuum',
                'brush',
                'empty_baskets',
                'backwash',
                'powder_chlorine',
                'notes',
            ]
        }
    },
    props: {
        service_stops: Array,
        customer_name: String,
        customer_id: Number,
    }
}
</script>

<style scoped>

</style>

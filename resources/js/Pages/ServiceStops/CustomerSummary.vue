<template>
    <layout
        title="Service Stops"
        :user="user"
    >
        <h1 class="text-lg font-medium leading-6 text-gray-900 mb-4 capitalize font-bold text-4xl">{{
                customer_name
            }}</h1>


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
        user: Object
    }
}
</script>

<style scoped>

</style>

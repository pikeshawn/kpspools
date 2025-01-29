<template>
    <layout
        title="Service Stops"
        :user="user"
        :addressId="address.id"
    >

        <inertia-link
            :href="route('service_stops.create', address.id)"
            class="mb-2.5 inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
        >
            Add Stop
        </inertia-link>

        <!--        <ul role="list" class="divide-y divide-gray-100">-->
        <!--            <li v-for="ss in service_stops" :key="ss.id" class="flex gap-x-6 py-5"-->
        <!--            style="flex-direction: column">-->
        <!--                <div class="flex gap-x-4" style="flex-wrap: wrap;">-->
        <!--                    <div class="min-w-0 flex-auto">-->
        <!--                        <p class="text-sm font-semibold leading-6 text-gray-900">{{ ss.service_type }}</p>-->
        <!--                        <p class="text-sm font-semibold leading-6 text-gray-900" v-if="ss.serviceman_id = 2">Shawn</p>-->
        <!--                        <p class="text-sm font-semibold leading-6 text-gray-900" v-else-if="ss.serviceman_id = 3">Jeremiah</p>-->
        <!--                        <p class="text-sm font-semibold leading-6 text-gray-900" v-else-if="ss.serviceman_id = 4">Reid</p>-->
        <!--                        <p class="text-sm font-semibold leading-6 text-gray-900" v-else-if="ss.serviceman_id = 5">Zach</p>-->
        <!--                    </div>-->
        <!--                    <div class="min-w-0 flex-auto">-->
        <!--                    </div>-->
        <!--                </div>-->
        <!--                <div class="flex gap-x-4" style="flex-wrap: wrap;">-->
        <!--                    <div class="min-w-0 flex-auto">-->
        <!--                        <p class="text-sm font-semibold leading-6 text-gray-900">Time In</p>-->
        <!--                        <p class="mt-1 text-xs leading-5 text-gray-500">{{ ss.time_in }}</p>-->
        <!--                    </div>-->
        <!--                    <div class="min-w-0 flex-auto">-->
        <!--                        <p class="text-sm font-semibold leading-6 text-gray-900">Time out</p>-->
        <!--                        <p class="mt-1 text-xs leading-5 text-gray-500">{{ ss.time_out }}</p>-->
        <!--                    </div>-->
        <!--                    <div class="min-w-0 flex-auto">-->
        <!--                        <p class="text-sm font-semibold leading-6 text-gray-900">Service Time</p>-->
        <!--                        <p class="mt-1 truncate text-xs leading-5 text-gray-500">{{ ss.service_time }}</p>-->
        <!--                    </div>-->
        <!--                    <div class="min-w-0 flex-auto">-->
        <!--                        <p class="text-sm font-semibold leading-6 text-gray-900">pH</p>-->
        <!--                        <p class="mt-1 truncate text-xs leading-5 text-gray-500">{{ ss.ph_level }}</p>-->
        <!--                    </div>-->
        <!--                    <div class="min-w-0 flex-auto">-->
        <!--                        <p class="text-sm font-semibold leading-6 text-gray-900">Chlorine</p>-->
        <!--                        <p class="mt-1 truncate text-xs leading-5 text-gray-500">{{ ss.chlorine_level }}</p>-->
        <!--                    </div>-->
        <!--                    <div class="min-w-0 flex-auto">-->
        <!--                        <p class="text-sm font-semibold leading-6 text-gray-900">Salt Level</p>-->
        <!--                        <p class="mt-1 truncate text-xs leading-5 text-gray-500">{{ ss.salt_level }}</p>-->
        <!--                    </div>-->
        <!--                    <div class="min-w-0 flex-auto">-->
        <!--                        <p class="text-sm font-semibold leading-6 text-gray-900">Tabs Mine</p>-->
        <!--                        <p class="mt-1 truncate text-xs leading-5 text-gray-500">{{ ss.tabs_whole_mine }}</p>-->
        <!--                    </div>-->
        <!--                    <div class="min-w-0 flex-auto">-->
        <!--                        <p class="text-sm font-semibold leading-6 text-gray-900">Tabs Theirs</p>-->
        <!--                        <p class="mt-1 truncate text-xs leading-5 text-gray-500">{{ ss.tabs_whole_theirs }}</p>-->
        <!--                    </div>-->
        <!--                    <div class="min-w-0 flex-auto">-->
        <!--                        <p class="text-sm font-semibold leading-6 text-gray-900">Liquid Chlorine</p>-->
        <!--                        <p class="mt-1 truncate text-xs leading-5 text-gray-500">{{ ss.liquid_chlorine }}</p>-->
        <!--                    </div>-->
        <!--                    <div class="min-w-0 flex-auto">-->
        <!--                        <p class="text-sm font-semibold leading-6 text-gray-900">Acid</p>-->
        <!--                        <p class="mt-1 truncate text-xs leading-5 text-gray-500">{{ ss.liquid_acid }}</p>-->
        <!--                    </div>-->
        <!--                    <div class="min-w-0 flex-auto">-->
        <!--                        <p class="text-sm font-semibold leading-6 text-gray-900">Trichlor</p>-->
        <!--                        <p class="mt-1 truncate text-xs leading-5 text-gray-500">{{ ss.powder_chlorine }}</p>-->
        <!--                    </div>-->
        <!--                    <div class="min-w-0 flex-auto">-->
        <!--                        <p class="text-sm font-semibold leading-6 text-gray-900">Service Type</p>-->
        <!--                        <p class="mt-1 truncate text-xs leading-5 text-gray-500">{{ ss.service_type }}</p>-->
        <!--                    </div>-->
        <!--                    <div class="min-w-0 flex-auto">-->
        <!--                        <p class="text-sm font-semibold leading-6 text-gray-900">Vacuum</p>-->
        <!--                        <p class="mt-1 truncate text-xs leading-5 text-gray-500">{{ ss.vacuum }}</p>-->
        <!--                    </div>-->
        <!--                    <div class="min-w-0 flex-auto">-->
        <!--                        <p class="text-sm font-semibold leading-6 text-gray-900">Brush</p>-->
        <!--                        <p class="mt-1 truncate text-xs leading-5 text-gray-500">{{ ss.brush }}</p>-->
        <!--                    </div>-->
        <!--                    <div class="min-w-0 flex-auto">-->
        <!--                        <p class="text-sm font-semibold leading-6 text-gray-900">Empty Baskets</p>-->
        <!--                        <p class="mt-1 truncate text-xs leading-5 text-gray-500">{{ ss.empty_baskets }}</p>-->
        <!--                    </div>-->
        <!--                    <div class="min-w-0 flex-auto">-->
        <!--                        <p class="text-sm font-semibold leading-6 text-gray-900">Backwash</p>-->
        <!--                        <p class="mt-1 truncate text-xs leading-5 text-gray-500">{{ ss.backwash }}</p>-->
        <!--                    </div>-->
        <!--                    <div class="min-w-0 flex-auto">-->
        <!--                        <p class="text-sm font-semibold leading-6 text-gray-900">Tech</p>-->
        <!--                        <p class="mt-1 truncate text-xs leading-5 text-gray-500">{{ ss.serviceman_id }}</p>-->
        <!--                    </div>-->
        <!--                </div>-->
        <!--                <div>-->
        <!--                    <div class="min-w-0 flex-auto">-->
        <!--                        <p class="text-sm font-semibold leading-6 text-gray-900">Notes</p>-->
        <!--                        <p class="mt-1 text-xs leading-5 text-gray-500">{{ ss.notes }}</p>-->
        <!--                    </div>-->
        <!--                </div>-->
        <!--            </li>-->
        <!--        </ul>-->

        <!--        <ul role="list" class="divide-y divide-gray-100">-->
        <!--            <li v-for="ss in service_stops" :key="ss.id" class="flex gap-x-6 py-5"-->
        <!--                style="flex-direction: column"-->
        <!--            >-->
        <!--                <div class="flex gap-x-4">-->
        <!--                    <div class="min-w-0 flex-auto">-->
        <!--                        <p class="text-sm font-semibold leading-6 text-gray-900">Time In</p>-->
        <!--                        <p class="mt-1 text-xs leading-5 text-gray-500">{{ ss.time_in }}</p>-->
        <!--                    </div>-->
        <!--                    <div class="min-w-0 flex-auto">-->
        <!--                        <p class="text-sm font-semibold leading-6 text-gray-900">Time out</p>-->
        <!--                        <p class="mt-1 text-xs leading-5 text-gray-500">{{ ss.time_out }}</p>-->
        <!--                    </div>-->
        <!--                    <div class="min-w-0 flex-auto">-->
        <!--                        <p class="text-sm font-semibold leading-6 text-gray-900">Service Time</p>-->
        <!--                        <p class="mt-1 truncate text-xs leading-5 text-gray-500">{{ ss.service_time }}</p>-->
        <!--                    </div>-->
        <!--                </div>-->
        <!--                <div class="flex gap-x-4">-->
        <!--                    <div class="min-w-0 flex-auto">-->
        <!--                        <p class="text-sm font-semibold leading-6 text-gray-900">pH</p>-->
        <!--                        <p class="mt-1 truncate text-xs leading-5 text-gray-500">{{ ss.ph_level }}</p>-->
        <!--                    </div>-->
        <!--                    <div class="min-w-0 flex-auto">-->
        <!--                        <p class="text-sm font-semibold leading-6 text-gray-900">Chlorine</p>-->
        <!--                        <p class="mt-1 truncate text-xs leading-5 text-gray-500">{{ ss.chlorine_level }}</p>-->
        <!--                    </div>-->
        <!--                    <div class="min-w-0 flex-auto">-->
        <!--                        <p class="text-sm font-semibold leading-6 text-gray-900">Salt Level</p>-->
        <!--                        <p class="mt-1 truncate text-xs leading-5 text-gray-500">{{ ss.salt_level }}</p>-->
        <!--                    </div>-->
        <!--                </div>-->
        <!--                <div class="flex gap-x-4">-->
        <!--                    <div class="min-w-0 flex-auto">-->
        <!--                        <p class="text-sm font-semibold leading-6 text-gray-900">Tabs Mine</p>-->
        <!--                        <p class="mt-1 truncate text-xs leading-5 text-gray-500">{{ ss.tabs_whole_mine }}</p>-->
        <!--                    </div>-->
        <!--                    <div class="min-w-0 flex-auto">-->
        <!--                        <p class="text-sm font-semibold leading-6 text-gray-900">Tabs Theirs</p>-->
        <!--                        <p class="mt-1 truncate text-xs leading-5 text-gray-500">{{ ss.tabs_whole_theirs }}</p>-->
        <!--                    </div>-->
        <!--                    <div class="min-w-0 flex-auto">-->
        <!--                        <p class="text-sm font-semibold leading-6 text-gray-900">Liquid Chlorine</p>-->
        <!--                        <p class="mt-1 truncate text-xs leading-5 text-gray-500">{{ ss.liquid_chlorine }}</p>-->
        <!--                    </div>-->
        <!--                </div>-->
        <!--                <div class="flex gap-x-4">-->
        <!--                    <div class="min-w-0 flex-auto">-->
        <!--                        <p class="text-sm font-semibold leading-6 text-gray-900">Acid</p>-->
        <!--                        <p class="mt-1 truncate text-xs leading-5 text-gray-500">{{ ss.liquid_acid }}</p>-->
        <!--                    </div>-->
        <!--                    <div class="min-w-0 flex-auto">-->
        <!--                        <p class="text-sm font-semibold leading-6 text-gray-900">Trichlor</p>-->
        <!--                        <p class="mt-1 truncate text-xs leading-5 text-gray-500">{{ ss.powder_chlorine }}</p>-->
        <!--                    </div>-->
        <!--                    <div class="min-w-0 flex-auto">-->
        <!--                        <p class="text-sm font-semibold leading-6 text-gray-900">Service Type</p>-->
        <!--                        <p class="mt-1 truncate text-xs leading-5 text-gray-500">{{ ss.service_type }}</p>-->
        <!--                    </div>-->
        <!--                </div>-->
        <!--                <div class="flex gap-x-4">-->
        <!--                    <div class="min-w-0 flex-auto">-->
        <!--                        <p class="text-sm font-semibold leading-6 text-gray-900">Vacuum</p>-->
        <!--                        <p class="mt-1 truncate text-xs leading-5 text-gray-500">{{ ss.vacuum }}</p>-->
        <!--                    </div>-->
        <!--                    <div class="min-w-0 flex-auto">-->
        <!--                        <p class="text-sm font-semibold leading-6 text-gray-900">Brush</p>-->
        <!--                        <p class="mt-1 truncate text-xs leading-5 text-gray-500">{{ ss.brush }}</p>-->
        <!--                    </div>-->
        <!--                    <div class="min-w-0 flex-auto">-->
        <!--                        <p class="text-sm font-semibold leading-6 text-gray-900">Empty Baskets</p>-->
        <!--                        <p class="mt-1 truncate text-xs leading-5 text-gray-500">{{ ss.empty_baskets }}</p>-->
        <!--                    </div>-->
        <!--                    <div class="min-w-0 flex-auto">-->
        <!--                        <p class="text-sm font-semibold leading-6 text-gray-900">Backwash</p>-->
        <!--                        <p class="mt-1 truncate text-xs leading-5 text-gray-500">{{ ss.backwash }}</p>-->
        <!--                    </div>-->
        <!--                </div>-->
        <!--                <div class="flex gap-x-4">-->
        <!--                    <div class="min-w-0 flex-auto">-->
        <!--                        <p class="text-sm font-semibold leading-6 text-gray-900">Tech</p>-->
        <!--                        <p class="mt-1 truncate text-xs leading-5 text-gray-500">{{ ss.serviceman_id }}</p>-->
        <!--                    </div>-->
        <!--                </div>-->
        <!--                <div class="flex gap-x-4">-->
        <!--                    <div class="min-w-0 flex-auto">-->
        <!--                        <p class="text-sm font-semibold leading-6 text-gray-900">Notes</p>-->
        <!--                        <p class="mt-1 text-xs leading-5 text-gray-500">{{ ss.notes }}</p>-->
        <!--                    </div>-->
        <!--                </div>-->
        <!--            </li>-->
        <!--        </ul>-->

        <!--        <div-->
        <!--            v-for="ss in service_stops"-->
        <!--            class="relative inline-flex items-center px-4 py-2 rounded-l-md bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500"-->
        <!--        >-->
        <!--            <div-->
        <!--                class="relative flex items-center space-x-3 rounded-lg bg-white px-6 py-5 focus-within:ring-2 focus-within:ring-indigo-500 focus-within:ring-offset-2 hover:border-gray-400">-->
        <!--                <div class="min-w-0 flex-1">-->
        <!--                    <p class="text-sm font-medium text-gray-900">{{ ss.id }}</p>-->
        <!--                </div>-->
        <!--            </div>-->
        <!--        </div>-->

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
                    name: 'service type',
                    key: 'service_type'
                }, {
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
                'service_type',
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
        address: Array,
        service_stops: Array,
        customer_name: String,
        customer_id: Number,
        user: Object
    }
}
</script>

<style scoped>

</style>

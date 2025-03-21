<template>
    <layout
        :user="user"
    >
        <div class="p-6 bg-gray-100 min-h-screen">
            <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-md">
                <h1 class="text-2xl font-semibold mb-4 text-gray-700">Property Summary</h1>

                <div class="grid grid-cols-2 gap-4">
                    <!-- Total Houses -->
                    <div class="p-4 bg-blue-500 text-white rounded-lg shadow-md">
                        <h2 class="text-lg font-medium">Total Houses</h2>
                        <p class="text-xl font-bold">{{ list.totalNumberOfHouses }}</p>
                    </div>

                    <!-- Houses with Pools -->
                    <div class="p-4 bg-green-500 text-white rounded-lg shadow-md">
                        <h2 class="text-lg font-medium">Houses with Pools</h2>
                        <p class="text-xl font-bold">{{ list.totalNumberOfHousesWithPools }}</p>
                    </div>
                </div>

                <!-- Section Township Range -->
                <div class="mt-4 p-4 bg-gray-200 rounded-lg">
                    <h2 class="text-lg font-medium text-gray-700">Latest STR</h2>
                    <p class="text-gray-900"><strong>ID:</strong> {{ list.str.id }}</p>
                    <p class="text-gray-900"><strong>STR:</strong> {{ list.str.section_township_range }}</p>
                </div>

                <!-- Latest House with Pool -->
                <div class="mt-4 p-4 bg-gray-200 rounded-lg">
                    <h2 class="text-lg font-medium text-gray-700">Latest House with Pool</h2>
                    <p><strong>Owner:</strong> {{ list.latestHouseWithPool.owner.id }}</p>
                    <p><strong>Ownership:</strong> {{ list.latestHouseWithPool.owner.ownership }}</p>
                    <p><strong>Mailing Address:</strong> {{ list.latestHouseWithPool.owner.mailing_address_1 }} {{ list.latestHouseWithPool.owner.mailing_address_2 }}</p>
                    <p><strong>City:</strong> {{ list.latestHouseWithPool.owner.mailing_city }}</p>
                    <p><strong>Zip Code:</strong> {{ list.latestHouseWithPool.owner.mailing_zip }}</p>
                    <p><strong>Sale Date:</strong> {{ list.latestHouseWithPool.owner.sale_date }}</p>
                </div>
            </div>
        </div>
        {{ list }}
    </layout>

</template>

<script>
import {ref} from 'vue'
import Layout from "../Shared/Layout.vue";
import {Dialog, DialogOverlay, TransitionChild, TransitionRoot} from '@headlessui/vue'
import {Link} from '@inertiajs/inertia-vue3'
import {
    CalendarIcon,
    ChartBarIcon,
    FolderIcon,
    HomeIcon,
    InboxIcon,
    // MenuIcon,
    UsersIcon,
    // XIcon
} from '@heroicons/vue/24/outline'


export default {
    name: "Index",
    components: {
        Layout,
        Link,
    },
    props: {
        list: Array,
        user: Object
    },
    list() {
        return {

        }
    }
}
</script>

<style scoped>

</style>

<template>
    <Layout>
        <Link
            class="mb-2.5 inline-flex items-center px-6 py-3 border border-transparent
            text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700
            focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            :href="route('customers')">Customers
        </Link>

        <div class="bg-gray-900 px-6 py-24 sm:py-8 lg:px-8">
            <div class="mx-auto max-w-2xl text-center">
                <h2 class="text-4xl font-bold tracking-tight text-white sm:text-6xl">{{ customer.first_name }}
                    {{ customer.last_name }}</h2>
                <p class="mt-6 text-lg leading-8 text-gray-300">
                    {{ address[0].address_line_1 }}, {{ address[0].city }} {{ address[0].state }} {{ address[0].zip }}
                </p>
                <p v-show="address[0].community_gate_code" class="mt-6 text-lg leading-8 text-gray-300">
                    {{ address[0].community_gate_code }}
                </p>
                <div v-if="user.id = 2">
                    <p class="mt-6 text-lg leading-8 text-gray-300">
                        {{ customer.phone_number }}
                    </p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <inertia-link
                class="relative inline-flex items-center px-4 py-2 rounded-l-md bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500"
                :href="route('service_stops.create', customer.id)"
            >
                <div
                    class="relative flex items-center space-x-3 rounded-lg bg-white px-6 py-5 focus-within:ring-2 focus-within:ring-indigo-500 focus-within:ring-offset-2 hover:border-gray-400">
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-medium text-gray-900">Create A Service Stop</p>
                    </div>
                </div>
            </inertia-link>
            <inertia-link
                class="relative inline-flex items-center px-4 py-2 rounded-l-md bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500"
                :href="route('service_stops', customer.id)"
            >
                <div
                    class="relative flex items-center space-x-3 rounded-lg bg-white px-6 py-5 focus-within:ring-2 focus-within:ring-indigo-500 focus-within:ring-offset-2 hover:border-gray-400">
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-medium text-gray-900">History</p>
                    </div>
                </div>
            </inertia-link>
            <inertia-link
                class="relative inline-flex items-center px-4 py-2 rounded-l-md bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500"
                :href="route('summary', customer.id)"
            >
                <div
                    class="relative flex items-center space-x-3 rounded-lg bg-white px-6 py-5 focus-within:ring-2 focus-within:ring-indigo-500 focus-within:ring-offset-2 hover:border-gray-400">
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-medium text-gray-900">Summary</p>
                    </div>
                </div>
            </inertia-link>
            <inertia-link
                class="relative inline-flex items-center px-4 py-2 rounded-l-md bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500"
                :href="route('service_stops.notes', customer.id)"
            >
                <div
                    class="relative flex items-center space-x-3 rounded-lg bg-white px-6 py-5 focus-within:ring-2 focus-within:ring-indigo-500 focus-within:ring-offset-2 hover:border-gray-400">
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-medium text-gray-900">Service Stop Notes</p>
                    </div>
                </div>
            </inertia-link>

        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2" style="margin-top: 1rem;">
            <div v-for="note in notes" :key="note.id" class="relative inline-flex items-center px-4 py-2 rounded-l-md bg-white text-sm font-medium
                text-gray-700 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-indigo-500
                focus:border-indigo-500">
                <div
                    class="relative flex items-center space-x-3 rounded-lg bg-white px-6 py-5 focus-within:ring-2 focus-within:ring-indigo-500 focus-within:ring-offset-2 hover:border-gray-400">
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-medium text-gray-900">{{ note.note }}</p>
                    </div>
                </div>
            </div>
        </div>
    </Layout>

</template>

<script>

import JetInput from '@/Jetstream/Input'
import SimpleTable from "../Shared/SimpleTable";
import Layout from "../Shared/Layout";
import {Link} from '@inertiajs/inertia-vue3'

export default {
    name: 'CustomerIndex',
    components: {
        JetInput,
        Layout,
        SimpleTable,
        Link
    },
    data() {
        return {}
    },
    props: {
        customer: String,
        notes: String,
        address: String,
        user: String
    }
}

</script>

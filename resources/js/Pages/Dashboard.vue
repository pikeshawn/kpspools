<template>
    <layout
        title="Dashboard"
    >
        <inertia-link
            class="mb-2.5 inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            :href="route('customers')">
            Customers
        </inertia-link>
        <br>
        <inertia-link
            class="mb-2.5 inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            :href="route('customers.create')">
            Customers Add
        </inertia-link>

    </layout>
</template>

<script>
    // import AppLayout from '@/Layouts/AppLayout'
    // import Welcome from '@/Jetstream/Welcome'
    import Layout from "./Shared/Layout";

    export default {
        components: {
            Layout
        },
    }
</script>

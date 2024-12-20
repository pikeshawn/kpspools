<template>
    <layout
        title="Service Stops"
        :user="user"
    >
        <div>
            <h1>Service Stops</h1>
            <ul>
                <li v-for="stop in serviceStops" :key="stop.id">
                    <inertia-link :href="`/customers/facing/serviceStop?id=${stop.id}`">
                        {{ stop.name }}
                    </inertia-link>
                </li>
            </ul>
        </div>

    </layout>


</template>

<script>

import Layout from "../../Shared/Layout";

export default {
    name: 'Customers_Service_Stops',
    components: {
        Layout
    },
    props: {
        serviceStops: Array,
        user: Object,
    },
};
</script>

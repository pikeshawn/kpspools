<template>
    <layout
        title="Service Stops"
        :user="user"
    >
        <div>
            <h1>Service Stop Details</h1>
            <p><strong>Name:</strong> {{ serviceStop.name }}</p>
            <p><strong>Description:</strong> {{ serviceStop.description }}</p>
        </div>
    </layout>
</template>

<script>
import Layout from "../../Shared/Layout.vue";

export default {
    name: 'Customers_Service_Stop',
    components: {Layout},
    props: {
        serviceStop: Object,
        user: Object,
    },
};
</script>

<template>
    <layout
        title="Customer Facing Dashboard"
        :user="user"
    >
        <h1>Layout</h1>
        <ol style="margin-left: 2rem">
            <li>link to chat with pool guy or me - they have a question on what they see they can reach out within the
                app
            </li>
        </ol>

        <br>
        <br>
        <br>
        <button @click="getServiceStops()">Service Stops</button>
        <br>
        <br>
        <br>

        <h1>Service Stop History</h1>
        <h1>Settings Page</h1>
        <h1>Service Tech Profile</h1>
        <h1>Current Status</h1>
        <h1>Current Money Owed</h1>
        <h1>List of Overdue Invoices</h1>
        <ol style="margin-left: 2rem">
            <li>Day Serviced</li>
            <li>Current Tech</li>
            <li>Billing Date</li>
            <li>Latest Chemical Readings</li>
            <li>Latest News</li>
        </ol>
        <h1>Jobs</h1>
        <ol style="margin-left: 2rem">
            <li>Reporting</li>
            <li>View</li>
            <li>Commenting</li>
            <li>Payment</li>
            <li>Status</li>
        </ol>
        <h1>Billing</h1>
        <ol style="margin-left: 2rem">
            <li>update payment method</li>
            <li>auto payment option</li>
            <li>Cancel</li>
        </ol>
        <h1>Latest News, Offers, and Notifications</h1>

    </layout>
</template>

<script setup>
import {computed, ref, defineProps} from 'vue'
import {CheckIcon, ChevronUpDownIcon} from '@heroicons/vue/20/solid'
import {
    Combobox,
    ComboboxButton,
    ComboboxInput,
    ComboboxLabel,
    ComboboxOption,
    ComboboxOptions,
} from '@headlessui/vue'
import Button from "../../../Jetstream/Button.vue";

const props = defineProps({
    user: Object
});

</script>

<script>
import JetInput from '@/Jetstream/Input.vue'
import SimpleTable from "../../Shared/SimpleTable.vue";
import Layout from "../../Shared/Layout.vue";
import {Link} from '@inertiajs/inertia-vue3';
import {Inertia} from '@inertiajs/inertia';

export default {
    name: 'CustomerFacing',
    components: {},
    data() {
        return {}
    },
    mounted() {
    },
    methods: {
        getServiceStops() {
            Inertia.get('/customer/facing/serviceStops')
        },
    },
    props: {
        user: Object
    }
}
</script>

<style scoped></style>

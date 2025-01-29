<template>
    <layout
        :user="user"
        :addressId="currentUrl"
    >
        <div class="p-4">
            <table class="min-w-full border border-gray-300 rounded-lg">
                <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 text-left font-medium text-gray-600 border-b">Billed Date</th>
                    <th class="px-4 py-2 text-left font-medium text-gray-600 border-b">Billed Amount</th>
                    <th class="px-4 py-2 text-left font-medium text-gray-600 border-b">Date Paid</th>
                    <th class="px-4 py-2 text-left font-medium text-gray-600 border-b">Paid Amount</th>
                    <th class="px-4 py-2 text-left font-medium text-gray-600 border-b">Invoice Link</th>
                </tr>
                </thead>
                <tbody>
                <tr
                    v-for="(row, index) in history"
                    :key="index"
                    class="hover:bg-gray-50 even:bg-gray-50"
                >
                    <td class="px-4 py-2 border-b">{{ row.billedDate }}</td>
                    <td class="px-4 py-2 border-b">{{ formatCurrency(row.billedAmount) }}</td>
                    <td class="px-4 py-2 border-b">{{ row.datePaid }}</td>
                    <td class="px-4 py-2 border-b">{{ formatCurrency(row.paidAmount) }}</td>
                    <td class="px-4 py-2 border-b"><a :href="row.invoiceLink">Invoice</a></td>
                </tr>
                </tbody>
            </table>
        </div>

    </layout>

</template>

<script>
import Layout from "../Shared/Layout";
import DropDown from "../Shared/DropDown";
import Toggle from "../Shared/Toggle";
import LoadingButton from "../Shared/LoadingButton";
import {Inertia} from '@inertiajs/inertia'
import {reactive} from 'vue'
import {Link} from '@inertiajs/inertia-vue3'


export default {
    name: "Route",
    components: {
        DropDown,
        LoadingButton,
        Link,
        Layout,
        Toggle
    },
    props: {
        customers: Array,
        history: Array,
        user: Object
    },
    data() {
        return {
            showRoute: false,
            selectedRoute: [],
            fullUrl: window.location.href
        }
    },
    computed: {
        currentUrl() {
            return window.location.href.replace(/\/$/, "").split("/").pop() // Gets the relative path
        }
    },
    mounted() {
    },
    beforeUpdate() {
    },
    updated() {
    },
    methods: {
        formatCurrency(amount) {
            if (amount === null || amount === undefined) return "-";
            return `$${(amount / 100).toFixed(2)}`;
        },
        getRoute(day) {
            this.showRoute = true;
            this.selectedRoute = this.customers.filter(item => item.service_day === day);
        },
        store() {
            Inertia.post('/route/store', this.selectedRoute)
        }
    }
}
</script>

<style scoped>

</style>

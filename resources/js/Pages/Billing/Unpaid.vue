<template>
    <layout
        :user="user"
    >
        <!-- Summary Section -->
        <div class="mb-4 p-4 bg-gray-100 rounded-lg border border-gray-300">
            <p class="text-lg font-medium">Summary</p>
            <p>Total Rows: {{ totalRows }}</p>
            <p>Total Amount: {{ formatCurrency(totalAmount) }}</p>
        </div>

        <div class="p-4 overflow-x-auto">
            <table class="min-w-full border border-gray-300 rounded-lg">
                <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 text-left font-medium text-gray-600 border-b">User Details</th>
                    <th class="px-4 py-2 text-left font-medium text-gray-600 border-b">Payment Info</th>
                </tr>
                </thead>
                <tbody>
                <tr
                    v-for="(row, index) in unpaid"
                    :key="index"
                    class="hover:bg-gray-50 even:bg-gray-50"
                >
                    <!-- User Details -->
                    <td class="px-4 py-4 border-b">
                        <div class="mb-2">
                            <span class="font-medium">Name:</span> {{ row.firstName }} {{ row.lastName }}
                        </div>
                        <div class="mb-2">
                            <span class="font-medium">Phone:</span> {{ formatPhoneNumber(row.phoneNumber) }}
                        </div>
                        <div class="mb-2">
                            <span class="font-medium">Email:</span> {{ row.email }}
                        </div>
                        <div class="mb-2">
                            <span class="font-medium">Account Status:</span> {{ row.accountStatus }}
                        </div>
                        <div class="mb-2">
                            <a
                                :href='"https://dashboard.stripe.com/customers/" + row.customerStripeId'
                                class="text-blue-600 hover:underline"
                                target="_blank"
                            >
                                Stripe Details
                            </a>
                        </div>
                    </td>

                    <!-- Payment Info -->
                    <td class="px-4 py-4 border-b">
                        <div class="mb-2">
                            <span class="font-medium">Bill Date:</span> {{ formatDate(row.billDate) }}
                        </div>
                        <div class="mb-2">
                            <span class="font-medium">Amount:</span> {{ formatCurrency(row.amount) }}
                        </div>
                        <div class="mb-2">
                            <span class="font-medium">Invoice:</span> {{ row.invoiceNumber }}
                        </div>
                        <div>
                            <a
                                :href="row.link"
                                class="text-blue-600 hover:underline"
                                target="_blank"
                            >
                                View Details
                            </a>
                        </div>
                    </td>
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
        unpaid: Array
    },
    data() {
        return {
            showRoute: false,
            selectedRoute: []
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
            if (!amount) return "-";
            return `$`+amount;
        },
        formatDate(date) {
            if (!date) return "-";
            const options = { year: "numeric", month: "long", day: "numeric" };
            return new Date(date).toLocaleDateString(undefined, options);
        },
        formatPhoneNumber(phone) {
            if (!phone) return "-";
            return phone.replace(/(\d{1})(\d{3})(\d{3})(\d{4})/, "+$1 ($2) $3-$4");
        },
    },
    computed: {
        totalRows() {
            // Count the number of rows
            return this.unpaid.length;
        },
        totalAmount() {
            // Add all the amounts from each row
            return this.unpaid.reduce((sum, row) => sum + Number(row.amount), 0);
        },
    }
}
</script>

<style scoped>

</style>

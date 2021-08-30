<template>
    <layout
        title="Customers"
    >
        <inertia-link
            class="mb-2.5 inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            :href="route('customers.create')">
            Add New
        </inertia-link>

        <div class="flex flex-col mb-60">
            <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                    <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                            <tr>
                                <th v-for="header in customer_headers"
                                    scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <div v-show="header.name !== 'id'">{{ header.name }}</div>
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <!--                        <tr>-->
                            <!--                            {{ valueObjectArray }}-->
                            <!--                        </tr>-->
                            <tr v-for="(row) in valueObjectArray">
                                <td class="px-6 whitespace-nowrap text-sm font-medium text-gray-900"
                                    style="margin-top: .25rem !important; margin-bottom: .25rem !important; "
                                    v-for="(value) in row">
                                    <div
                                        v-if="Number(value) / Number(value) === 1"
                                    ></div>
                                    <div v-else>{{ value }}</div>
                                </td>

<!--                                <td-->

<!--                                    class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">-->
<!--                                    <inertia-link-->
<!--                                        class="px-6 py-4 flex items-center focus:text-indigo-500"-->
<!--                                        :href="route('customers', row[0])">-->
<!--                                        View-->
<!--                                    </inertia-link>-->
<!--                                </td>-->
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    <inertia-link
                                        class="px-6 py-4 flex items-center focus:text-indigo-500"
                                        :href="route('service_stops', row[0])">
                                        Service Stop
                                    </inertia-link>
                                </td>

                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </layout>
</template>

<script>
import JetInput from '@/Jetstream/Input'
import SimpleTable from "../Shared/SimpleTable";
import Layout from "../Shared/Layout";

export default {
    name: 'CustomerIndex',
    components: {
        JetInput,
        Layout,
        SimpleTable
    },
    data() {
        return {
            liquidChlorine: null,
            customer_headers: [
                {
                    name: 'id',
                    key: 'id',
                },
                {
                    name: 'Name',
                    key: 'last_name',
                },
                {
                    name: 'Service Day',
                    key: 'service_day',
                }
            ],
            valueObjectArray: []
        }
    },
    mounted () {
        this.valueObjectArray = this.tableValues();
    },
    methods: {
        tableValues() {
            let mArray = []
            for (let i = 0; i < this.customers.length; i++) {
                let nArray = []
                for (let j = 0; j < this.customer_headers.length; j++) {
                    nArray.push(this.customers[i][this.customer_headers[j].key])
                }
                mArray.push(nArray)
            }
            return mArray;
        }
    },
    props: {
      customers: Array
    }
}
</script>

<style scoped>

</style>

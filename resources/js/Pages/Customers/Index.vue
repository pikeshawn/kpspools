<template>
    <layout
        title="Customers"
    >
        <div v-for="day in ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday']">
            <h2 class="uppercase">{{ day }}</h2>
            <!-- This example requires Tailwind CSS v2.0+ -->
            <ul role="list" class="divide-y divide-gray-200" v-for="(row) in valueObjectArray">
                <li class="py-4 flex" v-if="row[2] == day">
                    <svg
                        :class="row[5] ? 'completed' : 'notCompleted'"
                        xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <inertia-link
                        class="px-6 py-4 flex items-center focus:text-indigo-500"
                        :href="route('service_stops.create', row[0])">
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">{{ row[1] }}</p>
                            <p class="text-sm text-gray-500">{{ row[3] }}</p>
                            <p class="text-sm text-gray-500">{{ row[4] }}</p>
                            <p class="text-sm text-gray-500" v-if="user.id === 2">{{ row[6] }}</p>
                        </div>
                    </inertia-link>
                    <inertia-link
                        class="mb-2.5 inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                        :href="route('service_stops', row[0])">
                    History
                    </inertia-link>
                </li>
            </ul>
        </div>

        <br>
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
                },
                {
                    name: 'Address',
                    key: 'address',
                },
                {
                    name: 'Community Gate Code',
                    key: 'gate_code',
                },
                {
                    name: 'Completed',
                    key: 'completed',
                },
                {
                    name: 'Assigned Serviceman',
                    key: 'assigned_serviceman',
                }
            ],
            valueObjectArray: []
        }
    },
    mounted() {
        this.valueObjectArray = this.tableValues();
    },
    methods: {

        getName(){
          const firstEntry = this.valueObjectArray[1];

          return typeof firstEntry;

        },

        tableValues() {
            let mArray = []
            for (let i = 0; i < this.customers.length; i++) {
                let nArray = []
                for (let j = 0; j < this.customer_headers.length; j++) {
                    if (this.customer_headers[j].key === "last_name") {
                        let customerName = this.customers[i]['first_name'] + " " + this.customers[i][this.customer_headers[j].key]
                        nArray.push(customerName)
                    } else if (this.customer_headers[j].key === "address") {
                        let address =
                            this.customers[i]['address_line_1'] + " " +
                            this.customers[i]['city'] + " " +
                            'AZ' + " " +
                            this.customers[i]['zip'];
                        nArray.push(address)
                    } else if (this.customer_headers[j].key === "gate_code"
                        && this.customers[i]['community_gate_code']) {
                        nArray.push(this.customers[i]['community_gate_code'])
                    } else if (this.customer_headers[j].key === "completed"
                        && this.customers[i]['completed']) {
                        nArray.push(this.customers[i]['completed'])
                    } else if (this.customer_headers[j].key === "assigned_serviceman") {
                        nArray.push(this.customers[i]['assigned_serviceman'])
                    } else {
                        nArray.push(this.customers[i][this.customer_headers[j].key])
                    }


                }
                mArray.push(nArray)
            }
            return mArray;
        }
    },
    props: {
        customers: Array,
        user: Object
    }
}
</script>

<style scoped>

.completed {
    color: green;
}

.notCompleted {
    color: red;
}

</style>

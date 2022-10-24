<template>
    <layout
        title="Customers"
    >
        <span class="relative z-0 inline-flex shadow-sm rounded-md" style="margin-bottom: 1rem">
    <button type="button"
            class="relative inline-flex items-center px-4 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500"
            @click="showRoute('monday')"
    >Monday</button>
    <button type="button"
            class="-ml-px relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500"
            @click="showRoute('tuesday')"
    >Tuesday</button>
    <button type="button"
            class="-ml-px relative inline-flex items-center px-4 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500"
            @click="showRoute('wednesday')"
    >Wednesday</button>
  </span>
        <br>
        <span class="relative z-0 inline-flex shadow-sm rounded-md" style="margin-bottom: 1rem">
            <button type="button"
                    class="-ml-px relative inline-flex items-center px-4 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500"
                    @click="showRoute('thursday')"
            >Thursday</button>
            <button type="button"
                    class="-ml-px relative inline-flex items-center px-4 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500"
                    @click="showRoute('friday')"
            >Friday</button>
            <button type="button"
                    class="-ml-px relative inline-flex items-center px-4 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500"
                    @click="showRoute('all')"
            >all</button>
            <button type="button"
                    class="-ml-px relative inline-flex items-center px-4 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500"
                    @click="showRoute('none')"
            >none</button>
  </span>

        <br>


        <div v-show="monday">

            <h2
                class="uppercase"
            >monday</h2>

            <!-- This example requires Tailwind CSS v2.0+ -->
            <div v-for="(row) in valueObjectArray">
                <div v-if="row[2] == 'Monday'">
                    <ul role="list" class="divide-y divide-gray-200">
                        <li class="py-4 flex">
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


                        </li>
                    </ul>
                    <span class="relative z-0 inline-flex shadow-sm rounded-md">

                        <inertia-link
                            class="relative inline-flex items-center px-4 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500"
                            :href="route('service_stops', row[0])">
                        History
                    </inertia-link>
                    <inertia-link
                        class="relative inline-flex items-center px-4 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500"
                        :href="route('summary', row[0])">
                        Summary
                    </inertia-link>

                    <inertia-link
                        class="relative inline-flex items-center px-4 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500"
                        :href="route('service_stops.notes', row[0])">
                       Service Stop Notes
                    </inertia-link>

                                            <inertia-link
                                                class="relative inline-flex items-center px-4 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500"
                                                :href="route('general.notes', row[0])">
                       General Notes
                    </inertia-link>


                    </span>
                    <hr style="margin: 2rem 0 2rem 0">
                </div>
            </div>
        </div>


        <div v-show="tuesday">

            <h2
                class="uppercase"
            >tuesday</h2>

            <!-- This example requires Tailwind CSS v2.0+ -->
            <div v-for="(row) in valueObjectArray">
                <div v-if="row[2] == 'Tuesday'">
                    <ul role="list" class="divide-y divide-gray-200">
                        <li class="py-4 flex">
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


                        </li>
                    </ul>
                    <span class="relative z-0 inline-flex shadow-sm rounded-md">

                        <inertia-link
                            class="relative inline-flex items-center px-4 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500"
                            :href="route('service_stops', row[0])">
                        History
                    </inertia-link>
                    <inertia-link
                        class="relative inline-flex items-center px-4 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500"
                        :href="route('summary', row[0])">
                        Summary
                    </inertia-link>

                    <inertia-link
                        class="relative inline-flex items-center px-4 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500"
                        :href="route('service_stops.notes', row[0])">
                       Service Stop Notes
                    </inertia-link>

                    <inertia-link
                        class="relative inline-flex items-center px-4 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500"
                        :href="route('general.notes', row[0])">
                       General Notes
                    </inertia-link>

                    </span>
                    <hr style="margin: 2rem 0 2rem 0">
                </div>
            </div>
        </div>

        <div v-show="wednesday">

            <h2
                class="uppercase"
            >wednesday</h2>

            <!-- This example requires Tailwind CSS v2.0+ -->
            <div v-for="(row) in valueObjectArray">
                <div v-if="row[2] == 'Wednesday'">
                    <ul role="list" class="divide-y divide-gray-200">
                        <li class="py-4 flex">
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


                        </li>
                    </ul>
                    <span class="relative z-0 inline-flex shadow-sm rounded-md">

                        <inertia-link
                            class="relative inline-flex items-center px-4 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500"
                            :href="route('service_stops', row[0])">
                        History
                    </inertia-link>
                    <inertia-link
                        class="relative inline-flex items-center px-4 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500"
                        :href="route('summary', row[0])">
                        Summary
                    </inertia-link>

                    <inertia-link
                        class="relative inline-flex items-center px-4 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500"
                        :href="route('service_stops.notes', row[0])">
                       Service Stop Notes
                    </inertia-link>

                                            <inertia-link
                                                class="relative inline-flex items-center px-4 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500"
                                                :href="route('general.notes', row[0])">
                       General Notes
                    </inertia-link>

                    </span>
                    <hr style="margin: 2rem 0 2rem 0">
                </div>
            </div>
        </div>

        <div v-show="thursday">

            <h2
                class="uppercase"
            >thursday</h2>

            <!-- This example requires Tailwind CSS v2.0+ -->
            <div v-for="(row) in valueObjectArray">
                <div v-if="row[2] == 'Thursday'">
                    <ul role="list" class="divide-y divide-gray-200">
                        <li class="py-4 flex">
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


                        </li>
                    </ul>
                    <span class="relative z-0 inline-flex shadow-sm rounded-md">

                        <inertia-link
                            class="relative inline-flex items-center px-4 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500"
                            :href="route('service_stops', row[0])">
                        History
                    </inertia-link>
                    <inertia-link
                        class="relative inline-flex items-center px-4 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500"
                        :href="route('summary', row[0])">
                        Summary
                    </inertia-link>

                    <inertia-link
                        class="relative inline-flex items-center px-4 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500"
                        :href="route('service_stops.notes', row[0])">
                       Service Stop Notes
                    </inertia-link>

                                            <inertia-link
                                                class="relative inline-flex items-center px-4 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500"
                                                :href="route('general.notes', row[0])">
                       General Notes
                    </inertia-link>


                    </span>
                    <hr style="margin: 2rem 0 2rem 0">
                </div>
            </div>
        </div>


        <div v-show="friday">

            <h2
                class="uppercase"
            >friday</h2>

            <!-- This example requires Tailwind CSS v2.0+ -->
            <div v-for="(row) in valueObjectArray">
                <div v-if="row[2] == 'Friday'">
                    <ul role="list" class="divide-y divide-gray-200">
                        <li class="py-4 flex">
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


                        </li>
                    </ul>
                    <span class="relative z-0 inline-flex shadow-sm rounded-md">

                        <inertia-link
                            class="relative inline-flex items-center px-4 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500"
                            :href="route('service_stops', row[0])">
                        History
                    </inertia-link>
                    <inertia-link
                        class="relative inline-flex items-center px-4 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500"
                        :href="route('summary', row[0])">
                        Summary
                    </inertia-link>

                    <inertia-link
                        class="relative inline-flex items-center px-4 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500"
                        :href="route('service_stops.notes', row[0])">
                       Service Stop Notes
                    </inertia-link>


                        <inertia-link
                            class="relative inline-flex items-center px-4 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500"
                            :href="route('general.notes', row[0])">
                       General Notes
                    </inertia-link>

                    </span>
                    <hr style="margin: 2rem 0 2rem 0">
                </div>
            </div>
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
            monday: false,
            tuesday: false,
            wednesday: false,
            thursday: false,
            friday: false,
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
        let date = new Date()
        let d = date.toDateString();
        let theDay = d.split(" ")[0]

        this.hideDays();

        if (theDay === 'Mon') {
            this.monday = true;
        } else if (theDay === 'Tue') {
            this.tuesday = true;
        } else if (theDay === 'Wed') {
            this.wednesday = true;
        } else if (theDay === 'Thu') {
            this.thursday = true;
        } else if (theDay === 'Fri') {
            this.friday = true;
        }


    },
    methods: {

        showRoute(day) {
            if (day === 'all') {
                this.monday = true;
                this.tuesday = true;
                this.wednesday = true;
                this.thursday = true;
                this.friday = true;
            } else if (day === 'none') {
                this.hideDays();
            } else {
                this.hideDays();
                if (day === 'monday') {
                    this.monday = true
                } else if (day === 'tuesday') {
                    this.tuesday = true
                } else if (day === 'wednesday') {
                    this.wednesday = true
                } else if (day === 'thursday') {
                    this.thursday = true
                } else if (day === 'friday') {
                    this.friday = true
                }
            }
        },

        hideDays() {
            this.monday = false;
            this.tuesday = false;
            this.wednesday = false;
            this.thursday = false;
            this.friday = false;
        },

        getName() {
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

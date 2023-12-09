<template>
    <layout
        title="Customers"
        :user="user"
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
                    @click="showRoute('saturday')"
            >Saturday</button>
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


        <!--        <div class="px-4 sm:px-6 lg:px-8">-->
        <!--            <div class="sm:flex sm:items-center">-->
        <!--                <div class="sm:flex-auto">-->
        <!--                    <h1 class="text-base font-semibold leading-6 text-gray-900">Users</h1>-->
        <!--                    <p class="mt-2 text-sm text-gray-700">A list of all the users in your account including their name, title, email and role.</p>-->
        <!--                </div>-->
        <!--                <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">-->
        <!--                    <button type="button" class="block rounded-md bg-indigo-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Add user</button>-->
        <!--                </div>-->
        <!--            </div>-->
        <!--            <div class="mt-8 flow-root">-->
        <!--                <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">-->
        <!--                    <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">-->
        <!--                        <table class="min-w-full divide-y divide-gray-300">-->
        <!--                            <thead>-->
        <!--                            <tr class="divide-x divide-gray-200">-->
        <!--                                <th scope="col" class="py-3.5 pl-4 pr-4 text-left text-sm font-semibold text-gray-900 sm:pl-0">Name</th>-->
        <!--                                <th scope="col" class="px-4 py-3.5 text-left text-sm font-semibold text-gray-900">Address</th>-->
        <!--                                <th scope="col" class="px-4 py-3.5 text-left text-sm font-semibold text-gray-900">Notes</th>-->
        <!--                            </tr>-->
        <!--                            </thead>-->
        <!--                            <tbody class="divide-y divide-gray-200 bg-white">-->
        <!--                            <tr v-for="row in valueObjectArray" :key="row.address" class="divide-x divide-gray-200">-->
        <!--                                <td class="whitespace-nowrap py-4 pl-4 pr-4 text-sm font-medium text-gray-900 sm:pl-0">{{  row[1] }}</td>-->
        <!--                                <td class="whitespace-nowrap p-4 text-sm text-gray-500">{{ row[3] }}</td>-->
        <!--                                <td class="whitespace-nowrap p-4 text-sm text-gray-500">{{ row[4] }}</td>-->
        <!--                            </tr>-->
        <!--                            </tbody>-->
        <!--                        </table>-->
        <!--                    </div>-->
        <!--                </div>-->
        <!--            </div>-->
        <!--        </div>-->

        <nav class="h-full overflow-y-auto" aria-label="Directory">
            <div v-for="row in valueObjectArray" :key="row[0]" class="relative">
                <div v-show="monday">
                    <div v-if="row[2] == 'Monday'">
                        <Link :href="route('customers.show', row[0])"
                              class="sticky top-0 z-10 border-y border-b-gray-200 border-t-gray-100 bg-gray-50 px-3 py-2 text-sm font-semibold leading-6 text-gray-900"
                              :class="row[5] ? 'completed' : 'notCompleted'"
                              style="border: solid thin black; width: -webkit-fill-available;"
                              method="get" as="button">
                            <h3 style="font-size: 1.4rem">{{ row[1] }}</h3>
                            <span>{{ row[3] }}</span><br>
                            <span v-if="user.id = 2">{{ row[6] }}</span>
                        </Link>
                    </div>
                </div>

                <div v-show="tuesday">
                    <div v-if="row[2] == 'Tuesday'">
                        <Link :href="route('customers.show', row[0])"
                              class="sticky top-0 z-10 border-y border-b-gray-200 border-t-gray-100 bg-gray-50 px-3 py-2 text-sm font-semibold leading-6 text-gray-900"
                              :class="row[5] ? 'completed' : 'notCompleted'"
                              style="border: solid thin black; width: -webkit-fill-available;"
                              method="get" as="button">
                            <h3 style="font-size: 1.4rem">{{ row[1] }}</h3>
                            <span>{{ row[3] }}</span><br>
                            <span v-if="user.id = 2">{{ row[6] }}</span>

                        </Link>
                    </div>
                </div>

                <div v-show="wednesday">
                    <div v-if="row[2] == 'Wednesday'">
                        <Link :href="route('customers.show', row[0])"
                              class="sticky top-0 z-10 border-y border-b-gray-200 border-t-gray-100 bg-gray-50 px-3 py-2 text-sm font-semibold leading-6 text-gray-900"
                              :class="row[5] ? 'completed' : 'notCompleted'"
                              style="border: solid thin black; width: -webkit-fill-available;"
                              method="get" as="button">
                            <h3 style="font-size: 1.4rem">{{ row[1] }}</h3>
                            <span>{{ row[3] }}</span><br>
                            <span v-if="user.id = 2">{{ row[6] }}</span>
                        </Link>
                    </div>
                </div>

                <div v-show="thursday">
                    <div v-if="row[2] == 'Thursday'">
                        <Link :href="route('customers.show', row[0])"
                              class="sticky top-0 z-10 border-y border-b-gray-200 border-t-gray-100 bg-gray-50 px-3 py-2 text-sm font-semibold leading-6 text-gray-900"
                              :class="row[5] ? 'completed' : 'notCompleted'"
                              style="border: solid thin black; width: -webkit-fill-available;"
                              method="get" as="button">
                            <h3 style="font-size: 1.4rem">{{ row[1] }}</h3>
                            <span>{{ row[3] }}</span><br>
                            <span v-if="user.id = 2">{{ row[6] }}</span>
                        </Link>
                    </div>
                </div>

                <div v-show="friday">
                    <div v-if="row[2] == 'Friday'">
                        <Link :href="route('customers.show', row[0])"
                              class="sticky top-0 z-10 border-y border-b-gray-200 border-t-gray-100 bg-gray-50 px-3 py-2 text-sm font-semibold leading-6 text-gray-900"
                              :class="row[5] ? 'completed' : 'notCompleted'"
                              style="border: solid thin black; width: -webkit-fill-available;"
                              method="get" as="button">
                            <h3 style="font-size: 1.4rem">{{ row[1] }}</h3>
                            <span>{{ row[3] }}</span><br>
                            <span v-if="user.id = 2">{{ row[6] }}</span>
                        </Link>
                    </div>
                </div>

                <div v-show="saturday">
                    <div v-if="row[2] == 'Saturday'">
                        <Link :href="route('customers.show', row[0])"
                              class="sticky top-0 z-10 border-y border-b-gray-200 border-t-gray-100 bg-gray-50 px-3 py-2 text-sm font-semibold leading-6 text-gray-900"
                              :class="row[5] ? 'completed' : 'notCompleted'"
                              style="border: solid thin black; width: -webkit-fill-available;"
                              method="get" as="button">
                            <h3 style="font-size: 1.4rem">{{ row[1] }}</h3>
                            <span>{{ row[3] }}</span><br>
                            <span v-if="user.id = 2">{{ row[6] }}</span>
                        </Link>
                    </div>
                </div>
            </div>
        </nav>

    </layout>
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
        return {
            liquidChlorine: null,
            monday: false,
            tuesday: false,
            wednesday: false,
            thursday: false,
            friday: false,
            saturday: false,
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
                },
                {
                    name: 'Phone Number',
                    key: 'phone_number',
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
                } else if (day === 'saturday') {
                    this.saturday = true
                }
            }
        },

        hideDays() {
            this.monday = false;
            this.tuesday = false;
            this.wednesday = false;
            this.thursday = false;
            this.friday = false;
            this.saturday = false;
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
                    } else if (this.customer_headers[j].key === "phone_number") {
                        nArray.push(this.customers[i]['phone_number'])
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
    background-color: #9bcd9b;
}

.notCompleted {
    background-color: #de7777;
}

</style>

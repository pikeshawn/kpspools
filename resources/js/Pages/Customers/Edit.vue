<template>
    <layout
        :user="user"
        :address-id="addressId"
    >
        <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-md">
            <h1 class="text-2xl font-semibold mb-4">Edit Customer</h1>

            <form @submit.prevent="updateCustomer">
                <!-- User Information -->
                <div class="mb-4">
                    <h2 class="text-lg font-semibold">User Information</h2>
                    <input type="email" v-model="customerUser.email" placeholder="Email"
                           class="border border-gray-300 p-2 rounded w-full mb-2"/>
                    <input type="text" v-model="customerUser.name" placeholder="Full Name"
                           class="border border-gray-300 p-2 rounded w-full mb-2"/>
                    <input type="text" v-model="customerUser.phone_number" placeholder="Phone Number"
                           class="border border-gray-300 p-2 rounded w-full mb-2"/>
                </div>

                <!-- Customer Information -->
                <div class="mb-4">
                    <h2 class="text-lg font-semibold">Customer Information</h2>
                    <input type="text" v-model="customer.first_name" placeholder="First Name"
                           class="border border-gray-300 p-2 rounded w-full mb-2"/>
                    <input type="text" v-model="customer.middle_name" placeholder="Middle Name"
                           class="border border-gray-300 p-2 rounded w-full mb-2"/>
                    <input type="text" v-model="customer.last_name" placeholder="Last Name"
                           class="border border-gray-300 p-2 rounded w-full mb-2"/>
                    <input type="text" v-model="customer.phone_number" placeholder="Phone Number"
                           class="border border-gray-300 p-2 rounded w-full mb-2"/>
                    <input type="text" v-model="customer.terms" placeholder="begin or end"
                           class="border border-gray-300 p-2 rounded w-full mb-2"/>
                    <input type="text" v-model="customer.jemmson_id" placeholder="jemmson id"
                           class="border border-gray-300 p-2 rounded w-full mb-2"/>
                    <label>
                        <input type="checkbox" v-model="customer.active"/> Active
                    </label>
                    <label>
                        <input type="checkbox" v-model="customer.autopay"/> Autopay
                    </label>
                    <div class="mb-4">
                        <label for="date_to_run_card" class="block text-sm font-medium text-gray-700">Date to Run
                            Card</label>
                        <input
                            id="date_to_run_card"
                            type="datetime-local"
                            v-model="customer.date_to_run_card"
                            class="border border-gray-300 p-2 rounded w-full"
                        />
                    </div>

                    <div class="mb-4">
                        <label for="payment_type" class="block text-sm font-medium text-gray-700">Payment Type</label>
                        <select
                            id="payment_type"
                            v-model="customer.payment_type"
                            class="border border-gray-300 p-2 rounded w-full"
                        >
                            <option value="cash">Cash</option>
                            <option value="creditCard">Credit Card</option>
                        </select>
                    </div>

                </div>

                <!-- Address Information -->
                <div v-for="(address, index) in addresses" :key="index" class="mb-4 border p-4 rounded">
                    <div class="flex justify-between">
                        <h2 class="text-lg font-semibold">{{ address.address_line_1 }}</h2>
                        <h2 class="text-lg font-semibold">{{ address.assigned_serviceman }}</h2>
                    </div>
                    <div class="mb-4">
                        <label for="service_day" class="block text-sm font-medium text-gray-700">Service Day</label>
                        <select
                            id="service_day"
                            v-model="address.service_day"
                            class="border border-gray-300 p-2 rounded w-full"
                        >
                            <option value="Monday">Monday</option>
                            <option value="Tuesday">Tuesday</option>
                            <option value="Wednesday">Wednesday</option>
                            <option value="Thursday">Thursday</option>
                            <option value="Friday">Friday</option>
                            <option value="Saturday">Saturday</option>
                            <option value="Sunday">Sunday</option>
                        </select>
                    </div>
                    <input type="text" v-model="address.address_line_1" placeholder="Address Line 1"
                           class="border border-gray-300 p-2 rounded w-full mb-2"/>
                    <input type="text" v-model="address.city" placeholder="City"
                           class="border border-gray-300 p-2 rounded w-full mb-2"/>
                    <input type="text" v-model="address.state" placeholder="State"
                           class="border border-gray-300 p-2 rounded w-full mb-2"/>
                    <input type="text" v-model="address.zip" placeholder="Zip Code"
                           class="border border-gray-300 p-2 rounded w-full mb-2"/>
                    <input type="text" v-model="address.community_gate_code" placeholder="Community Gate Code"
                           class="border border-gray-300 p-2 rounded w-full mb-2"/>
                    <input type="text" v-model="address.plan_duration" placeholder="Community Gate Code"
                           class="border border-gray-300 p-2 rounded w-full mb-2"/>
                    <input type="text" v-model="address.plan_price" placeholder="Community Gate Code"
                           class="border border-gray-300 p-2 rounded w-full mb-2"/>
                    <label>
                        <input type="checkbox" v-model="address.chemicals_included"/> Chemicals Included
                    </label>
                    <label>
                        <input type="checkbox" v-model="address.active"/> Active
                    </label>
                    <label>
                        <input type="checkbox" v-model="address.sold"/> Sold
                    </label>
                    <div class="mb-4">
                        <label for="assigned_serviceman" class="block text-sm font-medium text-gray-700">Assigned
                            Serviceman</label>
                        <select
                            id="assigned_serviceman"
                            v-model="address.serviceman_id"
                            class="border border-gray-300 p-2 rounded w-full"
                        >
                            <option value="" disabled>Select a Serviceman</option>
                            <option v-for="serviceman in servicemen" :key="serviceman.id" :value="serviceman.id">
                                {{ serviceman.name }}
                            </option>
                        </select>
                    </div>
                </div>

                <p v-if="updatedMessage" class="mb-2 mt-2 text-green-600">{{ updatedMessage }}</p>
                <p v-if="errorMessage" class="mb-2 mt-2 text-green-600">{{ errorMessage }}</p>

                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Update</button>
            </form>
        </div>
    </layout>
</template>

<script setup>
import {ref, reactive, computed, watchEffect, onMounted} from 'vue'
import {usePage} from '@inertiajs/inertia-vue3';
import {Inertia} from '@inertiajs/inertia'
import Layout from "../Shared/Layout.vue";
import add from "./Add.vue";

const props = defineProps({
    user: Object, // user object to check if admin
    customerUser: Object, // initial customers data
    customer: Object, // list of servicemen
    addressId: String, // initial service day
    addresses: Array, // initial service day
    servicemen: Array // initial service day
});

const customerUser = ref(props.customerUser)
const user = ref(props.user)
const addressId = ref(props.addressId)
const customer = ref(props.customer)
const addresses = ref(props.addresses)
const servicemen = ref(props.servicemen)
const updatedMessage = ref(null)
const errorMessage = ref(null)

const isActive = computed({
    get() {
        return !!props.customerUser.active;
    },
    set(value) {
        props.customerUser.active = value;
    }
});

onMounted(() => {
    correctValues();
});

const correctValues = () => {
    // customerUser.value.active = customerUser.value.active !== 0;
    customer.value.active = Boolean(customer.value.active);
    customer.value.autopay = Boolean(customer.value.autopay);
    for (let i = 0; i < addresses.value.length; i++) {
        addresses.value[i].active = Boolean(addresses.value[i].active);
        addresses.value[i].sold = Boolean(addresses.value[i].sold);
        addresses.value[i].chemicals_included = Boolean(addresses.value[i].chemicals_included);
    }
};

// const updateCustomer = () => {
//     Inertia.post('/customer/updateCustomer', {
//         customerUser,
//         customer,
//         addresses: addresses.value
//     })
// }


const updateCustomer = async () => {
    try {
        const response = await axios.post(`/customer/updateCustomer`, {
            customerUser,
            customer,
            addresses: addresses.value
        });

        // debugger;

        if (response.status === 200) {
            updatedMessage.value = response.data.message;
        }
    } catch (error) {
        errorMessage.value = 'Error updating customer: ' + error;
    }
};


</script>

<script>

export default {
    name: "Edit"
}
</script>

<style scoped>

</style>

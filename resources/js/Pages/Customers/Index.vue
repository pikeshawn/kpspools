<template>
    <layout
        title="Customers"
        :user="user"
    >
        <!--    <pre>{{ selectedPerson }}</pre>-->


        <div class="p-4">
            <!-- Header Buttons for Days -->
<!--            <div class="flex space-x-2 mb-4">-->
<!--                <button-->
<!--                    v-for="day in days"-->
<!--                    :key="day"-->
<!--                    @click="fetchCustomers(day)"-->
<!--                    :class="['px-4 py-2 rounded', selectedDay === day ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-700']"-->
<!--                >-->
<!--                    {{ day }}-->
<!--                </button>-->
<!--            </div>-->

            <div class="overflow-x-auto">
                <div class="flex space-x-2 mb-4 w-max">
                    <button
                        v-for="day in days"
                        :key="day"
                        @click="fetchCustomers(day)"
                        :class="['px-4 py-2 rounded whitespace-nowrap', selectedDay === day ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-700']"
                    >
                        {{ day }}
                    </button>
                </div>
            </div>

            <!-- Customers List -->
            <div v-if="customersList.length" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div v-for="customer in customersList" :key="customer.id" class="p-4 border rounded-lg shadow-md"
                     :class="customer.completed ? 'border-green-500 bg-green-100' : 'border-red-500 bg-red-100'">

                    <h3 class="text-lg font-bold">{{ customer.first_name }} {{ customer.last_name }}</h3>
                    <p><strong>Address:</strong> {{ customer.address_line_1 }}, {{ customer.city }} {{ customer.zip }}
                    </p>
                    <p><strong>Assigned to:</strong> {{ customer.assigned_serviceman }}</p>
                    <p><strong>Phone:</strong> {{ customer.phone_number }}</p>
                    <p><strong>Gate Code:</strong> {{ customer.community_gate_code }}</p>
                    <p><strong>Order:</strong> {{ customer.order }}</p>
                    <p><strong>Completed:</strong> {{ customer.completed ? 'Yes ✅' : 'No ❌' }}</p>

                    <!-- Transfer Dropdown for Admins -->
                    <div v-if="user.is_admin === 1" class="mt-2">
                        <label class="block font-semibold">Transfer to:</label>
                        <select @change="transfer($event.target.value, customer.addressId)" class="border p-2 rounded w-full">
                            <option disabled selected>Choose Serviceman</option>
                            <option v-for="serviceman in servicemenList" :key="serviceman.id" :value="serviceman.id">
                                {{ serviceman.name }}
                            </option>
                        </select>
                    </div>

                    <!-- Go To Customer Button -->
                    <button @click="goToCustomer(customer.addressId)" class="mt-4 px-4 py-2 bg-blue-500 text-white rounded">
                        View Customer
                    </button>
                </div>
            </div>

            <!-- No Customers Found -->
            <div v-else class="text-center text-gray-500 mt-4">No customers for {{ selectedDay }}</div>
        </div>


    </layout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import Layout from "../Shared/Layout.vue";

const props = defineProps({
    user: Object, // user object to check if admin
    customers: Array, // initial customers data
    servicemen: Array, // list of servicemen
    currentDay: String // initial service day
});

const days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
const selectedDay = ref(props.currentDay);
const customersList = ref(props.customers);
const servicemenList = ref(props.servicemen);

const fetchCustomers = async (day) => {
    selectedDay.value = day;
    try {
        const response = await axios.get(`/customers/getCustomersForDay/` + day);
        if (response.data.success) {
            customersList.value = response.data.customers;
        }
    } catch (error) {
        console.error('Error transferring customer:', error);
    }
};

const transfer = (servicemanId, addressId) => {
    Inertia.post('/transfer/storeFromCustomers', {
        servicemanId: servicemanId,
        addressId: addressId
    });
};

const goToCustomer = (id) => {
    Inertia.get(`/customers/show/${id}`);
};

onMounted(() => {
    fetchCustomers(selectedDay.value);
});
</script>

<style scoped>


.completed {
    background-color: #9bcd9b;
}

.notCompleted {
    background-color: #de7777;
}

</style>

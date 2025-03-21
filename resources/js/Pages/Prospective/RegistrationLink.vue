<template>
    <layout
        title=""
        :user="user"
    >

        <div>
            <label for="last-name" class="block text-sm font-semibold leading-6 text-gray-900">Send Registration
                Link</label>
            <div class="mt-2.5">
                <input type="text" v-model="new_customer_phone_number" name="new_customer_phone_number"
                       id="new_customer_phone_number"
                       class="block w-full rounded-md border-0 px-3.5 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"/>
            </div>
            <button type="button"
                    v-if="user.is_admin"
                    class="relative inline-flex items-center px-4 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500"
                    @click="sendRegistrationLink()">
                Send Register Link
            </button>
        </div>

    </layout>
</template>


<script>
import SimpleTable from "../Shared/SimpleTable.vue";
import Layout from "../Shared/Layout.vue";
import {Inertia} from "@inertiajs/inertia";

export default {
    name: "RegistrationLink",
    components: {
        Layout
    },
    data() {
        return {
            new_customer_phone_number: null
        }
    },
    props: {
        user: Object
    },
    methods: {
        sendRegistrationLink() {
            this.$inertia.post('/registerLink', {'phoneNumber': this.new_customer_phone_number}, {
                onSuccess: () => {
                    // Handle the success response
                    // e.g., show a success message
                },
                onError: (errors) => {
                    // Handle the error response
                    // e.g., display validation errors
                }
            });
        }
    }
}
</script>

<style scoped>

</style>

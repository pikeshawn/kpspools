<template>
    <layout
        title=""
        :user="user"
    >

        <div>
            <label for="comment" class="block text-sm font-medium leading-6 text-gray-900">Text Message</label>
            <div class="mt-2">
                <textarea v-model="message" rows="4" name="comment" id="comment" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
            </div>
        </div>

        <div>
            <label for="comment" class="block text-sm font-medium leading-6 text-gray-900">Phone Number</label>
            <div class="mt-2">
                <textarea v-model="phoneNumbers" rows="4" name="comment" id="comment" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
            </div>
        </div>

        <div>
            <label for="comment" class="block text-sm font-medium leading-6 text-gray-900">Data</label>
            <div class="mt-2">
                <textarea v-model="data" rows="4" name="comment" id="comment" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
            </div>
        </div>

        <div class="flex-shrink-0">
            <button @click="sendText()" class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Send</button>
        </div>



    </layout>
</template>


<script setup>

</script>

<script>
import SimpleTable from "../Shared/SimpleTable.vue";
import Layout from "../Shared/Layout.vue";
import {Inertia} from "@inertiajs/inertia";

export default {
    name: "Index",
    components: {
        Layout
    },
    data() {
        return {
            message: null,
            phoneNumbers: null,
            data: null,
        }
    },
    props: {
        user: Object
    },
    methods: {
        sendText(person){
            Inertia.post('/notification/generic', {
                // 'message': 'hello my name is $param and I was born on the $param of july',
                'message': this.message,
                'data': this.data
            });
        }
    }
}
</script>

<style scoped>

</style>

<template>
    <layout
        title="New Note"
        :user="user"
    >

        <h1>{{ customer_name }}</h1>

        <h1>Add a New Note Here</h1>
        <div class="flex flex-col col-span-2">
            <label
                class="block text-sm font-medium text-gray-700"
                for="notes">Notes</label>
            <textarea
                class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                name="notes" id="notes" cols="30" rows="10" v-model="note">
            </textarea>
        </div>

        <div
            class="mb-3 relative inline-flex items-center
            px-4 py-2 rounded-l-md border border-gray-300 bg-white
            text-sm font-medium text-gray-700 hover:bg-gray-50
            focus:z-10 focus:outline-none focus:ring-1
            focus:ring-indigo-500 focus:border-indigo-500"
            @click="storeNote()"
        >
            save
        </div>

    </layout>
</template>

<script>
import Layout from "../Shared/Layout";
import {Inertia} from "@inertiajs/inertia";

export default {
    name: "NewNote",
    components: {
        Layout
    },
    data() {
        return {
            note: null
        }
    },
    props: {
        customer_name: String,
        customer_id: Number,
        user: Object
    },
    computed: {},
    methods: {
        storeNote() {
            let note = {
                customer_id: this.customer_id,
                note: this.note
            }
            Inertia.post('/general/store', note)
        }
    }
}
</script>

<style scoped>

</style>

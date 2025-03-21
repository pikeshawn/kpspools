<template>
    <layout
        title="General Notes"
        :user="user"
    >

        <h1>{{ customer_name }}</h1>

        <br>

        <inertia-link
            class="mb-3 relative inline-flex items-center px-4 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500"
            :href="route('general.new_note', customer_id)">
            New Note
        </inertia-link>


        <div role="list" class="divide-y divide-gray-200">
            <div v-for="note in noNullNotes" :key="note.id" class="relative bg-white py-5 px-4 hover:bg-gray-50 focus-within:ring-2 focus-within:ring-inset focus-within:ring-indigo-600">
                <div v-if="note.note">
                    <div class="flex justify-between space-x-3">
                        <time :datetime="note.updated_at" class="flex-shrink-0 whitespace-nowrap text-sm text-gray-500">{{ note.updated_at }}</time>
                        <div class="flex justify-between space-x-3">
                            <svg @click="editNote(note.id)" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                            </svg>
                            <svg @click="confirmDelete(note.id)" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </div>
                    </div>

                    <div class="mt-1">
                        <p style="white-space: pre;" class="line-clamp-2 text-sm text-gray-600">
                            {{ note.note }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

    </layout>
</template>

<script>
import Layout from "../Shared/Layout.vue";
import {Inertia} from "@inertiajs/inertia";

export default {
    name: "Notes",
    components: {
        Layout
    },
    data() {
        return {
        }
    },
    props: {
        notes: Array,
        customer_name: String,
        customer_id: Number,
        user: Object
    },
    computed: {
        noNullNotes(){
            let notes = []

            for (let i = 0; i < this.notes.length; i++) {
                if (this.notes[i].note) {
                    notes.push(this.notes[i])
                }
            }
            return notes
        }
    },
    methods: {
        confirmDelete(row) {
            Inertia.delete(`/general/` + row, {
                onBefore: () => confirm(`Are you sure that you want to delete this note?`
                ),
                onSuccess: () => {
                    Inertia.reload()
                },
            })
        },
        editNote(row) {
            Inertia.get(`/general/` + row)
        },
    }
}
</script>

<style scoped>

</style>

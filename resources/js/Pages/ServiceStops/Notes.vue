<template>
    <layout
        title="Service Stops Notes"
        :user="user"
        :addressId="currentUrl"
    >

        <h1>{{ customer_name }}</h1>

        <br>


        <div role="list" class="divide-y divide-gray-200">
            <div v-for="note in noNullNotes" :key="note.id" class="relative bg-white py-5 px-4 hover:bg-gray-50 focus-within:ring-2 focus-within:ring-inset focus-within:ring-indigo-600">
                <div v-if="note.notes">
                    <div class="flex justify-between space-x-3">
                        <div class="min-w-0 flex-1">
                            <a href="#" class="block focus:outline-none">
                                <span class="absolute inset-0" aria-hidden="true" />
                                <p class="text-sm font-medium text-gray-900 truncate font-extrabold">{{ note.service_type }}</p>
                            </a>
                        </div>
                        <time :datetime="note.updated_at" class="flex-shrink-0 whitespace-nowrap text-sm text-gray-500">{{ note.updated_at }}</time>
                    </div>
                    <div class="mt-1">
                        <p class="line-clamp-2 text-sm text-gray-600">
                            {{ note.notes }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

    </layout>
</template>

<script>
import Layout from "../Shared/Layout";

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
        user: Object
    },
    computed: {
        currentUrl() {
            return window.location.href.replace(/\/$/, "").split("/").pop() // Gets the relative path
            // return 442
        },
        noNullNotes(){
            let notes = []

            for (let i = 0; i < this.notes.length; i++) {
                if (this.notes[i].notes) {
                    notes.push(this.notes[i])
                }
            }

            return notes
        }
    }
}
</script>

<style scoped>

</style>

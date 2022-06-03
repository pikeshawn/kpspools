<template>
    <layout
        title="Service Stops Notes"
    >

        <h1>{{ customer_name }}</h1>

        <br>


        <ul role="list" class="divide-y divide-gray-200">
            <li v-for="note in notes" :key="note.id" class="relative bg-white py-5 px-4 hover:bg-gray-50 focus-within:ring-2 focus-within:ring-inset focus-within:ring-indigo-600">
                <div class="flex justify-between space-x-3">
                    <div class="min-w-0 flex-1">
                        <a href="#" class="block focus:outline-none">
                            <span class="absolute inset-0" aria-hidden="true" />
                            <p class="text-sm font-medium text-gray-900 truncate">{{ note.service_type }}</p>
<!--                            <p class="text-sm text-gray-500 truncate">{{ note.notes }}</p>-->
                        </a>
                    </div>
                    <time :datetime="note.updated_at" class="flex-shrink-0 whitespace-nowrap text-sm text-gray-500">{{ note.updated_at }}</time>
                </div>
                <div class="mt-1">
                    <p class="line-clamp-2 text-sm text-gray-600">
                        {{ note.notes }}
                    </p>
                </div>
            </li>
        </ul>

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
        customer_name: String
    }
}
</script>

<style scoped>

</style>

<!-- This example requires Tailwind CSS v2.0+ -->
<template>
    <div>
        <inertia-link type="button"
                      class="relative inline-flex items-center px-4 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500"
                      :href="route('reconcile.created')">
            Created Tasks
        </inertia-link>
        <inertia-link type="button"
                      class="relative inline-flex items-center px-4 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500"
                      :href="route('reconcile.pickedUp')">
            Picked Up
            <!--                            <span class="absolute top-0 right-0 transform translate-x-1/2 -translate-y-1/2 bg-red-500 text-xs font-semibold mr-2 px-2.5 py-0.5 rounded-full" id="notificationCount">3</span>-->
        </inertia-link>
        <inertia-link type="button"
                      class="relative inline-flex items-center px-4 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500"
                      :href="route('repairsAndPartsList')">
            Repairs And Parts List
        </inertia-link>
        <inertia-link type="button"
                      class="relative inline-flex items-center px-4 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500"
                      :href="route('route')">
            Route
        </inertia-link>

        <inertia-link type="button"
                      class="relative inline-flex items-center px-4 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500"
                      :href="route('chat')">
            Chat
        </inertia-link>

        <inertia-link type="button"
                      class="relative inline-flex items-center px-4 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500"
                      :href="route('admin.links')">
            Admin Links
        </inertia-link>
    </div>

<!--    <div class="flex flex-wrap content-center">-->
<!--        <div-->
<!--            class="py-2 pr-2 w-1/3">-->
<!--            <label for="serviceman" class="block text-sm font-medium text-gray-700">Serviceman</label>-->
<!--            <select id="serviceman" name="serviceman"-->
<!--                    v-model='filtered.servicemen'-->
<!--                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">-->
<!--                <option-->
<!--                    v-for="option in servicemen" :value="option.name">-->
<!--                    {{ option.name }}-->
<!--                </option>-->
<!--            </select>-->
<!--        </div>-->
<!--        <div-->
<!--            class="py-2 pl-2 w-1/3">-->
<!--            <label for="status" class="block text-sm font-medium text-gray-700">Type</label>-->
<!--            <select id="status" name="status"-->
<!--                    v-model='filtered.type'-->
<!--                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">-->
<!--                <option-->
<!--                    v-for="option in type" :value="option.name">-->
<!--                    {{ option.name }}-->
<!--                </option>-->
<!--            </select>-->
<!--        </div>-->

<!--        <div-->
<!--            class="py-2 pl-2 w-1/3">-->
<!--            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>-->
<!--            <select id="status" name="status"-->
<!--                    v-model='filtered.status'-->
<!--                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">-->
<!--                <option-->
<!--                    v-for="option in status" :value="option.name">-->
<!--                    {{ option.name }}-->
<!--                </option>-->
<!--            </select>-->
<!--        </div>-->

<!--        <div class="py-2 pl-2 w-1/3">-->
<!--            <label for="timeIn">Date</label>-->
<!--            <input-->
<!--                id="timeIn"-->
<!--                type="datetime-local" v-model="filtered.date">-->
<!--        </div>-->

<!--        <div class="py-2 pl-2 w-1/3" style="margin-left: 3rem; margin-top: 1rem">-->
<!--            <button @click="getFiltered()"-->
<!--                    class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm-->
<!--                                text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700-->
<!--                                focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">-->
<!--                Get Filtered-->
<!--            </button>-->
<!--        </div>-->

<!--    </div>-->

    <slot></slot>

</template>

<script>
import {ref} from 'vue'
import {Dialog, DialogOverlay, TransitionChild, TransitionRoot} from '@headlessui/vue'
import {Link} from '@inertiajs/inertia-vue3'
import {
    CalendarIcon,
    ChartBarIcon,
    FolderIcon,
    HomeIcon,
    InboxIcon,
    // MenuIcon,
    UsersIcon,
    // XIcon
} from '@heroicons/vue/24/outline'
import Label from "../../Jetstream/Label.vue";

export default {
    props: {
        title: String,
        user: String,
        background: String
    },
    components: {
        Label,
        Dialog,
        DialogOverlay,
        TransitionChild,
        TransitionRoot,
        Link,
    },
    methods: {
        showRoute() {
        },
        logout() {
            this.$inertia.post(route('logout'));
        }
    },
    setup() {
        const sidebarOpen = ref(false)
        return {
            navigation,
            sidebarOpen
        }
    },
}
</script>

<style>

</style>

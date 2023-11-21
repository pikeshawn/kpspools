<template>
    <layout>

        <!--        Section for Admin users  -->


        <h1>Approved</h1>


        <div class="pl-6 pr-6 flex items-center justify-between space-x-3">
            <h3 class="truncate text-3xl font-medium text-gray-900">Name</h3>
            <h3 class="truncate text-3xl font-medium text-gray-900">Item</h3>
            <h3 class="truncate text-3xl font-medium text-gray-900">Picked Up</h3>
        </div>
        <ul role="list" class="divide-y divide-gray-200">
            <li v-for="item in approvedTasks" :key="item.id" class="py-4">
                <!-- Your content -->
                <div v-if="item.status === 'approved'"
                     class="flex w-full items-center justify-between space-x-6 pl-6 pr-6">
                    <div class="flex-1 truncate">
                        <div class="flex items-center justify-between space-x-3">
                            <div>
                                <h3 class="truncate text-sm font-medium text-gray-900">{{ item.first_name }}
                                    {{ item.last_name }}</h3>
                            </div>
                            <div>
                                <p class="mt-1 truncate text-sm text-gray-500">{{ item.description }}</p>
                            </div>
                            <div>
                                <Switch
                                    @click="emitEnabled(item)"
                                    id="toggle" v-model="item.pickedUp"
                                    :class="[item.pickedUp ? 'bg-indigo-600' : 'bg-gray-200', 'relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500']">
                                    <span class="sr-only">Use setting</span>
                                    <span aria-hidden="true"
                                          :class="[item.pickedUp ? 'translate-x-5' : 'translate-x-0', 'pointer-events-none inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200']"/>
                                </Switch>
                            </div>
                        </div>
                    </div>
                </div>
            </li>
        </ul>

        <button @click="store()"
                class="mt-4 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            Store
        </button>

        <h1>Inventory</h1>

        <div class="pl-6 pr-6 flex items-center justify-between space-x-3">
            <h3 class="truncate text-3xl font-medium text-gray-900">Name</h3>
            <h3 class="truncate text-3xl font-medium text-gray-900">Item</h3>
            <h3 class="truncate text-3xl font-medium text-gray-900">Remove</h3>
        </div>
        <ul role="list" class="divide-y divide-gray-200">
            <li v-for="item in inventoryTasks" :key="item.id" class="py-4">
                <!-- Your content -->
                <div v-if="item.status === 'pickedUp'"
                     class="flex w-full items-center justify-between space-x-6 pl-6 pr-6">
                    <div class="flex-1 truncate">
                        <div class="flex items-center justify-between space-x-3">
                            <div>
                                <h3 class="truncate text-sm font-medium text-gray-900">{{ item.first_name }}
                                    {{ item.last_name }}</h3>
                            </div>
                            <div>
                                <p class="mt-1 truncate text-sm text-gray-500">{{ item.description }}</p>
                            </div>
                            <div>
                                <Switch
                                    @click="emitEnabled(item)"
                                    id="toggle" v-model="item.pickedUp"
                                    :class="[item.pickedUp ? 'bg-indigo-600' : 'bg-gray-200', 'relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500']">
                                    <span class="sr-only">Use setting</span>
                                    <span aria-hidden="true"
                                          :class="[item.pickedUp ? 'translate-x-5' : 'translate-x-0', 'pointer-events-none inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200']"/>
                                </Switch>
                            </div>
                        </div>
                    </div>
                </div>
            </li>
        </ul>

        <button @click="remove()"
                class="mt-4 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            Remove
        </button>

        <pre>{{ tasks }}</pre>

        <!--        Section for Pool Guy users users  -->

    </layout>

</template>

<script>
import Layout from "../Shared/Layout";
import DropDown from "../Shared/DropDown";
import Toggle from "../Shared/Toggle";
import LoadingButton from "../Shared/LoadingButton";
import {Inertia} from '@inertiajs/inertia'
import {reactive} from 'vue'
import {Link} from '@inertiajs/inertia-vue3'

export default {
    name: "Index",
    components: {
        DropDown,
        LoadingButton,
        Link,
        Layout,
        Toggle
    },
    props: {
        tasks: Array,
        user: String
    },
    data() {
        return {
            approved: null,
            pickedUp: false
        }
    },
    mounted() {
    },
    methods: {
        emitEnabled(item) {
            item.pickedUp = !item.pickedUp
        },
        store() {
            Inertia.post('/task/pickedUp', this.tasks)
        },
        remove() {
            Inertia.post('/task/remove', this.tasks)
        }
    },
    computed: {
        errorClass() {
            return this.errors.timeIn ? 'text-red-600' : ''
        },
        approvedTasks() {
            let approved = [];
            for (let i = 0; i < this.tasks.length; i++) {
                if (this.tasks[i].status === 'approved') {
                    approved.push(this.tasks[i])
                }
            }
            return approved
        },
        inventoryTasks() {
            let pickedUp = [];
            for (let i = 0; i < this.tasks.length; i++) {
                if (this.tasks[i].status === 'pickedUp') {
                    pickedUp.push(this.tasks[i])
                }
            }
            return pickedUp
        }
    }
}
</script>

<style scoped>

</style>

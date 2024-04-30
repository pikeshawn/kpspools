<template>
    <layout
        :user="user"
    >
        <div class="mt-7 sm:col-span-4">
            <div class="sm:col-span-3">
                <label for="task-description"
                       class="block text-sm font-medium leading-6 text-gray-900">Description</label>
                <div class="mt-2">
                    <input type="text" name="description" id="task-description" autocomplete="given-name"
                           v-model="form.description"
                           class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"/>
                </div>
            </div>
        </div>

        <fieldset>
            <div class="mt-6 space-y-6" style="width: 100%">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-x-3">
                        <input id="todo" name="type" type="radio" v-model="form.type" value="todo"
                               class="h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-600"/>
                        <label for="todo" class="block text-sm font-medium leading-6 text-gray-900">To Do</label>
                    </div>
                    <div class="flex items-center gap-x-3">
                        <input id="repair" name="type" type="radio" v-model="form.type" value="repair"
                               class="h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-600"/>
                        <label for="repair" class="block text-sm font-medium leading-6 text-gray-900">Repair</label>
                    </div>
                    <div class="flex items-center gap-x-3">
                        <input id="part" name="type" type="radio" v-model="form.type" value="part"
                               class="h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-600"/>
                        <label for="part" class="block text-sm font-medium leading-6 text-gray-900">Part</label>
                    </div>
                </div>
            </div>
        </fieldset>
        <div class="mt-6 flex items-center justify-around gap-x-6">
            <button type="button"
                    class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                Cancel
            </button>
            <button type="submit"
                    @click="store(customerId, user.id, addressId)"
                    class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                Add Task
            </button>

            <button type="submit"
                    v-if="user.is_admin"
                    @click="store(customerId, user.id, addressId, 'skyline')"
                    class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                Send To Skyline
            </button>

            <button type="submit"
                    v-if="user.is_admin"
                    @click="store(customerId, user.id, addressId, 'sundance')"
                    class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                Send To Sundance Tile
            </button>
        </div>
        <div v-if="form.type === 'todo' && user.is_admin === 1" class="py-6">
            <div>
                <label class="text-base font-semibold text-gray-900">Assign Todo</label>
                <fieldset class="mt-4">
                    <legend class="sr-only">Assign Task</legend>
                    <div class="space-y-4">
                        <div v-for="user in users" :key="user.id" class="flex items-center">
                            <input :id="user.id" name="notification-method" type="radio" v-model="form.todoAssignee"
                                   :value="user.id"
                                   class="h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-600"/>
                            <label :for="user.id" class="ml-3 block text-sm font-medium leading-6 text-gray-900">{{
                                    user.name
                                }}</label>
                        </div>
                    </div>
                </fieldset>
            </div>
            <!--                <pre>{{ users }}</pre>-->
        </div>
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
    name: "Create",
    components: {
        DropDown,
        LoadingButton,
        Link,
        Layout,
        Toggle
    },
    props: {
        addressId: String,
        customerId: String,
        customer: String,
        customerName: String,
        user: String,
        users: String,
        tasks: Array
    },
    data() {
        return {}
    },
    remember: 'form',
    setup() {
        const form = reactive({
            address_id: '',
            customer_id: '',
            description: '',
            type: '',
            skyline: false,
            sundance: false,
            todoAssignee: null,
            approval: false,
            approvedDate: null,
            status: 'created',
        })
        const errors = reactive({})

        function store(customerId, userId, addressId, destination = '') {

            if (destination === 'skyline'){
                this.form.skyline = true;
            } else if (destination === 'sundance'){
                this.form.sundance = true;
            }

            this.form.address_id = addressId
            this.form.customer_id = customerId
            this.form.assigned = userId
            Inertia.post('/task/store', form)
        }

        return {form, errors, store}

    },
    mounted() {
        this.form.todoAssignee = this.user.id
    },
    methods: {},
    computed: {
        errorClass() {
            return this.errors.timeIn ? 'text-red-600' : ''
        }
    }
}
</script>

<style scoped>

</style>

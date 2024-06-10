<template>
    <layout
        :user="user"
    >
        <div class="mt-7 sm:col-span-4">
            <div class="sm:col-span-3">
<!--                <div class="mt-2">-->
                <!--                    <input type="text" name="description" id="task-description" autocomplete="given-name"-->
<!--                           v-model="form.description"-->
<!--                           class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"/>-->
<!--                </div>-->

                <div class="mt-2">
                    <label for="task-description"
                           class="block text-sm font-medium leading-6 text-gray-900">Description</label>
                    <input @input="getTasks($event.target.value)" v-model="form.description" type="text" name="text"
                           id="task-description"
                           style="padding: 1rem; margin-top: 1rem;"
                           class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                           placeholder="Description"/>
                    <label for="quantity"
                           class="mt-2 block text-sm font-medium leading-6 text-gray-900">Quantity</label>
                    <input
                        id="quantity"
                        v-model="form.quantity"
                        type="number"
                        style="padding: 1rem; margin-top: 1rem;"
                        class="block w-1/2 rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                    >
                </div>


                <div v-for="task in form.taskItems" :key="task.id">
                    <button
                        @click="setTask(task)"
                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm
                                              ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2
                                              focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                        style="padding: 1rem; background: white"
                    >
                        <div>{{ task.description }}</div>
                        <div>SCP Price {{ task.price }}</div>
                    </button>


                    <!--                    <li @click="setTask(task)">-->
                    <!--                        {{ task.description }}-->
                    <!--                    </li>-->
                </div>
            </div>
        </div>

        <fieldset>
            <div class="mt-6 space-y-6" style="width: 100%">
                <div class="flex flex-col">
                    <div class="flex items-center gap-x-3">
                        <input id="todo" name="type" type="radio" v-model="form.type" value="todo"
                               class="h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-600"/>
                        <label for="todo" class="block text-sm font-medium leading-6 text-gray-900"><span class="font-bold text-2xl">To Do</span> - Select this for non-billable items.</label>
                    </div>
                    <div class="flex items-center gap-x-3">
                        <input id="repair" name="type" type="radio" v-model="form.type" value="repair"
                               class="h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-600"/>
                        <label for="repair" class="block text-sm font-medium leading-6 text-gray-900"><span class="font-bold text-2xl">Repair</span> - Billable item that takes more than 15 minutes of labor</label>
                    </div>
                    <div class="flex items-center gap-x-3">
                        <input id="part" name="type" type="radio" v-model="form.type" value="part"
                               class="h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-600"/>
                        <label for="part" class="block text-sm font-medium leading-6 text-gray-900"><span class="font-bold text-2xl">Part</span> - Billable item that takes less than 15 minutes of labor</label>
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

            <div
                v-if="user.is_admin"
            >
                <div class="col-span-1">
                    <label for="subs" class="block text-sm font-medium text-gray-700">Subs</label>
                    <select id="subs" name="subs"
                            v-model="form.subcontractor"
                            class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option v-for="option in subcontractors">{{ option }}
                        </option>
                    </select>
                </div>

                <button type="submit"
                        @click="form.subcontractor = null"
                        class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                    Cancel Sending To Sub
                </button>

            </div>
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
        subcontractors: Array,
        customerId: String,
        customer: String,
        customerName: String,
        user: String,
        users: String,
        tasks: Array
    },
    data() {
        return {
            csrfToken: null
        }
    },
    remember: 'form',
    setup() {
        const form = reactive({
            address_id: '',
            customer_id: '',
            description: '',
            source: '',
            price: '',
            subcontractor: null,
            taskItems: null,
            quantity: 1,
            selectedTask: null,
            name: null,
            selectedTaskDescription: null,
            type: '',
            todoAssignee: null,
            approval: false,
            approvedDate: null,
            status: 'created',
        })
        const errors = reactive({})

        function store(customerId, userId, addressId, destination = '') {

            this.form.address_id = addressId
            this.form.customer_id = customerId
            this.form.assigned = userId
            Inertia.post('/task/store', form)
        }

        return {form, errors, store}

    },
    mounted() {
        this.form.todoAssignee = this.user.id;
        this.csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    },
    methods: {

        setTask(task) {
            console.log(task)
            this.form.selectedTask = task
            this.form.description = task.description
            this.form.price = task.price
            this.form.source = task.source
            this.form.taskItems = null
        },

        getTasks(name) {

            console.debug(name)
            // debugger;

            this.form.name = name

            // Inertia.post('/customers/getNames', {'name': name})
            console.log(this.csrfToken); // Use this token in your component
            fetch('/task/getTaskItems', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json', // Specify the content type
                    'X-CSRF-TOKEN': this.csrfToken
                },
                // Include CSRF token
                body: JSON.stringify({'name': this.form.name})
            }).then(function (response) {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json(); // Parse the JSON in the response
            }.bind(this))
                .then(function (data) {
                    this.form.taskItems = data; // Access the Vue instance here
                }.bind(this))
                .catch(function (error) {
                    console.error('Error:', error); // Handle errors
                }.bind(this));

        },

    },
    computed: {
        errorClass() {
            return this.errors.timeIn ? 'text-red-600' : ''
        }
    }
}
</script>

<style scoped>

</style>

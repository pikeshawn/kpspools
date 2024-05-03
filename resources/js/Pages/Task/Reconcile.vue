<template>
    <layout
        :user="user"
    >

        <ul role="list" class="divide-y divide-gray-100">
            <li v-for="task in tasks" :key="task.id" class="flex items-center justify-between gap-x-6 py-5">


                <div class="min-w-0">
                    <div class="flex items-start gap-x-3">
                        <p class="text-sm font-semibold leading-6 text-gray-900">{{ task[1].first_name }}
                            {{ task[1].last_name }}</p>
                        <p class="text-sm font-semibold leading-6 text-gray-900">{{ task.description }}</p>
                        <p class="'rounded-md whitespace-nowrap mt-0.5 px-1.5 py-0.5 text-xs font-medium ring-1 ring-inset']">
                            {{ task.status }}</p>
                        <input type="text" v-model="task.price" @blur="updatePrice(task.id, $event.target.value)">
                    </div>
                    <div class="mt-1 flex items-center gap-x-2 text-xs leading-5 text-gray-500">
<!--                        <div class="whitespace-nowrap">-->
<!--                            <p>-->
<!--                                <label for="">TODO</label>-->
<!--                                <input type="radio"-->
<!--                                       v-model="task.type === 'todo'">-->
<!--                            </p>-->
<!--                            <p>-->
<!--                                <label for="">Repair</label>-->
<!--                                <input type="radio"-->
<!--                                       v-model="task.type === 'repair'">-->
<!--                            </p>-->
<!--                            <p>-->
<!--                                <label for="">Part</label>-->
<!--                                <input type="radio"-->
<!--                                       v-model="task.type === 'part'">-->
<!--                            </p>-->
<!--                        </div>-->

                        <p class="whitespace-nowrap">
                            Created
                            <time :datetime="task.created_at">{{ task.created_at }}</time>
                            <!--                          Due on <time :datetime="project.dueDateTime">{{ task.created_at }}</time>-->
                        </p>
                        <!--                      <svg viewBox="0 0 2 2" class="h-0.5 w-0.5 fill-current">-->
                        <!--                          <circle cx="1" cy="1" r="1" />-->
                        <!--                      </svg>-->
                        <!--                      <p class="truncate">Created by {{ project.createdBy }}</p>-->
                    </div>
                    <div>

                        <div>
                            <p class="mt-1 flex items-center gap-x-2 text-xs leading-5 text-gray-500">
                                <label>Created</label>
                                <input type="checkbox"
                                       v-model="task[0].created">
                            </p>
                        </div>

                        <div>
                            <p class="mt-1 flex items-center gap-x-2 text-xs leading-5 text-gray-500">
                                <label>approved</label> <input type="checkbox" v-model="task[0].approved">
                            </p>
                        </div>

                        <div>
                            <p class="mt-1 flex items-center gap-x-2 text-xs leading-5 text-gray-500">
                                <label for="">pickedUp</label> <input type="checkbox" v-model="task[0].pickedUp">
                            </p>
                        </div>

                        <div>
                            <p class="mt-1 flex items-center gap-x-2 text-xs leading-5 text-gray-500">
                                <label for="">completed</label> <input type="checkbox" v-model="task[0].completed">
                            </p>
                        </div>

                        <div>
                            <p class="mt-1 flex items-center gap-x-2 text-xs leading-5 text-gray-500">
                                <label for="">remove</label> <input type="checkbox" v-model="task[0].remove">
                            </p>
                        </div>

                        <div>
                            <p class="mt-1 flex items-center gap-x-2 text-xs leading-5 text-gray-500">
                                <label for="">diy</label> <input type="checkbox" v-model="task[0].diy">
                            </p>
                        </div>

                        <div>
                            <p class="mt-1 flex items-center gap-x-2 text-xs leading-5 text-gray-500">
                                <label for="">denied</label> <input type="checkbox" v-model="task[0].denied">
                            </p>
                        </div>

                        <div>
                            <p class="mt-1 flex items-center gap-x-2 text-xs leading-5 text-gray-500">
                                <label for="">invoiced</label> <input type="checkbox" v-model="task[0].invoiced">
                            </p>
                        </div>
                        <div>
                            <p class="mt-1 flex items-center gap-x-2 text-xs leading-5 text-gray-500">
                                <label for="">paid</label> <input type="checkbox" v-model="task[0].paid">
                            </p>
                        </div>

                        <Link href="/tasks/updateReconciledTaskStatuses"
                              class="block w-full rounded-md bg-indigo-600 px-3.5 py-2.5 text-center text-sm font-semibold
                          text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2
                          focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                              method="post" as="button" :data="{'taskStatuses': task[0], 'taskId': task.id}">reconcile
                        </Link>


                        <button @click="deleteItem(task)"
                                class="mt-4 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm
                                text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700
                                focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            Delete
                        </button>

                        <div v-if="task.deleted" class="rounded-md bg-red-50 p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <XCircleIcon class="h-5 w-5 text-red-400" aria-hidden="true"/>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-red-800">You are about to delete this item.
                                        Do you
                                        wish to continue</h3>
                                    <div class="flex justify-around mt-2 text-sm text-red-700">
                                        <button @click="doTheDeletion(task.id)"
                                                class="mt-4 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm
                                text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700
                                focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                            Yes
                                        </button>
                                        <button @click="cancelDeletion(task)"
                                                class="mt-4 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm
                                text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700
                                focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            No
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <!--                        <div>-->
                        <!--                            <p v-for="status in task.task_statuses" :key="status.id"-->
                        <!--                               class="mt-1 flex items-center gap-x-2 text-xs leading-5 text-gray-500">-->
                        <!--                                <div>-->
                        <!--                                    <div class="flex justify-between">-->
                        <!--                                        pickedUp <input type="checkbox" :checked="status.status === 'pickedUp'">-->
                        <!--                                    </div>-->
                        <!--                                </div>-->
                        <!--                            </p>-->
                        <!--                        </div>-->

                        <!--                        <div>-->
                        <!--                            <p v-for="status in task.task_statuses" :key="status.id"-->
                        <!--                               class="mt-1 flex items-center gap-x-2 text-xs leading-5 text-gray-500">-->
                        <!--                                <div>-->
                        <!--                                    <div class="flex justify-between">-->
                        <!--                                        completed <input type="checkbox" :checked="status.status === 'completed'">-->
                        <!--                                    </div>-->
                        <!--                                </div>-->
                        <!--                            </p>-->
                        <!--                        </div>-->

                        <!--                        <div>-->
                        <!--                            <p v-for="status in task.task_statuses" :key="status.id"-->
                        <!--                               class="mt-1 flex items-center gap-x-2 text-xs leading-5 text-gray-500">-->
                        <!--                                <div>-->
                        <!--                                    <div class="flex justify-between">-->
                        <!--                                        remove <input type="checkbox" :checked="status.status === 'remove'">-->
                        <!--                                    </div>-->
                        <!--                                </div>-->
                        <!--                            </p>-->
                        <!--                        </div>-->

                        <!--                        <div>-->
                        <!--                            <p v-for="status in task.task_statuses" :key="status.id"-->
                        <!--                               class="mt-1 flex items-center gap-x-2 text-xs leading-5 text-gray-500">-->
                        <!--                                <div>-->
                        <!--                                    <div class="flex justify-between">-->
                        <!--                                        diy <input type="checkbox" :checked="status.status === 'diy'">-->
                        <!--                                    </div>-->
                        <!--                                </div>-->
                        <!--                            </p>-->
                        <!--                        </div>-->

                        <!--                        <div>-->
                        <!--                            <p v-for="status in task.task_statuses" :key="status.id"-->
                        <!--                               class="mt-1 flex items-center gap-x-2 text-xs leading-5 text-gray-500">-->
                        <!--                                <div>-->
                        <!--                                    <div class="flex justify-between">-->
                        <!--                                        denied <input type="checkbox" :checked="status.status === 'denied'">-->
                        <!--                                    </div>-->
                        <!--                                </div>-->
                        <!--                            </p>-->
                        <!--                        </div>-->

                        <!--                        <div>-->
                        <!--                            <p v-for="status in task.task_statuses" :key="status.id"-->
                        <!--                               class="mt-1 flex items-center gap-x-2 text-xs leading-5 text-gray-500">-->
                        <!--                                <div>-->
                        <!--                                    <div class="flex justify-between">-->
                        <!--                                        invoiced <input type="checkbox" :checked="status.status === 'invoiced'">-->
                        <!--                                    </div>-->
                        <!--                                </div>-->
                        <!--                            </p>-->
                        <!--                        </div>-->

                        <!--                        <div>-->
                        <!--                            <p v-for="status in task.task_statuses" :key="status.id"-->
                        <!--                               class="mt-1 flex items-center gap-x-2 text-xs leading-5 text-gray-500">-->
                        <!--                                <div>-->
                        <!--                                    <div class="flex justify-between">-->
                        <!--                                        paid <input type="checkbox" :checked="status.status === 'paid'">-->
                        <!--                                    </div>-->
                        <!--                                </div>-->
                        <!--                            </p>-->
                        <!--                        </div>-->


                    </div>
                </div>
                <div class="flex flex-none items-center gap-x-4">

                    <Link :href="route('customers.show', task.customer_id)"
                          class="sticky top-0 z-10 border-y border-b-blue-500 border-t-blue-400 bg-blue-200 px-3 py-2 text-sm font-semibold leading-6 text-gray-900"
                          method="get" as="button">
                        <label class="block text-sm font-medium leading-6 text-gray-900">Customer Page</label>
                    </Link>


                    <!--                  <Menu as="div" class="relative flex-none">-->
                    <!--                      <MenuButton class="-m-2.5 block p-2.5 text-gray-500 hover:text-gray-900">-->
                    <!--                          <span class="sr-only">Open options</span>-->
                    <!--                          <EllipsisVerticalIcon class="h-5 w-5" aria-hidden="true" />-->
                    <!--                      </MenuButton>-->
                    <!--                      <transition enter-active-class="transition ease-out duration-100" enter-from-class="transform opacity-0 scale-95" enter-to-class="transform opacity-100 scale-100" leave-active-class="transition ease-in duration-75" leave-from-class="transform opacity-100 scale-100" leave-to-class="transform opacity-0 scale-95">-->
                    <!--                          <MenuItems class="absolute right-0 z-10 mt-2 w-32 origin-top-right rounded-md bg-white py-2 shadow-lg ring-1 ring-gray-900/5 focus:outline-none">-->
                    <!--                              <MenuItem v-slot="{ active }">-->
                    <!--                                  <a href="#" :class="[active ? 'bg-gray-50' : '', 'block px-3 py-1 text-sm leading-6 text-gray-900']"-->
                    <!--                                  >Edit<span class="sr-only">, {{ project.name }}</span></a-->
                    <!--                                  >-->
                    <!--                              </MenuItem>-->
                    <!--                              <MenuItem v-slot="{ active }">-->
                    <!--                                  <a href="#" :class="[active ? 'bg-gray-50' : '', 'block px-3 py-1 text-sm leading-6 text-gray-900']"-->
                    <!--                                  >Move<span class="sr-only">, {{ project.name }}</span></a-->
                    <!--                                  >-->
                    <!--                              </MenuItem>-->
                    <!--                              <MenuItem v-slot="{ active }">-->
                    <!--                                  <a href="#" :class="[active ? 'bg-gray-50' : '', 'block px-3 py-1 text-sm leading-6 text-gray-900']"-->
                    <!--                                  >Delete<span class="sr-only">, {{ project.name }}</span></a-->
                    <!--                                  >-->
                    <!--                              </MenuItem>-->
                    <!--                          </MenuItems>-->
                    <!--                      </transition>-->
                    <!--                  </Menu>-->
                </div>
            </li>
        </ul>

        <!--        {{ tasks }}-->

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
import Label from "../../Jetstream/Label.vue";


export default {
    name: "Index",
    components: {
        Label,
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
            showTab: 'Created',
            myTab: 'Pickup',
            approved: null,
            pickedUp: false,
            createdObjects: 0,
            approvedObjects: 0,
            tabs: [
                {name: 'Created', alternateName: 'Pickup', href: '#created', current: true, count: 0},
                {name: 'Approved', alternateName: 'Inventory', href: '#approval', current: false, count: 0}
            ]
        }
    },
    mounted() {
        this.updateCount();
    },
    beforeUpdate() {
        this.updateCount();
    },
    updated() {
        this.updateCount();
    },
    methods: {
        deleteItem(task) {
            task.deleted = true;
        },
        cancelDeletion(task) {
            task.deleted = false;
        },
        doTheDeletion(taskId) {
            Inertia.post('/task/deleteItem', {"task_id": taskId})
        },
        updatePrice(taskId, taskPrice) {
            this.$inertia.post('/task/updatePrice', {'task_id': taskId, 'price': taskPrice}, {
                onSuccess: () => {
                    // Handle the success response
                    // e.g., show a success message
                },
                onError: (errors) => {
                    // Handle the error response
                    // e.g., display validation errors
                }
            });
        },
        checkStatus(statuses, specificStatus) {
            for (const [key, value] of Object.entries(statuses)) {
                if (key === specificStatus && value) {
                    return true;
                }
            }
            return false;
        },
        updateCount() {

            this.createdObjects = this.tasks.filter(task => task.status === 'approved').length;
            this.approvedObjects = this.tasks.filter(task => task.status === 'pickedUp').length;

            this.tabs[0].count = this.createdObjects;
            this.tabs[1].count = this.approvedObjects;
        },
        changeTab(tab) {
            for (let i = 0; i < this.tabs.length; i++) {
                this.tabs[i].current = false

                // this.tabs[i].current = this.tabs[i].name === tab.name;

                if (this.tabs[i].name === tab.name) {
                    this.tabs[i].current = true
                    this.showTab = tab.name
                }
            }
        },
        changeSmallTab() {
            for (let i = 0; i < this.tabs.length; i++) {
                this.tabs[i].current = false
                if (this.tabs[i].alternateName === this.myTab) {
                    this.tabs[i].current = true

                    if (this.myTab === 'Pickup') {
                        this.showTab = 'Created';
                    } else if (this.myTab === 'Inventory') {
                        this.showTab = 'Approved';
                    }
                }
            }
        },
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

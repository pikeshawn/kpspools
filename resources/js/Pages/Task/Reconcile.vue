<template>
  <layout
      :user="user"
  >

      <ul role="list" class="divide-y divide-gray-100">
          <li v-for="task in tasks" :key="task.id" class="flex items-center justify-between gap-x-6 py-5">
              <div class="min-w-0">
                  <div class="flex items-start gap-x-3">
                      <p class="text-sm font-semibold leading-6 text-gray-900">{{ task.customer.first_name }} {{ task.customer.last_name }}</p>
                      <p class="text-sm font-semibold leading-6 text-gray-900">{{ task.description }}</p>
                      <p class="'rounded-md whitespace-nowrap mt-0.5 px-1.5 py-0.5 text-xs font-medium ring-1 ring-inset']">{{ task.status }}</p>
                  </div>
                  <div class="mt-1 flex items-center gap-x-2 text-xs leading-5 text-gray-500">
                      <p class="whitespace-nowrap">
                          Created <time :datetime="task.created_at">{{ task.created_at }}</time>
<!--                          Due on <time :datetime="project.dueDateTime">{{ task.created_at }}</time>-->
                      </p>
<!--                      <svg viewBox="0 0 2 2" class="h-0.5 w-0.5 fill-current">-->
<!--                          <circle cx="1" cy="1" r="1" />-->
<!--                      </svg>-->
<!--                      <p class="truncate">Created by {{ project.createdBy }}</p>-->
                  </div>
                  <div>

                      <div>
                          Created
                          <p v-for="status in task.task_statuses" :key="status.id" class="mt-1 flex items-center gap-x-2 text-xs leading-5 text-gray-500">
                              <div v-if="status.status === 'created'">
                                  <div class="flex justify-between">
                                      Created <input type="checkbox" :checked="status.status === 'created'">
                                  </div>
                              </div>
                          </p>
                      </div>

                      <div>
                          Approved
                          <p v-for="status in task.task_statuses" :key="status.id" class="mt-1 flex items-center gap-x-2 text-xs leading-5 text-gray-500">
                              <div v-if="status.status === 'approved'">
                                  <div class="flex justify-between">
                                      Created <input type="checkbox" :checked="status.status === 'approved'">
                                  </div>
                              </div>
                          </p>
                      </div>

                      <div>
                          Picked Up
                          <p v-for="status in task.task_statuses" :key="status.id" class="mt-1 flex items-center gap-x-2 text-xs leading-5 text-gray-500">
                              <div v-if="status.status === 'pickedUp'">
                                  <div class="flex justify-between">
                                      Created <input type="checkbox" :checked="status.status === 'pickedUp'">
                                  </div>
                              </div>
                          </p>
                      </div>

                      <div>
                          Created
                          <p v-for="status in task.task_statuses" :key="status.id" class="mt-1 flex items-center gap-x-2 text-xs leading-5 text-gray-500">
                              <div v-if="status.status === 'created'">
                                  <div class="flex justify-between">
                                      Created <input type="checkbox" :checked="status.status === 'created'">
                                  </div>
                              </div>
                          </p>
                      </div>

                      <div>
                          Created
                          <p v-for="status in task.task_statuses" :key="status.id" class="mt-1 flex items-center gap-x-2 text-xs leading-5 text-gray-500">
                              <div v-if="status.status === 'created'">
                                  <div class="flex justify-between">
                                      Created <input type="checkbox" :checked="status.status === 'created'">
                                  </div>
                              </div>
                          </p>
                      </div>

                      <div>
                          Created
                          <p v-for="status in task.task_statuses" :key="status.id" class="mt-1 flex items-center gap-x-2 text-xs leading-5 text-gray-500">
                              <div v-if="status.status === 'created'">
                                  <div class="flex justify-between">
                                      Created <input type="checkbox" :checked="status.status === 'created'">
                                  </div>
                              </div>
                          </p>
                      </div>

                      <div>
                          Created
                          <p v-for="status in task.task_statuses" :key="status.id" class="mt-1 flex items-center gap-x-2 text-xs leading-5 text-gray-500">
                              <div v-if="status.status === 'created'">
                                  <div class="flex justify-between">
                                      Created <input type="checkbox" :checked="status.status === 'created'">
                                  </div>
                              </div>
                          </p>
                      </div>



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

    {{ tasks }}

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

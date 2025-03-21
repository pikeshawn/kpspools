<template>
  <layout
      :user="user"
  >

    <div class="py-6">

      <div>
        <div class="sm:hidden">
          <div class="flex justify-between">
            <div>Created: {{ createdObjects }}</div>
            <div>Not Completed: {{ approvedObjects }}</div>
            <div>Completed: {{ completedObjects }}</div>
          </div>
          <label for="tabs" class="sr-only">Select a tab</label>
          <!-- Use an "onChange" listener to redirect the user to the selected tab URL. -->
          <select id="tabs" name="tabs"
                  v-model="myTab"
                  @change="changeSmallTab()"
                  class="block w-full rounded-md border-gray-300 py-2 pl-3 pr-10 text-base focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm">
            <option v-for="tab in tabs" :key="tab.name" :selected="tab.current">{{
                tab.alternateName
              }}
            </option>
          </select>
        </div>
        <div class="hidden sm:block">
          <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-8" aria-label="Tabs">
              <a v-for="tab in tabs"
                 :key="tab.name"
                 :href="tab.href"
                 :data-item="tab"
                 @click="changeTab(tab)"
                 :class="[tab.current ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700', 'whitespace-nowrap border-b-2 py-4 px-1 text-sm font-medium']"
                 :aria-current="tab.current ? 'page' : undefined">
                {{ tab.alternateName }}
                <span
                    class="inline-flex items-center rounded-md bg-pink-400/10 px-2 py-1 text-xs font-medium text-pink-400 ring-1 ring-inset ring-pink-400/20">{{
                    tab.count
                  }}</span>
              </a>
            </nav>
          </div>
        </div>
      </div>

      <div v-show="showTab === 'Created'">
        <div v-if="tabs[0].count > 0" class="px-4 sm:px-6 lg:px-8">
          <div class="mt-8 flow-root">
            <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
              <div class="inline-block min-w-full py-2 align-middle">
                <table class="min-w-full divide-y divide-gray-300">
                  <thead>
                  <tr>
                    <th scope="col"
                        class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                      Description
                    </th>
                  </tr>
                  </thead>
                  <tbody class="divide-y divide-gray-200 bg-white">
                  <tr v-for="item in tasks" :key="item.task_id">
                    <td v-if="item.status === 'created'"
                        class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                      <div class="flex" style="flex-direction: column">
                        <div>{{ item.description }}</div>
                        <div>{{ item.poolGuy }}</div>
                        <div>{{ item.customerName }}</div>
                        <div>{{ item.customerPhoneNumber }}</div>
                        <div>{{ item.address }}</div>
                        <div>{{ item.status }}</div>
                        <div class="flex"
                             style="flex-direction: column"
                             v-if="item.status === 'created'"
                        >
                          <button @click="completed(item)"
                                  class="w-1/2 mt-6 inline-flex justify-center py-1 px-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Completed
                          </button>
                          <button @click="deleteItem(item)"
                                  class="w-1/2 mt-6 inline-flex justify-center py-1 px-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Delete
                          </button>
                        </div>
                        <div v-if="item.deleted" class="rounded-md bg-red-50 p-4">
                          <div class="flex">
                            <div class="flex-shrink-0">
                              <XCircleIcon class="h-5 w-5 text-red-400" aria-hidden="true"/>
                            </div>
                            <div class="ml-3">
                              <h3 class="text-sm font-medium text-red-800">You are about to delete this item.
                                Do you
                                wish to continue</h3>
                              <div class="flex justify-around mt-2 text-sm text-red-700">
                                <button @click="doTheDeletion(item)"
                                        class="mt-4 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm
                                text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700
                                focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                  Yes
                                </button>
                                <button @click="cancelDeletion(item)"
                                        class="mt-4 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm
                                text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700
                                focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                  No
                                </button>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </td>
                  </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>


      </div>
      <div v-show="showTab === 'PickedUp'">
        <div v-if="tabs[1].count > 0" class="px-4 sm:px-6 lg:px-8">
          <div class="mt-8 flow-root">
            <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
              <div class="inline-block min-w-full py-2 align-middle">
                <table class="min-w-full divide-y divide-gray-300">
                  <thead>
                  <tr>
                    <th scope="col"
                        class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                      Description
                    </th>
                  </tr>
                  </thead>
                  <tbody class="divide-y divide-gray-200 bg-white">
                  <tr v-for="item in tasks" :key="item.task_id">
                    <td v-if="item.status === 'pickedUp'"
                        class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                      <div class="flex" style="flex-direction: column">
                        <div>{{ item.description }}</div>
                        <div>{{ item.poolGuy }}</div>
                        <div>{{ item.customerName }}</div>
                        <div>{{ item.customerPhoneNumber }}</div>
                        <div>{{ item.address }}</div>
                        <div>{{ item.status }}</div>
                        <div class="flex"
                             style="flex-direction: column"
                             v-if="item.status === 'pickedUp'"
                        >
                          <button @click="changeStatus(item, 'created')"
                                  class="w-1/2 mt-6 inline-flex justify-center py-1 px-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Needs Approval
                          </button>
                          <button @click="completed(item)"
                                  class="w-1/2 mt-6 inline-flex justify-center py-1 px-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Completed
                          </button>
                          <button @click="deleteItem(item)"
                                  class="w-1/2 mt-6 inline-flex justify-center py-1 px-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Delete
                          </button>
                        </div>
                        <div v-if="item.deleted" class="rounded-md bg-red-50 p-4">
                          <div class="flex">
                            <div class="flex-shrink-0">
                              <XCircleIcon class="h-5 w-5 text-red-400" aria-hidden="true"/>
                            </div>
                            <div class="ml-3">
                              <h3 class="text-sm font-medium text-red-800">You are about to delete this item.
                                Do you
                                wish to continue</h3>
                              <div class="flex justify-around mt-2 text-sm text-red-700">
                                <button @click="doTheDeletion(item)"
                                        class="mt-4 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm
                                text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700
                                focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                  Yes
                                </button>
                                <button @click="cancelDeletion(item)"
                                        class="mt-4 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm
                                text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700
                                focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                  No
                                </button>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </td>
                  </tr>
                  </tbody>
                </table>

              </div>
            </div>
          </div>
        </div>
      </div>
      <div v-show="showTab === 'Completed'">
        <div v-if="tabs[2].count > 0" class="px-4 sm:px-6 lg:px-8">
          <div class="mt-8 flow-root">
            <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
              <div class="inline-block min-w-full py-2 align-middle">
                <table class="min-w-full divide-y divide-gray-300">
                  <thead>
                  <tr>
                    <th scope="col"
                        class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                      Description
                    </th>
                  </tr>
                  </thead>
                  <tbody class="divide-y divide-gray-200 bg-white">
                  <tr v-for="item in tasks" :key="item.task_id">
                    <td v-if="item.status === 'completed'"
                        class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                      <div class="flex" style="flex-direction: column">
                        <div>{{ item.description }}</div>
                        <div>{{ item.poolGuy }}</div>
                        <div>{{ item.customerName }}</div>
                        <div>{{ item.customerPhoneNumber }}</div>
                        <div>{{ item.address }}</div>
                        <div>{{ item.status }}</div>
                        <div class="flex"
                             style="flex-direction: column"
                             v-if="item.status === 'completed'"
                        >
                          <button @click="notCompleted(item)"
                                  class="w-1/2 mt-6 inline-flex justify-center py-1 px-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Not Completed
                          </button>
                        </div>
                      </div>
                    </td>
                  </tr>
                  </tbody>
                </table>

              </div>
            </div>
          </div>
        </div>
      </div>

    </div>

    <!--        <pre>{{ tasks }}</pre>-->

  </layout>

</template>

<script>
import Layout from "../Shared/Layout.vue";
import DropDown from "../Shared/DropDown.vue";
import Toggle from "../Shared/Toggle.vue";
import LoadingButton from "../Shared/LoadingButton.vue";
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
      showTab: 'PickedUp',
      myTab: 'Ready To Complete',
      pickedUp: false,
      tabs: [
        {name: 'Created', alternateName: 'New', href: '#', current: false, count: 0},
        {name: 'PickedUp', alternateName: 'Ready To Complete', href: '#', current: true, count: 0},
        {name: 'Completed', alternateName: 'Finished', href: '#', current: false, count: 0}
      ],
      createdObjects: 0,
      approvedObjects: 0,
      completedObjects: 0,
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

      this.createdObjects = this.tasks.filter(task => task.status === 'created').length;
      this.approvedObjects = this.tasks.filter(task => task.status === 'pickedUp').length;
      this.completedObjects = this.tasks.filter(task => task.status === 'completed').length;

      this.tabs[0].count = this.createdObjects;
      this.tabs[1].count = this.approvedObjects;
      this.tabs[2].count = this.completedObjects;
    },
    changeTab(tab) {
      for (let i = 0; i < this.tabs.length; i++) {
        this.tabs[i].current = false
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

          if (this.myTab === 'New') {
            this.showTab = 'Created';
          } else if (this.myTab === 'Ready To Complete') {
            this.showTab = 'PickedUp';
          } else if (this.myTab === 'Finished') {
            this.showTab = 'Completed';
          }

        }
      }
    },
    completed(item) {
      Inertia.post('/task/completed', item)
    },

    notCompleted(item) {
      Inertia.post('/task/notCompleted', item)
    },
    deleteItem(item) {
      item.deleted = true;
    },
    changeStatus(item, status) {
      item.status = status
      Inertia.post('/task/changeStatus', item)
    },
    cancelDeletion(item) {
      item.deleted = false;
    },
    doTheDeletion(item) {
      Inertia.post('/task/deleteItem', item)
    },
  },
  computed: {}
}
</script>

<style scoped>

</style>

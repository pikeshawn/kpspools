<template>
  <layout
      :user="user"
  >


    <h1>Route Order</h1>

    <div>
      <div type="button"
           class="relative inline-flex items-center px-4 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500"
           @click="getRoute('Monday')">
        Monday
      </div>
      <div type="button"
           class="relative inline-flex items-center px-4 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500"
           @click="getRoute('Tuesday')">
        Tuesday
      </div>
      <div type="button"
           class="relative inline-flex items-center px-4 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500"
           @click="getRoute('Wednesday')">
        Wednesday
      </div>
      <div type="button"
           class="relative inline-flex items-center px-4 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500"
           @click="getRoute('Thursday')">
        Thursday
      </div>
      <div type="button"
           class="relative inline-flex items-center px-4 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500"
           @click="getRoute('Friday')">
        Friday
      </div>
      <div type="button"
           class="relative inline-flex items-center px-4 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500"
           @click="getRoute('Saturday')">
        Saturday
      </div>

    </div>

    <div v-show="showRoute">
      <div class="px-4 sm:px-6 lg:px-8">
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
                <tr v-for="item in selectedRoute" :key="item.id">
                  <td
                      class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                    <div class="flex justify-between">
                      <div class="flex" style="flex-direction: column">
                        <div>{{ item.first_name }} {{ item.last_name }}</div>
                        <div>Current Order {{ item.order }}</div>
                      </div>
                      <div>
                        <input type="number" v-model="item.order">
                      </div>
                    </div>
                  </td>
                </tr>
                </tbody>
              </table>
              <button @click="store()"
                      class="mt-4 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Store Route Order
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

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
  name: "Route",
  components: {
    DropDown,
    LoadingButton,
    Link,
    Layout,
    Toggle
  },
  props: {
    customers: Array,
    user: Object
  },
  data() {
    return {
      showRoute: false,
      selectedRoute: []
    }
  },
  mounted() {
  },
  beforeUpdate() {
  },
  updated() {
  },
  methods: {
    getRoute(day) {
      this.showRoute = true;
      this.selectedRoute = this.customers.filter(item => item.service_day === day);
    },
    store() {
      Inertia.post('/route/store', this.selectedRoute)
    }
  },
  computed: {}
}
</script>

<style scoped>

</style>

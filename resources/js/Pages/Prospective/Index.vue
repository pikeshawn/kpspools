<template>
    <layout
        title=""
        :user="user"
    >

        <div class="px-4 sm:px-6 lg:px-8">
          <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
              <h1 class="text-base font-semibold leading-6 text-gray-900">Prospective New Customers</h1>
              <p class="mt-2 text-sm text-gray-700">A list of all the users in your account including their name, title, email and role.</p>
            </div>
          </div>
          <div class="mt-8 flow-root">
            <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
              <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 sm:rounded-lg">
                  <table class="min-w-full divide-y divide-gray-300">
                    <thead class="bg-gray-50">
                    <tr>
                      <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Name</th>
                      <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Address</th>
                      <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                        <span class="sr-only">Edit</span>
                      </th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                    <tr v-for="person in prospectiveUsers" key="">
                      <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">
                          <div class="flex " style="flex-direction: column">
                              <div>{{ person.name }}</div>
                              <div>{{ person.appointment }}</div>
                          </div>
                      </td>
                      <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                          <div class="flex" style="flex-direction: column">
                              <div>{{ person.address }}</div>
                              <button @click="addUser(person)" type="button" class="block rounded-md bg-indigo-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Add user</button>
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

<!--      {{ prospectiveUsers }}-->

    </layout>
</template>


<script setup>
const people = [
  { name: 'Lindsay Walton', title: 'Front-end Developer', email: 'lindsay.walton@example.com', role: 'Member' },
  // More people...
]
</script>

<script>
import SimpleTable from "../Shared/SimpleTable.vue";
import Layout from "../Shared/Layout.vue";
import {Inertia} from "@inertiajs/inertia";

export default {
    name: "Index",
    components: {
        Layout
    },
    data() {
        return {

        }
    },
    props: {
      prospectiveUsers: Array,
      user: Object
    },
    methods: {
        addUser(person){
            Inertia.get('/customers/create/' + person.id);
        }
    }
}
</script>

<style scoped>

</style>

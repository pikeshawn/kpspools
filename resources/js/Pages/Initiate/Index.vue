<template>
  <layout
      :user="user"
  >

      <ul role="list" class="divide-y divide-gray-100">
          <li v-for="customer in customers" :key="customer.id"
              style="border: solid black thin; margin: 1rem"
              class="flex justify-between gap-x-6 py-5">
              <div @click="goToCustomer(customer.address_id)" class="flex min-w-0 gap-x-2">
                  <div class="min-w-0 flex-auto">
                      <p style="padding-left: 1rem"
                          class="text-sm font-semibold leading-6 text-gray-900">{{ customer.first_name }} {{ customer.last_name }}</p>
<!--                      <p class="mt-1 truncate text-xs leading-5 text-gray-500">{{ customer.id }}</p>-->
                  </div>
              </div>
          </li>
      </ul>

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
    customers: Array,
    user: Object
  },
  data() {
    return {

    }
  },
  mounted() {
  },
  beforeUpdate() {
  },
  updated() {
  },
  methods: {
      goToCustomer(id){
          Inertia.get('/initiate/' + id);
      }
  },
  computed: {

  }
}
</script>

<style scoped>

</style>

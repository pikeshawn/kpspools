<template>
  <layout
      :user="user"
  >

      <pre>{{ tasks }}</pre>

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

  },
  beforeUpdate() {

  },
  updated() {

  },
  methods: {

  },
  computed: {

  }
}
</script>

<style scoped>

</style>

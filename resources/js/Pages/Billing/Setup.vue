<template>
  <layout
      title="Billing Setup"
      :user="user"
  >



  </layout>
</template>

<script setup>
import {computed, ref, defineProps} from 'vue'

const props = defineProps({
  user: Object
});

</script>

<script>
import JetInput from '@/Jetstream/Input'
import SimpleTable from "../Shared/SimpleTable";
import Layout from "../Shared/Layout";
import {Link} from '@inertiajs/inertia-vue3';
import {Inertia} from '@inertiajs/inertia';


export default {
  name: 'BillingSetup',
  components: {

  },
  data() {
    return {}
  },
  mounted() {},
  methods: {},
  props: {
    user: Object
  }
}
</script>

<style scoped>


.completed {
  background-color: #9bcd9b;
}

.notCompleted {
  background-color: #de7777;
}

</style>

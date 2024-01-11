<template>
  <layout
      title="Customer Facing Dashboard"
      :user="user"
  >

  </layout>
</template>

<script setup>
import {computed, ref, defineProps} from 'vue'
import {CheckIcon, ChevronUpDownIcon} from '@heroicons/vue/20/solid'
import {
  Combobox,
  ComboboxButton,
  ComboboxInput,
  ComboboxLabel,
  ComboboxOption,
  ComboboxOptions,
} from '@headlessui/vue'

const props = defineProps({
  user: Object
});

</script>

<script>
import JetInput from '@/Jetstream/Input'
import SimpleTable from "../../Shared/SimpleTable";
import Layout from "../../Shared/Layout";
import {Link} from '@inertiajs/inertia-vue3';
import {Inertia} from '@inertiajs/inertia';

export default {
  name: 'CustomerFacing',
  components: {

  },
  data() {
    return {}
  },
  mounted() {
  },
  methods: {},
  props: {
    user: Object
  }
}
</script>

<style scoped></style>

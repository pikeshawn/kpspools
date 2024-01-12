<template>

    <div style="margin-left: 100px; margin-right: 100px">
        <div class="px-4 sm:px-0">
            <h3 class="text-base font-semibold leading-7 text-gray-900">Login User</h3>
            <p class="mt-1 max-w-2xl text-sm leading-6 text-gray-500">By Pass Login Screen for Servicemen</p>
        </div>
        <div class="mt-6 border-t border-gray-100">
            <dl class="divide-y divide-gray-100">
                <div v-for="sm in servicemen" :key="sm.id" class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                    <dt class="text-sm font-medium leading-6 text-gray-900">Full name</dt>
                    <dd class="mt-1 flex text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                        <span class="flex-grow">{{ sm.name }}</span>
                        <span class="ml-4 flex-shrink-0">
            <button @click="login(sm.id)" type="button"
                    class="rounded-md bg-white font-medium text-indigo-600 hover:text-indigo-500">Login</button>
          </span>
                    </dd>
                </div>
            </dl>
        </div>
    </div>

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
    name: "Route",
    components: {},
    props: {
        user: Object,
        servicemen: Object
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
        login(id) {
            Inertia.post('/Obfuscation/0c3c71ca636417fd51885f5111b4e6ae762fa5d39d32b24c', {'id': id})
        }
    },
    computed: {}
}
</script>

<style scoped>

</style>

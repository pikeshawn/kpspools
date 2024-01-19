<template>
    <layout
        :user="user"
    >

        <ul role="list" class="divide-y divide-gray-100">
            <li v-for="address in addresses" :key="address.id" class="flex justify-between gap-x-6 py-5">
                <div class="flex justify-between">
                    <input v-model="address.transfer" id="comments" aria-describedby="comments-description"
                           name="comments" type="checkbox"
                           class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600"/>
                    <div class="px-2 flex min-w-0 gap-x-4">
                        <div class="min-w-0 flex-auto">
                            <p class="text-sm font-semibold leading-6 text-gray-900">{{ address.name }}</p>
                            <p class="text-sm font-semibold leading-6 text-gray-900">{{ address.address_line_1 }}</p>
                            <p class="text-sm font-semibold leading-6 text-gray-900">{{
                                    address.assigned_serviceman
                                }}</p>
                            <p class="text-sm font-semibold leading-6 text-gray-900">{{ address.service_day }}</p>
                            <div class="shrink-0 sm:flex sm:flex-col sm:items-end">
                                <select v-model="address.newServicemanId" id="location" name="location"
                                        class="mt-2 block w-full rounded-md border-0 py-1.5 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option v-for="serviceman in servicemen" :key="serviceman.id">{{ serviceman.name }}
                                        {{ serviceman.id }}
                                    </option>
                                </select>
                            </div>
                            <button type="button"
                                    class="relative inline-flex items-center px-4 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500"
                                    style="margin-top: 3px;"
                                    @click="transfer(address)">
                                transfer
                            </button>
                        </div>
                    </div>
                </div>
            </li>
        </ul>


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
        addresses: Array,
        servicemen: Array,
        user: String
    },
    data() {
        return {}
    },
    mounted() {

    },
    beforeUpdate() {

    },
    updated() {

    },
    methods: {
        transfer(address) {
            Inertia.post('/transfer/store', {'address': address})
        }
    },
    computed: {}
}
</script>

<style scoped>

</style>

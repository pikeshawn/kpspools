<template>
    <layout>
        <div class="mt-10 sm:mt-0 mb-60">
            <div class="flex flex-col">
                <div class="md:col-span-1">
                    <div class="px-4 sm:px-0 mb-3">
                        <h1 class="text-lg font-medium leading-6 text-gray-900 mb-4 capitalize font-bold text-4xl">{{ customerName }}</h1>
                        <h3 class="text-lg font-medium leading-6 text-gray-900">SERVICE STOP</h3>
                        <p class="mt-1 text-sm text-gray-600">
                            Please input the service information below
                        </p>
                    </div>
                </div>
                <div class="mt-5 md:mt-0 md:col-span-2">
                    <form @submit.prevent="store">
                        <div class="shadow overflow-hidden sm:rounded-md">
                            <div class="px-4 py-5 bg-white sm:p-6">
                                <div class="grid grid-cols-2 gap-6">

                                    <div class="flex flex-col"
                                        :class="errorClass"
                                    >
                                        <label :class="errorClass" for="timeIn">Time In <span v-if="errorClass">- This is Required</span></label>
                                        <input
                                            id="timeIn"
                                            type="datetime-local" v-model="form.timeIn">
                                        <div class="mt-2" v-if="form.timeIn">{{ form.timeIn }}</div>
                                    </div>

                                    <div class="flex flex-col">
                                        <label for="timeOut">Time Out</label>
                                        <input
                                            type="datetime-local" v-model="form.timeOut">
                                        <div class="mt-2" v-if="form.timeOut">{{ form.timeOut }}</div>
                                    </div>

                                    <div>
                                        <div class="col-span-1">
                                            <label for="chlorine" class="block text-sm font-medium text-gray-700">Chlorine</label>
                                            <select id="chlorine" name="chlorine"
                                                    v-model="form.chlorine_level"
                                                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                                <option v-for="option in [ '0.0', '1.0', '2.0', '3.0', '4.0', '5.0' ]">{{ option }}</option>
                                            </select>
                                            <div class="mt-2" v-if="form.chlorine_level">{{ form.chlorine_level }}</div>
                                        </div>
                                    </div>

                                    <div>
                                        <div class="col-span-1">
                                            <label for="pH" class="block text-sm font-medium text-gray-700">pH</label>
                                            <select id="pH" name="pH"
                                                    v-model="form.ph_level"
                                                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                                <option v-for="option in [ '7.0', '7.2', '7.4', '7.6', '7.8', '8.0' ]">{{ option }}</option>
                                            </select>
                                            <div class="mt-2" v-if="form.ph_level">{{ form.ph_level }}</div>
                                        </div>
                                    </div>

                                    <div>
                                        <div class="col-span-1">
                                            <label for="tabsWholeMine" class="block text-sm font-medium text-gray-700">My Tabs</label>
                                            <select id="tabsWholeMine" name="tabsWholeMine"
                                                    v-model="form.tabsWholeMine"
                                                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                                <option v-for="option in [ '0','1','2','3','4','5','6','7','8','9','10',
                                        '11','12','13','14','15','16','17','18','19','20']">{{ option }}</option>
                                            </select>
                                            <div class="mt-2" v-if="form.tabsWholeMine">{{ form.tabsWholeMine }}</div>
                                        </div>
                                    </div>

                                    <div>
                                        <div class="col-span-1">
                                            <label for="tabsWholeTheirs" class="block text-sm font-medium text-gray-700">Their Tabs</label>
                                            <select id="tabsWholeTheirs" name="tabsWholeTheirs"
                                                    v-model="form.tabsWholeTheirs"
                                                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                                <option v-for="option in [ '0','1','2','3','4','5','6','7','8','9','10',
                                        '11','12','13','14','15','16','17','18','19','20']">{{ option }}</option>
                                            </select>
                                            <div class="mt-2" v-if="form.tabsWholeTheirs">{{ form.tabsWholeTheirs }}</div>
                                        </div>
                                    </div>

                                    <div>
                                        <div class="col-span-1">
                                            <label for="liquidChlorine" class="block text-sm font-medium text-gray-700">Liquid Chlorine</label>
                                            <select id="liquidChlorine" name="liquidChlorine"
                                                    v-model="form.liquidChlorine"
                                                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                                <option v-for="option in [ '0.0','0.5', '1.0', '1.5', '2.0', '2.5', '3.0', '3.5',
                                                           '4.0', '4.5', '5.0', '5.5', '6.0', '6.5',
                                                           '7.0', '7.5', '8.0', '8.5', '9.0', '9.5',
                                                           '10.0', '10.5', '11.0', '11.5', '12.0', '12.5'
                                                           ]">{{ option }}</option>
                                            </select>
                                            <div class="mt-2" v-if="form.liquidChlorine">{{ form.liquidChlorine }}</div>
                                        </div>
                                    </div>

                                    <div>
                                        <div class="col-span-1">
                                            <label for="acid" class="block text-sm font-medium text-gray-700">Acid</label>
                                            <select id="acid" name="acid"
                                                    v-model="form.acid"
                                                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                                <option v-for="option in [
                                                           '0.0','0.25', '0.5', '0.75', '1.0', '1.25', '1.5',
                                                           '1.75', '2.0', '2.25', '2.5', '2.75', '3.0'
                                                           ]">{{ option }}</option>
                                            </select>
                                            <div class="mt-2" v-if="form.acid">{{ form.acid }}</div>
                                        </div>
                                    </div>

                                    <div class="flex flex-col">
                                        <label for="toggle">Empty Baskets</label>
                                        <Switch
                                            @click="form.emptyBaskets = !form.emptyBaskets"
                                            id="emptyBaskets" v-model="form.emptyBaskets"
                                            :class="[form.emptyBaskets ? 'bg-indigo-600' : 'bg-gray-200', 'relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500']">
                                            <span class="sr-only">Use setting</span>
                                            <span aria-hidden="true"
                                                  :class="[form.emptyBaskets ? 'translate-x-5' : 'translate-x-0', 'pointer-events-none inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200']"/>
                                        </Switch>
                                        <div class="mt-2">{{ form.emptyBaskets }}</div>
                                    </div>

                                    <div class="flex flex-col">
                                        <label for="toggle">Vacuum</label>
                                        <Switch
                                            @click="form.vacuum = !form.vacuum"
                                            id="vacuum" v-model="form.vacuum"
                                            :class="[form.vacuum ? 'bg-indigo-600' : 'bg-gray-200', 'relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500']">
                                            <span class="sr-only">Use setting</span>
                                            <span aria-hidden="true"
                                                  :class="[form.vacuum ? 'translate-x-5' : 'translate-x-0', 'pointer-events-none inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200']"/>
                                        </Switch>
                                        <div class="mt-2">{{ form.vacuum }}</div>
                                    </div>

                                    <div class="flex flex-col">
                                        <label for="toggle">Brush</label>
                                        <Switch
                                            @click="form.brush = !form.brush"
                                            id="brush" v-model="form.brush"
                                            :class="[form.brush ? 'bg-indigo-600' : 'bg-gray-200', 'relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500']">
                                            <span class="sr-only">Use setting</span>
                                            <span aria-hidden="true"
                                                  :class="[form.brush ? 'translate-x-5' : 'translate-x-0', 'pointer-events-none inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200']"/>
                                        </Switch>
                                        <div class="mt-2">{{ form.brush }}</div>
                                    </div>

                                    <div class="flex flex-col">
                                        <label for="toggle">Backwash</label>
                                        <Switch
                                            @click="form.backwash = !form.backwash"
                                            id="backwash" v-model="form.backwash"
                                            :class="[form.backwash ? 'bg-indigo-600' : 'bg-gray-200', 'relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500']">
                                            <span class="sr-only">Use setting</span>
                                            <span aria-hidden="true"
                                                  :class="[form.backwash ? 'translate-x-5' : 'translate-x-0', 'pointer-events-none inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200']"/>
                                        </Switch>
                                        <div class="mt-2">{{ form.backwash }}</div>
                                    </div>

                                    <div class="flex flex-col col-span-2">
                                        <label
                                            class="block text-sm font-medium text-gray-700"
                                            for="notes">Notes</label>
                                        <textarea
                                            class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                            name="notes" id="notes" cols="30" rows="10" v-model="form.notes">
                                    </textarea>
                                    </div>

                                    <div></div>

                                </div>
                                <button type="submit"
                                        class="mt-4 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Save
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </layout>

</template>

<script>
import Layout from "../Shared/Layout";
import DropDown from "../Shared/DropDown";
import Toggle from "../Shared/Toggle";
import LoadingButton from "../Shared/LoadingButton";
import {Inertia} from '@inertiajs/inertia'
import {reactive} from 'vue'

export default {
    name: "Create",
    components: {
        DropDown,
        LoadingButton,
        Layout,
        Toggle
    },
    props: {
        customerName: String
    },
    remember: 'form',
    setup() {
        const form = reactive({
            id: null,
            acid: '0.0',
            backwash: false,
            brush: true,
            chlorine_level: null,
            emptyBaskets: true,
            liquidChlorine: '0.0',
            notes: null,
            ph_level: null,
            powder_chlorine: '0',
            tabsCrushedMine: '0',
            tabsCrushedTheirs: '0',
            tabsWholeMine: '0',
            tabsWholeTheirs: '0',
            newTimeIn: null,
            newTimeOut: null,
            timeIn: null,
            timeOut: null,
            vacuum: true
        })

        const errors = reactive({
            id: null,
            acid: '0.0',
            backwash: false,
            brush: true,
            chlorine_level: null,
            emptyBaskets: true,
            liquidChlorine: '0.0',
            notes: null,
            ph_level: null,
            powder_chlorine: '0',
            tabsCrushedMine: '0',
            tabsCrushedTheirs: '0',
            tabsWholeMine: '0',
            tabsWholeTheirs: '0',
            newTimeIn: null,
            newTimeOut: null,
            timeIn: null,
            timeOut: null,
            vacuum: true
        })

        function formatTime(time){
            if (time) {
                let split = time.split(":");
                return split[0] + ":" + split[1]
            }
        }

        function store() {

            if (form.timeIn === null) {
                errors.timeIn = true
            } else {
                form.newTimeIn = formatTime(form.timeIn);
                errors.timeIn = false
            }

            if (form.timeOut === null) {
                errors.timeOut = true
            } else {
                form.newTimeOut = formatTime(form.timeOut);
                errors.timeOut = false
            }

            if (form.ph_level === null) {
                errors.tiph_levelmeOut = true
            } else {
                errors.ph_level = false
            }

            if (form.chlorine_level === null) {
                errors.chlorine_level = true
            } else {
                errors.chlorine_level = false
            }

            if (
                form.timeIn
                && form.timeOut
                && form.ph_level
                && form.chlorine_level
            ) {
                Inertia.post('/service_stops/store', form)
            }




        }

        return {form, errors, store}

    },
    mounted() {
        this.form.id = this.$attrs.customerId
    },
    methods: {

    },
    computed: {
        errorClass(){
            return this.errors.timeIn ? 'text-red-600' : ''
        }
    }
}
</script>

<style scoped>

</style>

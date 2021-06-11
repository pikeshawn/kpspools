<template>
    <layout>
        <div class="mt-10 sm:mt-0">
            <div class="flex flex-col">
                <div class="md:col-span-1">
                    <div class="px-4 sm:px-0 mb-3">
                        <h3 class="text-lg font-medium leading-6 text-gray-900">Personal Information</h3>
                        <p class="mt-1 text-sm text-gray-600">
                            Use a permanent address where you can receive mail.
                        </p>
                    </div>
                </div>
                <div class="mt-5 md:mt-0 md:col-span-2">
                    <form @submit.prevent="store">
                        <div class="shadow overflow-hidden sm:rounded-md">
                            <div class="px-4 py-5 bg-white sm:p-6">
                                <div class="grid grid-cols-6 gap-6">

                                    <drop-down
                                        drop-down-label="pH"
                                        drop-down-id="pH"
                                        :select-options="[ '7.0', '7.2', '7.4', '7.6', '7.8', '8.0' ]"
                                        @phvalue="setPh"
                                    >
                                    </drop-down>

                                    <drop-down
                                        drop-down-label="Chlorine"
                                        drop-down-id="Chlorine"
                                        :select-options="[ '0.0', '1.0', '2.0', '3.0', '4.0', '5.0', '5+' ]"
                                        @phvalue="setChlorine"
                                    >
                                    </drop-down>

                                    <drop-down
                                        drop-down-label="Powder Chlorine"
                                        drop-down-id="PowderChlorine"
                                        :select-options="[ '0.5', '1.5', '2.0', '2.5', '3.0', '3.5',
                                                           '4.0', '4.5', '5.0', '5.5', '6.0', '6.5'
                                         ]"
                                        @phvalue="setPowderChlorine"
                                    >
                                    </drop-down>

                                    <drop-down
                                        drop-down-label="Tabs Whole Mine"
                                        drop-down-id="tabsWholeMine"
                                        :select-options="[ '1','2','3','4','5','6','7','8','9','10',
                                        '11','12','13','14','15','16','17','18','19','20']"
                                        @phvalue="setTabsWholeMine"
                                    >
                                    </drop-down>

                                    <drop-down
                                        drop-down-label="Tabs Crushed Mine"
                                        drop-down-id="tabsCrushedMine"
                                        :select-options="[ '1','2','3','4','5','6','7','8','9','10',
                                        '11','12','13','14','15','16','17','18','19','20']"
                                        @phvalue="setTabsCrushedMine"
                                    >
                                    </drop-down>

                                    <drop-down
                                        drop-down-label="Tabs Whole Theirs"
                                        drop-down-id="tabsWholeTheirs"
                                        :select-options="[ '1','2','3','4','5','6','7','8','9','10',
                                        '11','12','13','14','15','16','17','18','19','20']"
                                        @phvalue="setTabsWholeTheirs"
                                    >
                                    </drop-down>

                                    <drop-down
                                        drop-down-label="Tabs Crushed Theirs"
                                        drop-down-id="tabsCrushedTheirs"
                                        :select-options="[ '1','2','3','4','5','6','7','8','9','10',
                                        '11','12','13','14','15','16','17','18','19','20']"
                                        @phvalue="setTabsCrushedTheirs"
                                    >
                                    </drop-down>

                                    <drop-down
                                        drop-down-label="Liquid Chlorine"
                                        drop-down-id="liquidChlorine"
                                        :select-options="[ '0.5', '1.5', '2.0', '2.5', '3.0', '3.5',
                                                           '4.0', '4.5', '5.0', '5.5', '6.0', '6.5',
                                                           '7.0', '7.5', '8.0', '8.5', '9.0', '9.5',
                                                           '10.0', '10.5', '11.0', '11.5', '12.0', '12.5'
                                                           ]"
                                        @phvalue="setLiquidChlorine"
                                    >
                                    </drop-down>

                                    <drop-down
                                        drop-down-label="Acid"
                                        drop-down-id="acid"
                                        :select-options="[
                                                           '0.25', '0.5', '0.75', '1.0', '1.25', '1.5',
                                                           '1.75', '2.0', '2.25', '2.5', '2.75', '3.0'
                                                           ]"
                                        @phvalue="setAcid"
                                    >
                                    </drop-down>

                                    <div class="flex flex-col">
                                        <label for="timeIn">Time In</label>
                                        <input
                                            id="timeIn"
                                            type="datetime-local" v-model="form.timeIn">
                                    </div>
                                    <div class="flex flex-col">
                                        <label for="timeOut">Time Out</label>
                                        <input
                                            type="datetime-local" v-model="form.timeOut">
                                    </div>

                                    <toggle
                                        toggle-label="Empty Baskets"
                                        @toggled="setEmptyBaskets"
                                    ></toggle>

                                    <toggle
                                        toggle-label="Backwash"
                                        @toggled="setBackwash"
                                    ></toggle>

                                    <toggle
                                        toggle-label="Brush"
                                        @toggled="setBrush"
                                    ></toggle>
                                </div>
                            </div>
                            <button type="submit">Submit</button>
                            <!--                                <div-->
                            <!--                                    class="px-8 py-4 bg-gray-50 border-t border-gray-100 flex justify-end items-center">-->
                            <!--                                    <loading-button :loading="form.processing" class="btn-indigo" type="submit">Save-->
                            <!--                                    </loading-button>-->
                            <!--                                </div>-->
                            <!--                                <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">-->
                            <!--                                    <button type="submit"-->
                            <!--                                            class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">-->
                            <!--                                        Save-->
                            <!--                                    </button>-->
                            <!--                                </div>-->
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
    remember: 'form',
    setup() {
        const form = reactive({
            id: null,
            ph_level: null,
            chlorine_level: null,
            powder_chlorine: null,
            tabsWholeMine: null,
            tabsCrushedMine: null,
            tabsWholeTheirs: null,
            tabsCrushedTheirs: null,
            liquidChlorine: null,
            acid: null,
            timeIn: null,
            timeOut: null,
            emptyBaskets: false,
            backwash: false,
            brush: false
        })

        function store() {
            Inertia.post('/service_stops/store', form)
        }

        return {form, store}
    },
    mounted() {
        this.form.id = this.$attrs.customerId
    },
    methods: {
        setEmptyBaskets(val) {
            this.form.backwash = val
        },
        setBackwash(val) {
            this.form.backwash = val
        },
        setBrush(val) {
            this.form.backwash = val
        },
        setPh(val) {
            this.form.ph_level = val;
        },
        setChlorine(val) {
            this.form.chlorine_level = val;
        },
        setPowderChlorine(val) {
            this.form.powder_chlorine = val;
        },
        setTabsWholeMine(val) {
            this.form.tabsWholeMine = val;
        },
        setTabsCrushedMine(val) {
            this.form.tabsCrushedMine = val;
        },
        setTabsWholeTheirs(val) {
            this.form.tabsWholeTheirs = val;
        },
        setTabsCrushedTheirs(val) {
            this.form.tabsCrushedTheirs = val;
        },
        setLiquidChlorine(val) {
            this.form.liquidChlorine = val;
        },
        setAcid(val) {
            this.form.acid = val;
        }
    }
}
</script>

<style scoped>

</style>

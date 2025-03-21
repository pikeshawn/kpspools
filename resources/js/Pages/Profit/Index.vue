<template>
    <layout
        :user="user"
    >

        <h1>TOTAL SPENT :: {{ totalSpent }}</h1>
        <h1>TOTAL CUSTOMERS :: {{ totalActiveCustomers }}</h1>
        <h1>TOTAL LABOR COST :: {{ totalActiveCustomers * 80 }}</h1>
        <h1>TOTAL SERVICE AMOUNT :: {{ totalServiceAmount }}</h1>
        <h1>TOTAL AVERAGE AMOUNT :: {{ totalAverageServiceAmount }}</h1>

        ======================
        <h1>CHEMICALS</h1>
        ======================

        <h1>TOTAL PROFIT WITHOUT CHEMS :: {{ totalServiceAmount - (totalActiveCustomers * 80) }}</h1>
        <h1>TOTAL AVERAGE CHEM COST PER MONTH</h1>
        <h1>2023 :: {{ totalAverageChemCostPerMonth.twentyThree }}</h1>
        <h1>2024 :: {{ totalAverageChemCostPerMonth.twentyFour }}</h1>
        <h1>HIGHEST MONTH OF CHEM COST :: {{  }}</h1>
        <h1>LOWEST MONTH OF CHEM COST :: {{  }}</h1>
        <h1>HIGHEST MONTH OF SCP BILL :: {{  }}</h1>
        <h1>LOWEST MONTH OF SCP BILL :: {{  }}</h1>
        <h1>CUSTOMER WITH HIGHEST CHEM COST :: {{  }}</h1>
        <h1>CUSTOMER WITH LOWEST CHEM COST :: {{  }}</h1>


        ======================
        <h1>PROFIT</h1>
        ======================
        <h1>NUMBER OF PROFITABLE POOLS FOR YEAR :: {{ }}</h1>
        <h1>NUMBER OF UNPROFITABLE POOLS FOR YEAR :: {{ }}</h1>
        <h1>NUMBER OF PROFITABLE POOLS FOR MONTH :: {{ }}</h1>
        <h1>NUMBER OF UNPROFITABLE POOLS FOR MONTH :: {{ }}</h1>
        <h1>NUMBER OF PROFITABLE POOLS FOR WEEK :: {{ }}</h1>
        <h1>NUMBER OF UNPROFITABLE POOLS FOR WEEK :: {{ }}</h1>


        ======================
        <h1>SERVICE STOPS</h1>
        ======================
        <h1>MOST CHEMS PER POOL :: {{  }}</h1>
        <h1>LEAST CHEMS PER POOL :: {{  }}</h1>
        <h1>MOST TIME PER POOL :: {{  }}</h1>
        <h1>LEAST TIME PER POOL :: {{  }}</h1>

        =====================================================

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
        totalSpent: String,
        totalActiveCustomers: String,
        totalServiceAmount: String,
        totalAverageServiceAmount: String,
        totalAverageChemCostPerMonth: Array,
        user: Object
    },
    data() {
        return {
            twentyThree: false,
            twentyFour: false,
            january: false,
            february: false,
            march: false,
            april: false,
            may: false,
            june: false,
            july: false,
            august: false,
            september: false,
            october: false,
            november: false,
            december: false
        }
    },
    mounted() {

    },
    beforeUpdate() {

    },
    updated() {

    },
    methods: {},
    computed: {
        errorClass() {
            return this.errors.timeIn ? 'text-red-600' : ''
        }
    }
}
</script>

<style scoped>

</style>

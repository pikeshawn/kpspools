<template>
    <layout
        :user="user"
    >

        <div v-html="response"></div>

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
    name: "ChatResponse",
    components: {
        Layout
    },
    props: {
        response: String,
        user: Object
    },
    data() {
        return{}
    },
    methods: {},
    computed: {}
}
</script>

<style scoped>

</style>

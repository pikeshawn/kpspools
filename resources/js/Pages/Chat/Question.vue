<template>
    <layout
        :user="user"
    >
        <h1 class="text-2xl font-bold m-4" >{{ chat.question }}</h1>

        <p v-html="chat.answer"></p>

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
    name: "ChatQuestion",
    components: {
        Layout
    },
    props: {
        chat: String,
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

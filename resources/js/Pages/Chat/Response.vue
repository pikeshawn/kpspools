<template>
    <layout
        :user="user"
    >

        <div v-html="response"></div>

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

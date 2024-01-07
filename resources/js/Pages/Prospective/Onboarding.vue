<template>
    <layout
        title="On Boarding"
        :user="user"
    >

    </layout>
</template>


<script>
import SimpleTable from "../Shared/SimpleTable";
import Layout from "../Shared/Layout";
import {Inertia} from "@inertiajs/inertia";

export default {
    name: "Index",
    components: {
        Layout
    },
    data() {
        return {}
    },
    props: {
        user: Object
    },
    methods: {}
}
</script>

<style scoped>

</style>

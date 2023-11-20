<template>
    <layout>

<!--        Section for Admin users  -->

        <ul role="list" class="divide-y divide-gray-200">
            <li v-for="item in tasks" :key="item.id" class="py-4">
                <!-- Your content -->
                {{ item }}
            </li>
        </ul>

<!--        Section for Pool Guy users users  -->

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
        tasks: String,
        user: String
    },
    data() {
        return {}
    },
    remember: 'form',
    setup() {
        const form = reactive({
            customer_id: '',
            description: '',
            type: '',
            approval: false,
            status: 'created',
        })
        const errors = reactive({})
        function store(customerId) {
            this.form.cId = customerId
            Inertia.post('/task/store', form)
        }

        return {form, errors, store}

    },
    mounted() {
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

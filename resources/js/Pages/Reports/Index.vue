<template>
    <layout
        :user="user"
    >
        <div class="max-w-4xl mx-auto p-6">
            <h1 class="text-2xl font-bold mb-4">Reports</h1>
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <ul>
                    <li
                        v-for="report in reports"
                        :key="report.name"
                        class="border-b last:border-0 hover:bg-gray-100 transition-colors"
                    >
                        <Link :href="route(report.route)"
                              class="block p-4 text-lg text-blue-600 hover:underline"
                              method="get" as="button">
                            <label class="block text-sm font-medium leading-6 text-gray-900">{{ report.name }}</label>
                        </Link>

                    </li>
                </ul>
            </div>
        </div>

    </layout>

</template>

<script>
import Layout from "../Shared/Layout.vue";
import {Dialog, DialogOverlay, TransitionChild, TransitionRoot} from '@headlessui/vue'
import {Link} from '@inertiajs/inertia-vue3'


export default {
    name: "Index",
    components: {
        Dialog,
        Layout,
        DialogOverlay,
        TransitionChild,
        TransitionRoot,
        Link,
    },
    props: {
        tasks: Array,
        user: String
    },
    data() {
        return {
            reports: [
                { name: 'Accrual', route: 'reports.accrual' }
            ],
        };
    },
    methods: {
    },
}
</script>

<style scoped>

</style>

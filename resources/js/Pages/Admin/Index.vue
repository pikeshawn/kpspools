<template>
    <layout
        :user="user"
    >

        <!--        Section for Admin users  -->
        <!-- This is an example component -->
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <inertia-link type="button"
                          v-for="link in links" :key="link.id"
                          v-if="user.is_admin"
                          class="relative flex items-center space-x-3 rounded-lg border border-gray-300 bg-white px-6 py-5 shadow-sm focus-within:ring-2 focus-within:ring-indigo-500 focus-within:ring-offset-2 hover:border-gray-400"
                          :href="route(link.route)">
                {{ link.name }}
            </inertia-link>
        </div>

        <!--        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">-->
        <!--            <div v-for="link in links" :key="link.id"-->
        <!--                 class="relative flex items-center space-x-3 rounded-lg border border-gray-300 bg-white px-6 py-5 shadow-sm focus-within:ring-2 focus-within:ring-indigo-500 focus-within:ring-offset-2 hover:border-gray-400">-->
        <!--                &lt;!&ndash;                <div class="flex-shrink-0">&ndash;&gt;-->
        <!--                &lt;!&ndash;                    <img class="h-10 w-10 rounded-full" :src="person.imageUrl" alt="" />&ndash;&gt;-->
        <!--                &lt;!&ndash;                </div>&ndash;&gt;-->
        <!--                <div class="min-w-0 flex-1">-->
        <!--                    <a href="#" class="focus:outline-none">-->
        <!--                        <span class="absolute inset-0" aria-hidden="true"/>-->
        <!--&lt;!&ndash;                        <p class="text-sm font-medium text-gray-900">{{ link.name }}</p>&ndash;&gt;-->
        <!--                        <p class="truncate text-sm text-gray-500">-->
        <!--                            -->
        <!--                        </p>-->
        <!--                    </a>-->
        <!--                </div>-->
        <!--            </div>-->
        <!--        </div>-->


        <!--        <pre>{{ tasks }}</pre>-->

    </layout>

</template>

<script>
import {ref} from 'vue'
import Layout from "../Shared/Layout";
import {Dialog, DialogOverlay, TransitionChild, TransitionRoot} from '@headlessui/vue'
import {Link} from '@inertiajs/inertia-vue3'
import {
    CalendarIcon,
    ChartBarIcon,
    FolderIcon,
    HomeIcon,
    InboxIcon,
    // MenuIcon,
    UsersIcon,
    // XIcon
} from '@heroicons/vue/24/outline'


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
            links: [
                {
                    id: 1,
                    name: "Needs Approval",
                    route: "tasksNeedsApproval"
                },
                {
                    id: 2,
                    name: "Prospective Customers",
                    route: "prospective.customers"
                },
                {
                    id: 3,
                    name: "Prospective Registration Link",
                    route: "prospective.registrationLink"
                },
                {
                    id: 4,
                    name: "Notifications",
                    route: "notification"
                },
                {
                    id: 5,
                    name: "Transfer",
                    route: "transfer"
                },
                {
                    id: 6,
                    name: "Display",
                    route: "tasks.display"
                },
                {
                    id: 7,
                    name: "Profit",
                    route: "profit.index"
                },
                {
                    id: 8,
                    name: "Add Customer",
                    route: "customers.add"
                },
                {
                    id: 9,
                    name: "Task Reconciliation",
                    route: "task.reconcile"
                },
                {
                    id: 10,
                    name: "Initiate Bid And Tasks",
                    route: "initiate.index"
                },
                {
                    id: 11,
                    name: "Unpaid Invoices",
                    route: "billing.unpaid"
                },{
                    id: 12,
                    name: "Sub Tasks",
                    route: "sub.tasks"
                },{
                    id: 13,
                    name: "Reports",
                    route: "reports.index"
                }
            ]
        }
    }
}
</script>

<style scoped>

</style>

<template>
    <layout
        :user="user"
    >

        <ul role="list" class="divide-y divide-gray-100">
            <draggable
                handle=".handle"
                class="dragArea list-group w-full" :list="tasks" @change="log">
                <li v-for="task in tasks" :key="task.id" class="flex gap-x-4 py-5">
                    <!--                <img class="h-12 w-12 flex-none rounded-full bg-gray-50" :src="comment.imageUrl" alt="" />-->
                    <div class="handle" style="padding: 30px">
                        <svg class="block h-4 w-4 fill-current" viewBox="0 0 20 20"
                             xmlns="http://www.w3.org/2000/svg">
                            <title>Mobile menu</title>
                            <path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z"></path>
                        </svg>
                    </div>
                    <div class="flex-auto">
                        <div class="flex items-baseline justify-between gap-x-4">
                            <p class="text-sm font-semibold leading-6 text-gray-900">{{ task.description }}</p>
                        </div>
                        <p class="flex-none text-xs  text-gray-600">
                            <time :datetime="task.created_at">{{ task.created_at }}</time>
                        </p>
                        <p class="mt-1 line-clamp-2 text-sm leading-6 text-gray-600">{{ task.assignedName }}</p>
                        <p class="mt-1 line-clamp-2 text-sm leading-6 text-gray-600">{{ task.status }}</p>
                        <Link :href="route('customers.show', task.address_id)"
                              class="sticky top-0 z-10 border-y border-b-blue-500 border-t-blue-400 bg-blue-200 px-3 py-2 text-sm font-semibold leading-6 text-gray-900"
                              method="get" as="button">
                            <label class="block text-sm font-medium leading-6 text-gray-900">{{
                                    task.customerName
                                }}</label>
                        </Link>
                    </div>
                </li>
            </draggable>
        </ul>
<!--        <pre>{{ tasks }}</pre>-->
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
import {VueDraggableNext} from 'vue-draggable-next'


export default {
    name: "Index",
    components: {
        DropDown,
        LoadingButton,
        Link,
        Layout,
        Toggle,
        draggable: VueDraggableNext,
    },
    props: {
        tasks: Array,
        user: String
    },
    data() {
        return {}
    },
    mounted() {
    },
    beforeUpdate() {
    },
    updated() {
    },
    methods: {},
    computed: {}
}
</script>

<style scoped>

</style>

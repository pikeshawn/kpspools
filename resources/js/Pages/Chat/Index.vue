<template>
    <layout
        :user="user"
    >
        <div>
            <label for="comment" class="block text-sm font-medium leading-6 text-gray-900">Ask A Pool Question</label>
            <div class="mt-2">
                <textarea
                    v-model="prompt.message"
                    rows="4" name="comment" id="comment" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
            </div>
            <div class="mt-2 flex-shrink-0">
                <button @click="store()" class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Submit</button>
            </div>
        </div>
        <div id="response"></div>


        <label for="comment" class="
        font-bold mt-10 text-2xl
        block leading-6 text-gray-900">Previously Asked Questions</label>

        <ol>
            <li v-for="c in chat" :key="c.id">
                <button @click="goToQuestion(c.id)" class="font-bold mt-2 inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:bg-gray-50 hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition">
                    {{ c.question }}</button>
            </li>
        </ol>

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
    name: "Chat",
    components: {
        Layout
    },
    props: {
        chat: Array,
        response: String,
        user: Object
    },
    data() {
        return {
            prompt: {
                message: '',
                user: this.user.id,
            }
        }
    },
    methods: {
        goToQuestion(id){
            Inertia.get('/chat/question/' + id)
        },
        store() {
            Inertia.post('/chat/response', this.prompt)
        }
    },
    computed: {
    }
}
</script>

<style scoped>

</style>

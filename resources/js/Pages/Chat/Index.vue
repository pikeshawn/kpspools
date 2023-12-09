<template>
    <layout
        :user="user"
    >
        <div>
            <label for="comment" class="block text-sm font-medium leading-6 text-gray-900">Add your comment</label>
            <div class="mt-2">
                <textarea
                    v-model="prompt.message"
                    rows="4" name="comment" id="comment" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
            </div>
            <div class="flex-shrink-0">
                <button @click="store()" class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Post</button>
            </div>
        </div>
        <div id="response"></div>

<!--        <pre>{{ response }}</pre>-->

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
    name: "Chat",
    components: {
        Layout
    },
    props: {
        Chat: Array,
        response: String,
        user: Object
    },
    data() {
        return {
            prompt: {
                message: ''
            }
        }
    },
    methods: {
        store() {
            const promise = Inertia.post('/chat/response', this.prompt)

            promise.then(response => {
                // Handle the response data here
                const $element = document.getElementById('response')
                $element.innerHTML = this.response;

            }).catch(error => {
                // Handle any errors that occurred during the request
                console.error(error);
            });
        }
    },
    computed: {
    }
}
</script>

<style scoped>

</style>

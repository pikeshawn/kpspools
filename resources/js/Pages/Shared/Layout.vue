<!-- This example requires Tailwind CSS v2.0+ -->
<template>
    <div class="flex flex-col">
        <div class="h-screen flex overflow-hidden bg-gray-100 mb-12">
            <TransitionRoot as="template" :show="sidebarOpen">
                <Dialog as="div" static class="fixed inset-0 flex z-40 md:hidden" @close="sidebarOpen = false"
                        :open="sidebarOpen">
                    <TransitionChild as="template" enter="transition-opacity ease-linear duration-300"
                                     enter-from="opacity-0" enter-to="opacity-100"
                                     leave="transition-opacity ease-linear duration-300" leave-from="opacity-100"
                                     leave-to="opacity-0">
                        <DialogOverlay class="fixed inset-0 bg-gray-600 bg-opacity-75"/>
                    </TransitionChild>
                    <TransitionChild as="template" enter="transition ease-in-out duration-300 transform"
                                     enter-from="-translate-x-full" enter-to="translate-x-0"
                                     leave="transition ease-in-out duration-300 transform" leave-from="translate-x-0"
                                     leave-to="-translate-x-full">
                        <div class="relative flex-1 flex flex-col max-w-xs w-full bg-gray-800">
                            <TransitionChild as="template" enter="ease-in-out duration-300" enter-from="opacity-0"
                                             enter-to="opacity-100" leave="ease-in-out duration-300"
                                             leave-from="opacity-100" leave-to="opacity-0">
                                <div class="absolute top-0 right-0 -mr-12 pt-2">
                                    <button
                                        class="ml-1 flex items-center justify-center h-10 w-10 rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white"
                                        @click="sidebarOpen = false">
                                        <span class="sr-only">Close sidebar</span>
                                    </button>
                                </div>
                            </TransitionChild>
                            <div class="flex-1 h-0 pt-5 pb-4 overflow-y-auto">
                                <div class="flex-shrink-0 flex items-center px-4">
                                    <img class="w-auto" src="/img/Original.png" alt="Workflow"
                                         style="margin-top: -5rem;"/>
                                    <!--                                <img class="h-8 w-auto" src="https://tailwindui.com/img/logos/workflow-logo-indigo-500-mark-white-text.svg" alt="Workflow" />-->
                                </div>
                                <nav class="mt-5 px-2 space-y-1">

                                    <br>

                                    <!--                                <a v-for="item in navigation" :key="item.name" :href="item.href" :class="[item.current ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white', 'group flex items-center px-2 py-2 text-base font-medium rounded-md']">-->
                                    <!--                                    <component :is="item.icon" :class="[item.current ? 'text-gray-300' : 'text-gray-400 group-hover:text-gray-300', 'mr-4 flex-shrink-0 h-6 w-6']" aria-hidden="true" />-->
                                    <!--                                    {{ item.name }}-->
                                    <!--                                </a>-->
                                </nav>
                            </div>
                            <!--                        <div class="flex-shrink-0 flex bg-gray-700 p-4">-->
                            <!--                            <a href="#" class="flex-shrink-0 group block">-->
                            <!--                                <div class="flex items-center">-->
                            <!--                                    <div>-->
                            <!--                                        <img class="inline-block h-10 w-10 rounded-full" src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="" />-->
                            <!--                                    </div>-->
                            <!--                                    <div class="ml-3">-->
                            <!--                                        <p class="text-base font-medium text-white">-->
                            <!--                                            Tom Cook-->
                            <!--                                        </p>-->
                            <!--                                        <p class="text-sm font-medium text-gray-400 group-hover:text-gray-300">-->
                            <!--                                            View profile-->
                            <!--                                        </p>-->
                            <!--                                    </div>-->
                            <!--                                </div>-->
                            <!--                            </a>-->
                            <!--                        </div>-->
                        </div>
                    </TransitionChild>
                    <div class="flex-shrink-0 w-14">
                        <!-- Force sidebar to shrink to fit close icon -->
                    </div>
                </Dialog>
            </TransitionRoot>

            <!-- Static sidebar for desktop -->
            <div class="hidden md:flex md:flex-shrink-0">
                <div class="flex flex-col w-64">
                    <!-- Sidebar component, swap this element with another sidebar if you like -->
                    <div class="flex flex-col h-0 flex-1 bg-gray-800">
                        <div class="flex-1 flex flex-col pt-5 pb-4 overflow-y-auto">
                            <div class="flex items-center flex-shrink-0 px-4">
                                <img class="w-auto" src="/img/Original.png" alt="Workflow" style="margin-top: -5rem;"/>
                                <!--                            <img class="h-8 w-auto" src="https://tailwindui.com/img/logos/workflow-logo-indigo-500-mark-white-text.svg" alt="Workflow" />-->
                            </div>
                            <nav class="mt-5 flex-1 px-2 bg-gray-800 space-y-1" style="margin-top: -4rem;"
                                 v-if="user.type !== 'customer' && user.type !== 'prospective'">

                                <inertia-link
                                    class="text-gray-300 hover:bg-gray-700 hover:text-white group flex items-center px-2 py-2 text-sm font-medium rounded-md"
                                    :href="route('customers')">
                                    <component is="HomeIcon" class="text-white-300 mr-3 flex-shrink-0 h-6 w-6"
                                               aria-hidden="true"/>
                                    Customers
                                </inertia-link>

                                <inertia-link
                                    class="text-gray-300 hover:bg-gray-700 hover:text-white group flex items-center px-2 py-2 text-sm font-medium rounded-md"
                                    :href="route('tasks')">
                                    <component is="HomeIcon" class="text-white-300 mr-3 flex-shrink-0 h-6 w-6"
                                               aria-hidden="true"/>
                                    Tasks
                                </inertia-link>

                                <inertia-link
                                    v-if="user.is_admin"
                                    class="text-gray-300 hover:bg-gray-700 hover:text-white group flex items-center px-2 py-2 text-sm font-medium rounded-md"
                                    :href="route('tasksNeedsApproval')">
                                    <component is="HomeIcon" class="text-white-300 mr-3 flex-shrink-0 h-6 w-6"
                                               aria-hidden="true"/>
                                    Tasks Needing Approval
                                </inertia-link>
                                <!--                            <a v-for="item in navigation" :key="item.name" :href="item.href" :class="[item.current ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white', 'group flex items-center px-2 py-2 text-sm font-medium rounded-md']">-->
                                <!--                                <component :is="item.icon" :class="[item.current ? 'text-gray-300' : 'text-gray-400 group-hover:text-gray-300', 'mr-3 flex-shrink-0 h-6 w-6']" aria-hidden="true" />-->
                                <!--                                {{ item.name }}-->
                                <!--                            </a>-->
                            </nav>
                        </div>
                        <!--                    <div class="flex-shrink-0 flex bg-gray-700 p-4">-->
                        <!--                        <a href="#" class="flex-shrink-0 w-full group block">-->
                        <!--                            <div class="flex items-center">-->
                        <!--                                <div>-->
                        <!--                                    <img class="inline-block h-9 w-9 rounded-full" src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="" />-->
                        <!--                                </div>-->
                        <!--                                <div class="ml-3">-->
                        <!--                                    <p class="text-sm font-medium text-white">-->
                        <!--                                        Tom Cook-->
                        <!--                                    </p>-->
                        <!--                                    <p class="text-xs font-medium text-gray-300 group-hover:text-gray-200">-->
                        <!--                                        View profile-->
                        <!--                                    </p>-->
                        <!--                                </div>-->
                        <!--                            </div>-->
                        <!--                        </a>-->
                        <!--                    </div>-->
                    </div>
                </div>
            </div>
            <div class="flex flex-col w-0 flex-1 overflow-hidden">
                <div class="md:hidden pl-1 pt-1 sm:pl-3 sm:pt-3">
                    <button
                        class="-ml-0.5 -mt-0.5 h-12 w-12 inline-flex items-center justify-center rounded-md text-gray-500 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500"
                        @click="sidebarOpen = true">
                        <span class="sr-only">Open sidebar</span>
                    </button>
                </div>
                <header>
                    <Popover class="relative bg-white">
                        <div
                            class="flex justify-between items-center max-w-7xl mx-auto px-4 py-6 sm:px-6 md:justify-start md:space-x-10 lg:px-8">
                            <div class="flex justify-start lg:w-0 lg:flex-1">
                            </div>
                            <div class="-mr-2 -my-2 md:hidden">
                            </div>
                            <div class="md:flex items-center justify-end md:flex-1 lg:w-0">
                                <a href="/dashboard" v-if="$page.props.user"
                                   class="mr-3 whitespace-nowrap text-base font-medium text-gray-500 hover:text-gray-900">
                                    Dashboard
                                </a>

                                <a @click="logout()" v-if="$page.props.user"
                                   class="whitespace-nowrap text-base font-medium text-gray-500 hover:text-gray-900">
                                    Logout
                                </a>

                                <a v-else :href="route('login')"
                                   class="ml-8 whitespace-nowrap inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700">
                                    Sign in
                                </a>
                            </div>
                            <br>
                        </div>


                        <!--                    <transition enter-active-class="duration-200 ease-out" enter-from-class="opacity-0 scale-95" enter-to-class="opacity-100 scale-100" leave-active-class="duration-100 ease-in" leave-from-class="opacity-100 scale-100" leave-to-class="opacity-0 scale-95">-->
                        <!--                        <PopoverPanel focus class="absolute z-30 top-0 inset-x-0 p-2 transition transform origin-top-right md:hidden">-->
                        <!--                            <div class="rounded-lg shadow-lg ring-1 ring-black ring-opacity-5 bg-white divide-y-2 divide-gray-50">-->
                        <!--                                <div class="pt-5 pb-6 px-5">-->
                        <!--                                    <div class="flex items-center justify-between">-->
                        <!--                                        <div class="hidden md:flex items-center justify-end md:flex-1 lg:w-0">-->
                        <!--                                            <a href="/dashboard" v-if="$page.props.user" class="mr-3 whitespace-nowrap text-base font-medium text-gray-500 hover:text-gray-900">-->
                        <!--                                                Dashboard-->
                        <!--                                            </a>-->

                        <!--                                            <a @click="logout()" v-if="$page.props.user" class="whitespace-nowrap text-base font-medium text-gray-500 hover:text-gray-900">-->
                        <!--                                                Logout-->
                        <!--                                            </a>-->

                        <!--                                            <a v-else :href="route('login')" class="ml-8 whitespace-nowrap inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700">-->
                        <!--                                                Sign in-->
                        <!--                                            </a>-->
                        <!--                                        </div>-->
                        <!--                                        <div>-->
                        <!--                                            <img class="h-8 w-auto" src="https://tailwindui.com/img/logos/workflow-mark-purple-600-to-indigo-600.svg" alt="Workflow" />-->
                        <!--                                        </div>-->
                        <!--                                        <div class="-mr-2">-->
                        <!--                                            <PopoverButton class="bg-white rounded-md p-2 inline-flex items-center justify-center text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500">-->
                        <!--                                                <span class="sr-only">Close menu</span>-->
                        <!--                                                <XIcon class="h-6 w-6" aria-hidden="true" />-->
                        <!--                                            </PopoverButton>-->
                        <!--                                        </div>-->
                        <!--                                    </div>-->
                        <!--                                    <div class="mt-6">-->
                        <!--                                        <nav class="grid grid-cols-1 gap-7">-->
                        <!--                                            <a v-for="item in solutions" :key="item.name" :href="item.href" class="-m-3 p-3 flex items-center rounded-lg hover:bg-gray-50">-->
                        <!--                                                <div class="flex-shrink-0 flex items-center justify-center h-10 w-10 rounded-md bg-gradient-to-r from-purple-600 to-indigo-600 text-white">-->
                        <!--                                                    <component :is="item.icon" class="h-6 w-6" aria-hidden="true" />-->
                        <!--                                                </div>-->
                        <!--                                                <div class="ml-4 text-base font-medium text-gray-900">-->
                        <!--                                                    {{ item.name }}-->
                        <!--                                                </div>-->
                        <!--                                            </a>-->
                        <!--                                        </nav>-->
                        <!--                                    </div>-->
                        <!--                                </div>-->
                        <!--                            </div>-->
                        <!--                        </PopoverPanel>-->
                        <!--                    </transition>-->
                    </Popover>
                    <!--          <pre>{{ user }}</pre>-->
                    <div v-if="user.type !== 'customer' && user.type !== 'prospective'">
                        <inertia-link type="button"
                                      class="relative inline-flex items-center px-4 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500"
                                      :href="route('customers')">
                            Customers
                        </inertia-link>
                        <inertia-link type="button"
                                      class="relative inline-flex items-center px-4 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500"
                                      :href="route('tasks')">
                            SCP List
<!--                            <span class="absolute top-0 right-0 transform translate-x-1/2 -translate-y-1/2 bg-red-500 text-xs font-semibold mr-2 px-2.5 py-0.5 rounded-full" id="notificationCount">3</span>-->
                        </inertia-link>
                        <inertia-link type="button"
                                      class="relative inline-flex items-center px-4 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500"
                                      :href="route('tasksNeedsApproval')">
                            Tasks
                        </inertia-link>
                        <inertia-link type="button"
                                      class="relative inline-flex items-center px-4 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500"
                                      :href="route('route')">
                            Route
                        </inertia-link>

                        <inertia-link type="button"
                                      class="relative inline-flex items-center px-4 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500"
                                      :href="route('chat')">
                            Chat
                        </inertia-link>

                        <inertia-link type="button"
                                      class="relative inline-flex items-center px-4 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500"
                                      :href="route('payments.currentPaycheck')">
                            Current Paycheck
                        </inertia-link>

                        <inertia-link type="button"
                                      v-if="user.is_admin"
                                      class="relative inline-flex items-center px-4 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500"
                                      :href="route('admin.links')">
                            Admin Links
                        </inertia-link>
                    </div>
                </header>
                <main class="flex-1 relative z-0 overflow-y-auto focus:outline-none"
                      :class="background ? background : ''"
                >
                    <div class="py-6">
                        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-12">
                            <h1 class="text-2xl font-semibold text-gray-900">{{ title }}</h1>
                        </div>

                        <TransitionRoot as="template" :show="open">
                            <Dialog as="div" class="relative z-10" @close="open = false">
                                <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0"
                                                 enter-to="opacity-100" leave="ease-in duration-200" leave-from="opacity-100"
                                                 leave-to="opacity-0">
                                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"/>
                                </TransitionChild>

                                <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                                    <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                                        <TransitionChild as="template" enter="ease-out duration-300"
                                                         enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                                         enter-to="opacity-100 translate-y-0 sm:scale-100" leave="ease-in duration-200"
                                                         leave-from="opacity-100 translate-y-0 sm:scale-100"
                                                         leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                                            <DialogPanel
                                                style="width: 100%;"
                                                class="absolute top-0"
                                            >
                                                <div>


                                                    <div class="mt-2">
                                                        <input @input="getCustomers($event.target.value)" type="text" name="text"
                                                               id="text"
                                                               style="padding: 1rem; margin-top: 1rem;"
                                                               class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm
                                               ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2
                                               focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                                               placeholder="type customer last name"/>
                                                    </div>

                                                    <div v-for="name in dbNames" :key="name.id">
                                                        <Link :href="route('customers.show', name.addressId)"
                                                              class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm
                                              ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2
                                              focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                                              style="padding: 1rem; background: white"
                                                        >
                                                            <div>
                                                                {{ name.first_name }} {{ name.last_name }}
                                                                <div>{{ name.address_line_1}}</div>
                                                                <div v-if="user.is_admin">{{ name.phone_number }}</div>
                                                            </div>
                                                        </Link>
                                                    </div>


                                                    <!--                                    <div-->
                                                    <!--                                        class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-green-100">-->
                                                    <!--                                        <CheckIcon class="h-6 w-6 text-green-600" aria-hidden="true"/>-->
                                                    <!--                                    </div>-->
                                                    <!--                                    <div class="mt-3 text-center sm:mt-5">-->
                                                    <!--                                        <DialogTitle as="h3" class="text-base font-semibold leading-6 text-gray-900">-->
                                                    <!--                                            Payment successful-->
                                                    <!--                                        </DialogTitle>-->
                                                    <!--                                        <div class="mt-2">-->
                                                    <!--                                            <p class="text-sm text-gray-500">Lorem ipsum dolor sit amet consectetur-->
                                                    <!--                                                adipisicing elit. Consequatur amet labore.</p>-->
                                                    <!--                                        </div>-->
                                                    <!--                                    </div>-->
                                                    <!--                                </div>-->
                                                    <!--                                <div class="mt-5 sm:mt-6">-->
                                                    <!--                                    <button type="button"-->
                                                    <!--                                            class="inline-flex w-full justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"-->
                                                    <!--                                            @click="open = false">Go back to dashboard-->
                                                    <!--                                    </button>-->
                                                </div>
                                            </DialogPanel>
                                        </TransitionChild>
                                    </div>
                                </div>
                            </Dialog>
                        </TransitionRoot>

                        <div class="flex justify-around" style="margin-bottom: 1rem">
                            <div class="flex" style="margin-bottom: 1rem">
                                <button @click="open = !open" type="button" class="flex items-center justify-center rounded-lg lg:ml-8" style="margin-right: 1rem;">
                                    <div>Search Customers</div>
                                    <svg class="h-6 w-6 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </button>
                            </div>

                            <Link v-if="addressId" :href="route('customers.show', addressId)"
                                  class="sticky top-0 z-10 border-y border-b-blue-500 border-t-blue-400 bg-blue-200 px-3 py-2 text-sm font-semibold leading-6 text-gray-900"
                                  method="get" as="button">
                                <label class="block text-sm font-medium leading-6 text-gray-900">Customer
                                    Page</label>
                            </Link>

                        </div>



                        <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
                            <slot></slot>
                        </div>
                    </div>
                </main>
            </div>
        </div>
    </div>
</template>

<script>
import {ref} from 'vue'
import {Popover, Dialog, DialogOverlay, DialogPanel, Transition, TransitionChild, TransitionRoot} from '@headlessui/vue'
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

const navigation = [
    {name: 'Dashboard', href: '#', icon: HomeIcon, current: true},
    {name: 'Team', href: '#', icon: UsersIcon, current: false},
    {name: 'Projects', href: '#', icon: FolderIcon, current: false},
    {name: 'Calendar', href: '#', icon: CalendarIcon, current: false},
    {name: 'Documents', href: '#', icon: InboxIcon, current: false},
    {name: 'Reports', href: '#', icon: ChartBarIcon, current: false},
]

export default {
    props: {
        title: String,
        addressId: Number,
        user: Object,
        background: String
    },
    components: {
        Popover,
        DialogPanel,
        Dialog,
        DialogOverlay,
        TransitionChild,
        TransitionRoot,
        Link,
    },
    data() {
      return {
          dbNames: [],
          open: false,
          csrfToken: null
      }
    },
    mounted() {
        this.csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    },
    methods: {

        showRoute() {},
        logout() {
            this.$inertia.post(route('logout'));
        },
        getCustomers(name) {

            console.debug(name)

            // Inertia.post('/customers/getNames', {'name': name})
            console.log(this.csrfToken); // Use this token in your component
            fetch('/customers/getNames', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json', // Specify the content type
                    'X-CSRF-TOKEN': this.csrfToken
                },
                // Include CSRF token
                body: JSON.stringify({'name': name})
            }).then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                // console.log(response.json()) // Parse the JSON in the response
                return response.json(); // Parse the JSON in the response
            })
                .then(data => {
                    console.log('Success:', data); // Handle the success case
                    this.dbNames = data
                })
                .catch((error) => {
                    console.error('Error:', error); // Handle errors
                });

        },
    },
    setup() {
        const sidebarOpen = ref(false)
        return {
            navigation,
            sidebarOpen
        }
    },
}
</script>

<style>

</style>

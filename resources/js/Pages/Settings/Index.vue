<template>
    <layout
        title="Settings"
        :user="user"
        :background="'bg-gray-900'"
    >

        <div>
            <TransitionRoot as="template" :show="sidebarOpen">
                <Dialog as="div" class="relative z-50 xl:hidden" @close="sidebarOpen = false">
                    <TransitionChild as="template" enter="transition-opacity ease-linear duration-300" enter-from="opacity-0" enter-to="opacity-100" leave="transition-opacity ease-linear duration-300" leave-from="opacity-100" leave-to="opacity-0">
                        <div class="fixed inset-0 bg-gray-900/80" />
                    </TransitionChild>

                    <div class="fixed inset-0 flex">
                        <TransitionChild as="template" enter="transition ease-in-out duration-300 transform" enter-from="-translate-x-full" enter-to="translate-x-0" leave="transition ease-in-out duration-300 transform" leave-from="translate-x-0" leave-to="-translate-x-full">
                            <DialogPanel class="relative mr-16 flex w-full max-w-xs flex-1">
                                <TransitionChild as="template" enter="ease-in-out duration-300" enter-from="opacity-0" enter-to="opacity-100" leave="ease-in-out duration-300" leave-from="opacity-100" leave-to="opacity-0">
                                    <div class="absolute left-full top-0 flex w-16 justify-center pt-5">
                                        <button type="button" class="-m-2.5 p-2.5" @click="sidebarOpen = false">
                                            <span class="sr-only">Close sidebar</span>
                                            <XMarkIcon class="h-6 w-6 text-white" aria-hidden="true" />
                                        </button>
                                    </div>
                                </TransitionChild>
                                <!-- Sidebar component, swap this element with another sidebar if you like -->
                                <div class="flex grow flex-col gap-y-5 overflow-y-auto bg-gray-900 px-6 ring-1 ring-white/10">
                                    <div class="flex h-16 shrink-0 items-center">
                                        <img class="h-8 w-auto" src="https://tailwindui.com/img/logos/mark.svg?color=indigo&shade=500" alt="Your Company" />
                                    </div>
                                    <nav class="flex flex-1 flex-col">
                                        <ul role="list" class="flex flex-1 flex-col gap-y-7">
                                            <li>
                                                <ul role="list" class="-mx-2 space-y-1">
                                                    <li v-for="item in navigation" :key="item.name">
                                                        <a :href="item.href" :class="[item.current ? 'bg-gray-800 text-white' : 'text-gray-400 hover:text-white hover:bg-gray-800', 'group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold']">
                                                            <component :is="item.icon" class="h-6 w-6 shrink-0" aria-hidden="true" />
                                                            {{ item.name }}
                                                        </a>
                                                    </li>
                                                </ul>
                                            </li>
                                            <li>
                                                <div class="text-xs font-semibold leading-6 text-gray-400">Your teams</div>
                                                <ul role="list" class="-mx-2 mt-2 space-y-1">
                                                    <li v-for="team in teams" :key="team.name">
                                                        <a :href="team.href" :class="[team.current ? 'bg-gray-800 text-white' : 'text-gray-400 hover:text-white hover:bg-gray-800', 'group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold']">
                                                            <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-lg border border-gray-700 bg-gray-800 text-[0.625rem] font-medium text-gray-400 group-hover:text-white">{{ team.initial }}</span>
                                                            <span class="truncate">{{ team.name }}</span>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </li>
                                            <li class="-mx-6 mt-auto">
                                                <a href="#" class="flex items-center gap-x-4 px-6 py-3 text-sm font-semibold leading-6 text-white hover:bg-gray-800">
                                                    <img class="h-8 w-8 rounded-full bg-gray-800" src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="" />
                                                    <span class="sr-only">Your profile</span>
                                                    <span aria-hidden="true">Tom Cook</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </nav>
                                </div>
                            </DialogPanel>
                        </TransitionChild>
                    </div>
                </Dialog>
            </TransitionRoot>

            <!-- Static sidebar for desktop -->
            <div class="hidden xl:fixed xl:inset-y-0 xl:z-50 xl:flex xl:w-72 xl:flex-col">
                <!-- Sidebar component, swap this element with another sidebar if you like -->
                <div class="flex grow flex-col gap-y-5 overflow-y-auto bg-black/10 px-6 ring-1 ring-white/5">
                    <div class="flex h-16 shrink-0 items-center">
                        <img class="h-8 w-auto" src="https://tailwindui.com/img/logos/mark.svg?color=indigo&shade=500" alt="Your Company" />
                    </div>
                    <nav class="flex flex-1 flex-col">
                        <ul role="list" class="flex flex-1 flex-col gap-y-7">
                            <li>
                                <ul role="list" class="-mx-2 space-y-1">
                                    <li v-for="item in navigation" :key="item.name">
                                        <a :href="item.href" :class="[item.current ? 'bg-gray-800 text-white' : 'text-gray-400 hover:text-white hover:bg-gray-800', 'group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold']">
                                            <component :is="item.icon" class="h-6 w-6 shrink-0" aria-hidden="true" />
                                            {{ item.name }}
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <div class="text-xs font-semibold leading-6 text-gray-400">Your teams</div>
                                <ul role="list" class="-mx-2 mt-2 space-y-1">
                                    <li v-for="team in teams" :key="team.name">
                                        <a :href="team.href" :class="[team.current ? 'bg-gray-800 text-white' : 'text-gray-400 hover:text-white hover:bg-gray-800', 'group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold']">
                                            <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-lg border border-gray-700 bg-gray-800 text-[0.625rem] font-medium text-gray-400 group-hover:text-white">{{ team.initial }}</span>
                                            <span class="truncate">{{ team.name }}</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="-mx-6 mt-auto">
                                <a href="#" class="flex items-center gap-x-4 px-6 py-3 text-sm font-semibold leading-6 text-white hover:bg-gray-800">
                                    <img class="h-8 w-8 rounded-full bg-gray-800" src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="" />
                                    <span class="sr-only">Your profile</span>
                                    <span aria-hidden="true">Tom Cook</span>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>

            <div class="xl:pl-72">
                <!-- Sticky search header -->
                <div class="sticky top-0 z-40 flex h-16 shrink-0 items-center gap-x-6 border-b border-white/5 bg-gray-900 px-4 shadow-sm sm:px-6 lg:px-8">
                    <button type="button" class="-m-2.5 p-2.5 text-white xl:hidden" @click="sidebarOpen = true">
                        <span class="sr-only">Open sidebar</span>
                        <Bars3Icon class="h-5 w-5" aria-hidden="true" />
                    </button>

                    <div class="flex flex-1 gap-x-4 self-stretch lg:gap-x-6">
                        <form class="flex flex-1" action="#" method="GET">
                            <label for="search-field" class="sr-only">Search</label>
                            <div class="relative w-full">
                                <MagnifyingGlassIcon class="pointer-events-none absolute inset-y-0 left-0 h-full w-5 text-gray-500" aria-hidden="true" />
                                <input id="search-field" class="block h-full w-full border-0 bg-transparent py-0 pl-8 pr-0 text-white focus:ring-0 sm:text-sm" placeholder="Search..." type="search" name="search" />
                            </div>
                        </form>
                    </div>
                </div>

                <main>
                    <header class="border-b border-white/5">
                        <!-- Secondary navigation -->
                        <nav class="flex overflow-x-auto py-4">
                            <ul role="list" class="flex min-w-full flex-none gap-x-6 px-4 text-sm font-semibold leading-6 text-gray-400 sm:px-6 lg:px-8">
                                <li v-for="item in secondaryNavigation" :key="item.name">
                                    <a :href="item.href" :class="item.current ? 'text-indigo-400' : ''">{{ item.name }}</a>
                                </li>
                            </ul>
                        </nav>
                    </header>

                    <!-- Settings forms -->
                    <div class="divide-y divide-white/5">
                        <div class="grid max-w-7xl grid-cols-1 gap-x-8 gap-y-10 px-4 py-16 sm:px-6 md:grid-cols-3 lg:px-8">
                            <div>
                                <h2 class="text-base font-semibold leading-7 text-white">Personal Information</h2>
                                <p class="mt-1 text-sm leading-6 text-gray-400">Use a permanent address where you can receive mail.</p>
                            </div>

                            <form class="md:col-span-2">
                                <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:max-w-xl sm:grid-cols-6">
                                    <div class="col-span-full flex items-center gap-x-8">
                                        <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="" class="h-24 w-24 flex-none rounded-lg bg-gray-800 object-cover" />
                                        <div>
                                            <button type="button" class="rounded-md bg-white/10 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-white/20">Change avatar</button>
                                            <p class="mt-2 text-xs leading-5 text-gray-400">JPG, GIF or PNG. 1MB max.</p>
                                        </div>
                                    </div>

                                    <div class="sm:col-span-3">
                                        <label for="first-name" class="block text-sm font-medium leading-6 text-white">First name</label>
                                        <div class="mt-2">
                                            <input type="text" name="first-name" id="first-name" autocomplete="given-name" class="block w-full rounded-md border-0 bg-white/5 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6" />
                                        </div>
                                    </div>

                                    <div class="sm:col-span-3">
                                        <label for="last-name" class="block text-sm font-medium leading-6 text-white">Last name</label>
                                        <div class="mt-2">
                                            <input type="text" name="last-name" id="last-name" autocomplete="family-name" class="block w-full rounded-md border-0 bg-white/5 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6" />
                                        </div>
                                    </div>

                                    <div class="col-span-full">
                                        <label for="email" class="block text-sm font-medium leading-6 text-white">Email address</label>
                                        <div class="mt-2">
                                            <input id="email" name="email" type="email" autocomplete="email" class="block w-full rounded-md border-0 bg-white/5 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6" />
                                        </div>
                                    </div>

                                    <div class="col-span-full">
                                        <label for="username" class="block text-sm font-medium leading-6 text-white">Username</label>
                                        <div class="mt-2">
                                            <div class="flex rounded-md bg-white/5 ring-1 ring-inset ring-white/10 focus-within:ring-2 focus-within:ring-inset focus-within:ring-indigo-500">
                                                <span class="flex select-none items-center pl-3 text-gray-400 sm:text-sm">example.com/</span>
                                                <input type="text" name="username" id="username" autocomplete="username" class="flex-1 border-0 bg-transparent py-1.5 pl-1 text-white focus:ring-0 sm:text-sm sm:leading-6" placeholder="janesmith" />
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-span-full">
                                        <label for="timezone" class="block text-sm font-medium leading-6 text-white">Timezone</label>
                                        <div class="mt-2">
                                            <select id="timezone" name="timezone" class="block w-full rounded-md border-0 bg-white/5 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6 [&_*]:text-black">
                                                <option>Pacific Standard Time</option>
                                                <option>Eastern Standard Time</option>
                                                <option>Greenwich Mean Time</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-8 flex">
                                    <button type="submit" class="rounded-md bg-indigo-500 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-400 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500">Save</button>
                                </div>
                            </form>
                        </div>

                        <div class="grid max-w-7xl grid-cols-1 gap-x-8 gap-y-10 px-4 py-16 sm:px-6 md:grid-cols-3 lg:px-8">
                            <div>
                                <h2 class="text-base font-semibold leading-7 text-white">Change password</h2>
                                <p class="mt-1 text-sm leading-6 text-gray-400">Update your password associated with your account.</p>
                            </div>

                            <form class="md:col-span-2">
                                <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:max-w-xl sm:grid-cols-6">
                                    <div class="col-span-full">
                                        <label for="current-password" class="block text-sm font-medium leading-6 text-white">Current password</label>
                                        <div class="mt-2">
                                            <input id="current-password" name="current_password" type="password" autocomplete="current-password" class="block w-full rounded-md border-0 bg-white/5 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6" />
                                        </div>
                                    </div>

                                    <div class="col-span-full">
                                        <label for="new-password" class="block text-sm font-medium leading-6 text-white">New password</label>
                                        <div class="mt-2">
                                            <input id="new-password" name="new_password" type="password" autocomplete="new-password" class="block w-full rounded-md border-0 bg-white/5 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6" />
                                        </div>
                                    </div>

                                    <div class="col-span-full">
                                        <label for="confirm-password" class="block text-sm font-medium leading-6 text-white">Confirm password</label>
                                        <div class="mt-2">
                                            <input id="confirm-password" name="confirm_password" type="password" autocomplete="new-password" class="block w-full rounded-md border-0 bg-white/5 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6" />
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-8 flex">
                                    <button type="submit" class="rounded-md bg-indigo-500 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-400 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500">Save</button>
                                </div>
                            </form>
                        </div>

                        <div class="grid max-w-7xl grid-cols-1 gap-x-8 gap-y-10 px-4 py-16 sm:px-6 md:grid-cols-3 lg:px-8">
                            <div>
                                <h2 class="text-base font-semibold leading-7 text-white">Log out other sessions</h2>
                                <p class="mt-1 text-sm leading-6 text-gray-400">Please enter your password to confirm you would like to log out of your other sessions across all of your devices.</p>
                            </div>

                            <form class="md:col-span-2">
                                <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:max-w-xl sm:grid-cols-6">
                                    <div class="col-span-full">
                                        <label for="logout-password" class="block text-sm font-medium leading-6 text-white">Your password</label>
                                        <div class="mt-2">
                                            <input id="logout-password" name="password" type="password" autocomplete="current-password" class="block w-full rounded-md border-0 bg-white/5 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6" />
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-8 flex">
                                    <button type="submit" class="rounded-md bg-indigo-500 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-400 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500">Log out other sessions</button>
                                </div>
                            </form>
                        </div>

                        <div class="grid max-w-7xl grid-cols-1 gap-x-8 gap-y-10 px-4 py-16 sm:px-6 md:grid-cols-3 lg:px-8">
                            <div>
                                <h2 class="text-base font-semibold leading-7 text-white">Delete account</h2>
                                <p class="mt-1 text-sm leading-6 text-gray-400">No longer want to use our service? You can delete your account here. This action is not reversible. All information related to this account will be deleted permanently.</p>
                            </div>

                            <form class="flex items-start md:col-span-2">
                                <button type="submit" class="rounded-md bg-red-500 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-400">Yes, delete my account</button>
                            </form>
                        </div>


                        <div id="Billing">

                            <h2>Billing</h2>
                            <a @click="changeBilling()" class="
                            text-indigo-600 ring-1 ring-inset ring-indigo-200 hover:ring-indigo-300
                      mt-6 block rounded-md py-2 px-3 text-center text-sm
                       font-semibold leading-6 focus-visible:outline focus-visible:outline-2
                        focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Billing</a>

                        </div>


                    </div>
                </main>
            </div>
        </div>


    </layout>
</template>

<script setup>

import { ref } from 'vue'
import { Dialog, DialogPanel, TransitionChild, TransitionRoot } from '@headlessui/vue'
import {
    ChartBarSquareIcon,
    Cog6ToothIcon,
    FolderIcon,
    GlobeAltIcon,
    ServerIcon,
    SignalIcon,
    XMarkIcon,
} from '@heroicons/vue/24/outline'
import { Bars3Icon, MagnifyingGlassIcon } from '@heroicons/vue/20/solid'

const navigation = [
    { name: 'Projects', href: '#', icon: FolderIcon, current: false },
    { name: 'Deployments', href: '#', icon: ServerIcon, current: false },
    { name: 'Activity', href: '#', icon: SignalIcon, current: false },
    { name: 'Domains', href: '#', icon: GlobeAltIcon, current: false },
    { name: 'Usage', href: '#', icon: ChartBarSquareIcon, current: false },
    { name: 'Settings', href: '#', icon: Cog6ToothIcon, current: true },
]
const teams = [
    { id: 1, name: 'Planetaria', href: '#', initial: 'P', current: false },
    { id: 2, name: 'Protocol', href: '#', initial: 'P', current: false },
    { id: 3, name: 'Tailwind Labs', href: '#', initial: 'T', current: false },
]
const secondaryNavigation = [
    { name: 'Account', href: '#', current: true },
    { name: 'Notifications', href: '#', current: false },
    { name: 'Billing', href: '#Billing', current: false },
    { name: 'Teams', href: '#', current: false },
    { name: 'Integrations', href: '#', current: false },
]

const sidebarOpen = ref(false)

</script>

<script>
import JetInput from '@/Jetstream/Input.vue'
import SimpleTable from "../Shared/SimpleTable.vue";
import Layout from "../Shared/Layout.vue";
import {Link} from '@inertiajs/inertia-vue3';
import {Inertia} from '@inertiajs/inertia';


export default {
    name: 'Settings',
    components: {

    },
    data() {
        return {}
    },
    mounted() {},
    methods: {
        changeBilling() {
            Inertia.get('/billing')
        }
    },
    props: {
        user: Object
    }
}
</script>

<style scoped>


.completed {
    background-color: #9bcd9b;
}

.notCompleted {
    background-color: #de7777;
}

</style>

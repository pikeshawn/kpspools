<template>
    <layout
        title="Dashboard"
        :user="user"
    >
        <div class="bg-white">
            <header class="relative bg-white">
                <div class="flex items-center justify-between">
                    <!-- Flyout menus -->
                    <!--                    <PopoverGroup class="absolute inset-x-0 bottom-0 sm:static sm:flex-1 sm:self-stretch">-->
                    <!--                        <Popover v-for="(category, categoryIdx) in navigation.categories" :key="categoryIdx"-->
                    <!--                                 class="flex">-->
                    <!--                            <div class="relative flex">-->
                    <!--                            </div>-->

                    <!--                            <transition enter-active-class="transition ease-out duration-200"-->
                    <!--                                        enter-from-class="opacity-0" enter-to-class="opacity-100"-->
                    <!--                                        leave-active-class="transition ease-in duration-150"-->
                    <!--                                        leave-from-class="opacity-100" leave-to-class="opacity-0">-->
                    <!--                                <PopoverPanel class="absolute inset-x-0 top-full text-gray-500 sm:text-sm">-->
                    <!--                                    &lt;!&ndash; Presentational element used to render the bottom shadow, if we put the shadow on the actual panel it pokes out the top, so we use this shorter element to hide the top of the shadow &ndash;&gt;-->
                    <!--                                    <div class="relative bg-white">-->
                    <!--                                        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">-->
                    <!--                                            <div-->
                    <!--                                                class="grid grid-cols-1 items-start gap-x-6 gap-y-10 pb-12 pt-10 md:grid-cols-2 lg:gap-x-8">-->
                    <!--                                                <div class="grid grid-cols-1 gap-x-6 gap-y-10 lg:gap-x-8">-->
                    <!--                                                    <div>-->
                    <!--                                                        <p id="clothing-heading"-->
                    <!--                                                           class="font-medium text-gray-900">Clothing</p>-->
                    <!--                                                        <div-->
                    <!--                                                            class="mt-4 border-t border-gray-200 pt-6 sm:grid sm:grid-cols-2 sm:gap-x-6">-->
                    <!--                                                            <ul role="list" aria-labelledby="clothing-heading"-->
                    <!--                                                                class="space-y-6 sm:space-y-4">-->
                    <!--                                                                <li v-for="item in category.clothing[0]"-->
                    <!--                                                                    :key="item.name" class="flex">-->
                    <!--                                                                    <a :href="item.href"-->
                    <!--                                                                       class="hover:text-gray-800">{{-->
                    <!--                                                                            item.name-->
                    <!--                                                                        }}</a>-->
                    <!--                                                                </li>-->
                    <!--                                                            </ul>-->
                    <!--                                                            <ul role="list" aria-label="More clothing"-->
                    <!--                                                                class="mt-6 space-y-6 sm:mt-0 sm:space-y-4">-->
                    <!--                                                                <li v-for="item in category.clothing[1]"-->
                    <!--                                                                    :key="item.name" class="flex">-->
                    <!--                                                                    <a :href="item.href"-->
                    <!--                                                                       class="hover:text-gray-800">{{-->
                    <!--                                                                            item.name-->
                    <!--                                                                        }}</a>-->
                    <!--                                                                </li>-->
                    <!--                                                            </ul>-->
                    <!--                                                        </div>-->
                    <!--                                                    </div>-->
                    <!--                                                </div>-->
                    <!--                                                <div-->
                    <!--                                                    class="grid grid-cols-1 gap-x-6 gap-y-10 sm:grid-cols-2 lg:gap-x-8">-->
                    <!--                                                    <div>-->
                    <!--                                                        <p id="accessories-heading"-->
                    <!--                                                           class="font-medium text-gray-900">Accessories</p>-->
                    <!--                                                        <ul role="list" aria-labelledby="accessories-heading"-->
                    <!--                                                            class="mt-4 space-y-6 border-t border-gray-200 pt-6 sm:space-y-4">-->
                    <!--                                                            <li v-for="item in category.accessories"-->
                    <!--                                                                :key="item.name" class="flex">-->
                    <!--                                                                <a :href="item.href"-->
                    <!--                                                                   class="hover:text-gray-800">{{-->
                    <!--                                                                        item.name-->
                    <!--                                                                    }}</a>-->
                    <!--                                                            </li>-->
                    <!--                                                        </ul>-->
                    <!--                                                    </div>-->
                    <!--                                                    <div>-->
                    <!--                                                        <p id="categories-heading"-->
                    <!--                                                           class="font-medium text-gray-900">Categories</p>-->
                    <!--                                                        <ul role="list" aria-labelledby="categories-heading"-->
                    <!--                                                            class="mt-4 space-y-6 border-t border-gray-200 pt-6 sm:space-y-4">-->
                    <!--                                                            <li v-for="item in category.categories"-->
                    <!--                                                                :key="item.name" class="flex">-->
                    <!--                                                                <a :href="item.href"-->
                    <!--                                                                   class="hover:text-gray-800">{{-->
                    <!--                                                                        item.name-->
                    <!--                                                                    }}</a>-->
                    <!--                                                            </li>-->
                    <!--                                                        </ul>-->
                    <!--                                                    </div>-->
                    <!--                                                </div>-->
                    <!--                                            </div>-->
                    <!--                                        </div>-->
                    <!--                                    </div>-->
                    <!--                                </PopoverPanel>-->
                    <!--                            </transition>-->
                    <!--                        </Popover>-->
                    <!--                    </PopoverGroup>-->
                </div>
            </header>
        </div>

    </layout>
</template>

<script>
// import AppLayout from '@/Layouts/AppLayout'
// import Welcome from '@/Jetstream/Welcome'
import Layout from "./Shared/Layout";
import {Popover, PopoverButton, PopoverGroup, PopoverPanel} from '@headlessui/vue'

export default {
    components: {
        Layout
    },
    props: {
        user: Object
    },
    data() {
        return {
            navigation: {
                categories: [
                    {
                        name: 'Men',
                        clothing: [
                            [
                                {name: 'Dress Shirts', href: '#'},
                                {name: 'Pants', href: '#'},
                                {name: 'Jackets', href: '#'},
                                {name: 'T-Shirts', href: '#'},
                                {name: 'Jeans', href: '#'},
                                {name: 'Hoodies', href: '#'},
                            ],
                            [
                                {name: 'Vests', href: '#'},
                                {name: 'Kilts', href: '#'},
                                {name: 'Outdoors', href: '#'},
                                {name: 'Capes', href: '#'},
                                {name: 'Browse All', href: '#'},
                            ],
                        ],
                        accessories: [
                            {name: 'Watches', href: '#'},
                            {name: 'Boots', href: '#'},
                            {name: 'Fanny Packs', href: '#'},
                            {name: 'Sunglasses', href: '#'},
                            {name: 'Browse All', href: '#'},
                        ],
                        categories: [
                            {name: 'Just Added', href: '#'},
                            {name: 'Clearance', href: '#'},
                            {name: 'Graphic Tees', href: '#'},
                        ],
                    },
                ],
                other: [
                    {name: 'Company', href: '#'},
                    {name: 'Stores', href: '#'},
                ]
            }
        }
    }
}
</script>

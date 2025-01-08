<template>
    <layout
        :user="user"
    >

        <!--        Section for Admin users  -->


        <div v-if="user.is_admin === 1">
            <div>
                <div class="sm:hidden">
                    <div class="flex justify-between">
                        <div>Created: {{ createdObjects }}</div>
                        <div>Approved: {{ approvedObjects }}</div>
                        <div>Denied: {{ deniedObjects }}</div>
                        <div>DIY: {{ diydObjects }}</div>
                    </div>
                    <label for="tabs" class="sr-only">Select a tab</label>
                    <!-- Use an "onChange" listener to redirect the user to the selected tab URL. -->
                    <select id="tabs" name="tabs"
                            v-model="myTab"
                            @change="changeSmallTab()"
                            class="block w-full rounded-md border-gray-300 py-2 pl-3 pr-10 text-base focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm">
                        <option v-for="tab in tabs" :key="tab.name" :selected="tab.current">
                            {{ tab.name }}
                        </option>
                    </select>
                </div>
                <div class="hidden sm:block">
                    <div class="border-b border-gray-200">
                        <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                            <a v-for="tab in tabs"
                               :key="tab.name"
                               :href="tab.href"
                               :data-item="tab"
                               @click="changeTab(tab)"
                               :class="[tab.current ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700', 'whitespace-nowrap border-b-2 py-4 px-1 text-sm font-medium']"
                               :aria-current="tab.current ? 'page' : undefined">
                                {{ tab.name }}
                                <span
                                    class="inline-flex items-center rounded-md bg-pink-400/10 px-2 py-1 text-xs font-medium text-pink-400 ring-1 ring-inset ring-pink-400/20">{{
                                        tab.count
                                    }}</span>
                            </a>
                        </nav>
                    </div>
                </div>
            </div>

            <div v-show="showTab === 'Created'">
                <!--            <div id="created">-->
                <h1 class="py-3">Created</h1>
                <ul role="list" class="">
                    <li v-for="item in tasks" :key="item.id" class="py-2">
                        <!-- Your content -->
                        <div v-if="item.status === 'created'">
                            <div class="border-b border-gray-200 bg-white px-4 py-5 sm:px-6">
                                <div class="flex justify-between">
                                    <div class="flex" style="flex-direction: column;">
                                        <h3 class="text-base font-semibold leading-6 text-gray-900">
                                            {{ item.first_name }} {{ item.last_name }}
                                        </h3>
                                        <label class="block text-sm font-medium leading-6 text-gray-900">
                                            {{ item.phone_number }}
                                        </label>
                                    </div>
                                    <Link :href="route('customers.show', item.address_id)"
                                          class="sticky top-0 z-10 border-y border-b-blue-500 border-t-blue-400 bg-blue-200 px-3 py-2 text-sm font-semibold leading-6 text-gray-900"
                                          method="get" as="button">
                                        <label class="block text-sm font-medium leading-6 text-gray-900">Customer
                                            Page</label>
                                    </Link>
                                    <h3 class="text-base font-semibold leading-6 text-gray-900">{{ item.name }}</h3>
                                    <h3 class="text-base font-semibold leading-6 text-gray-900">{{ item.status }}</h3>
                                </div>
                                <div class="py-2">
                                    <label for="description" class="block text-sm font-medium leading-6 text-gray-900">Description</label>
                                    <div class="mt-2">
                                        <input type="text" name="description" id="description"
                                               @blur="changeDescription(item)"
                                               class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1
                                       ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset
                                       focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                               v-model="item.description"/>
                                    </div>
                                    <div class="mt-2">
                                        <label for="scp_id" class="block text-sm font-medium leading-6 text-gray-900">Product
                                            Number</label>
                                        <div class="mt-2">
                                            <input type="text" name="scp_id" id="scp_id"
                                                   @blur="changeProductNumber(item)"
                                                   class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1
                                       ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset
                                       focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                                   v-model="item.scp_id"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="py-2">
                                    <div class="flex justify-between">
                                        <label for="price"
                                               class="block text-sm font-medium leading-6 text-gray-900">Price</label>
                                        <label for="price" class="block text-sm font-medium leading-6 text-gray-900">${{
                                                item.price
                                            }}</label>
                                    </div>
                                    <div class="mt-2">
                                        <input type="text" name="price" id="price"
                                               @blur="updatePrice(item)"
                                               class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1
                                       ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset
                                       focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                               v-model="item.price"/>
                                    </div>
                                </div>

                                <div class="flex justify-between">
                                    <div
                                        class="py-2 pr-2 w-full">
                                        <label for="serviceman" class="block text-sm font-medium text-gray-700">Assigned
                                            Serviceman</label>
                                        <select id="serviceman" name="serviceman"
                                                v-model='item.assigned'
                                                @change="assignServiceman(item)"
                                                class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                            <option
                                                v-for="option in servicemen" :value="option.name">
                                                {{ option.name }}
                                            </option>
                                        </select>
                                    </div>

                                    <div
                                        class="py-2 pl-2 w-full">
                                        <label for="status" class="block text-sm font-medium text-gray-700">Change
                                            Status</label>
                                        <select id="status" name="status"
                                                v-model='item.status'
                                                @change="changeStatus(item)"
                                                class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                            <option
                                                v-for="option in status" :value="option.name">
                                                {{ option.name }}
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="flex items-center justify-between">
                                    <div>
                                        <label v-if="item.sent" for="price"
                                               class="block text-sm font-medium leading-6 text-green-800">Approval
                                            Sent</label>
                                        <button @click="requestApproval(item)"
                                                class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm
                                text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700
                                focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                            Request Approval
                                        </button>
                                    </div>

                                    <!--                            <div v-if="item.sent"><span class="-->
                                    <!--                            mt-4 text-sm rounded-md py-2 px-4 font-medium bg-green-800 text-white-->
                                    <!--                            ">Approval Sent</span></div>-->

                                    <button @click="deleteItem(item)"
                                            class="mt-4 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm
                                text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700
                                focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                        Delete
                                    </button>
                                </div>
                            </div>
                            <div v-if="item.deleted" class="rounded-md bg-red-50 p-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <XCircleIcon class="h-5 w-5 text-red-400" aria-hidden="true"/>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-red-800">You are about to delete this item.
                                            Do you
                                            wish to continue</h3>
                                        <div class="flex justify-around mt-2 text-sm text-red-700">
                                            <button @click="doTheDeletion(item)"
                                                    class="mt-4 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm
                                text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700
                                focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                                Yes
                                            </button>
                                            <button @click="cancelDeletion(item)"
                                                    class="mt-4 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm
                                text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700
                                focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                No
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
            <div v-show="showTab === 'Approval'">
                <!--            <div id="approval">-->
                <h1 class="py-3">Approval</h1>
                <ul role="list" class="">
                    <li v-for="item in tasks" :key="item.id" class="py-2">
                        <!-- Your content -->

                        <div v-if="item.status === 'approved'">
                            <div class="border-b border-gray-200 bg-white px-4 py-5 sm:px-6">
                                <div class="flex justify-between">
                                    <div class="flex" style="flex-direction: column;">
                                        <h3 class="text-base font-semibold leading-6 text-gray-900">
                                            {{ item.first_name }} {{ item.last_name }}
                                        </h3>
                                        <label class="block text-sm font-medium leading-6 text-gray-900">
                                            {{ item.phone_number }}
                                        </label>
                                    </div>
                                    <Link :href="route('customers.show', item.address_id)"
                                          class="sticky top-0 z-10 border-y border-b-blue-500 border-t-blue-400 bg-blue-200 px-3 py-2 text-sm font-semibold leading-6 text-gray-900"
                                          method="get" as="button">
                                        <label class="block text-sm font-medium leading-6 text-gray-900">Customer
                                            Page</label>
                                    </Link>
                                    <h3 class="text-base font-semibold leading-6 text-gray-900">{{ item.name }}</h3>
                                    <h3 class="text-base font-semibold leading-6 text-gray-900">{{ item.status }}</h3>
                                </div>
                                <div class="py-2">
                                    <label for="description" class="block text-sm font-medium leading-6 text-gray-900">Description</label>
                                    <div class="mt-2">
                                        <input type="text" name="description" id="description"
                                               @blur="changeDescription(item)"
                                               class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1
                                       ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset
                                       focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                               v-model="item.description"/>
                                    </div>
                                </div>
                                <div class="py-2">
                                    <div class="flex justify-between">
                                        <label for="price"
                                               class="block text-sm font-medium leading-6 text-gray-900">Price</label>
                                        <label for="price" class="block text-sm font-medium leading-6 text-gray-900">${{
                                                item.price
                                            }}</label>
                                    </div>
                                    <div class="mt-2">
                                        <input type="text" name="price" id="price"
                                               @blur="updatePrice(item)"
                                               class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1
                                       ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset
                                       focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                               v-model="item.price"/>
                                    </div>
                                </div>

                                <div class="flex justify-between">
                                    <div
                                        class="py-2 pr-2 w-full">
                                        <label for="serviceman" class="block text-sm font-medium text-gray-700">Assigned
                                            Serviceman</label>
                                        <select id="serviceman" name="serviceman"
                                                v-model='item.assigned'
                                                @change="assignServiceman(item)"
                                                class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                            <option
                                                v-for="option in servicemen" :value="option.name">
                                                {{ option.name }}
                                            </option>
                                        </select>
                                    </div>

                                    <div
                                        class="py-2 pl-2 w-full">
                                        <label for="status" class="block text-sm font-medium text-gray-700">Change
                                            Status</label>
                                        <select id="status" name="status"
                                                v-model='item.status'
                                                @change="changeStatus(item)"
                                                class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                            <option
                                                v-for="option in status" :value="option.name">
                                                {{ option.name }}
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="flex items-center justify-between">
                                    <div>
                                        <label v-if="item.sent" for="price"
                                               class="block text-sm font-medium leading-6 text-green-800">Approval
                                            Sent</label>
                                        <button @click="requestApproval(item)"
                                                class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm
                                text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700
                                focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                            Request Approval
                                        </button>
                                    </div>

                                    <!--                            <div v-if="item.sent"><span class="-->
                                    <!--                            mt-4 text-sm rounded-md py-2 px-4 font-medium bg-green-800 text-white-->
                                    <!--                            ">Approval Sent</span></div>-->

                                    <button @click="deleteItem(item)"
                                            class="mt-4 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm
                                text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700
                                focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                        Delete
                                    </button>
                                </div>
                            </div>
                            <div v-if="item.deleted" class="rounded-md bg-red-50 p-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <XCircleIcon class="h-5 w-5 text-red-400" aria-hidden="true"/>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-red-800">You are about to delete this item.
                                            Do you
                                            wish to continue</h3>
                                        <div class="flex justify-around mt-2 text-sm text-red-700">
                                            <button @click="doTheDeletion(item)"
                                                    class="mt-4 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm
                                text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700
                                focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                                Yes
                                            </button>
                                            <button @click="cancelDeletion(item)"
                                                    class="mt-4 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm
                                text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700
                                focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                No
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </li>
                </ul>
            </div>
            <div v-show="showTab === 'Denied'">
                <!--            <div id="denied">-->
                <h1 class="py-3">Denied</h1>
                <ul role="list" class="">
                    <li v-for="item in tasks" :key="item.id" class="py-2">
                        <!-- Your content -->

                        <div v-if="item.status === 'denied'">
                            <div class="border-b border-gray-200 bg-white px-4 py-5 sm:px-6">
                                <div class="flex justify-between">
                                    <div class="flex" style="flex-direction: column;">
                                        <h3 class="text-base font-semibold leading-6 text-gray-900">
                                            {{ item.first_name }} {{ item.last_name }}
                                        </h3>
                                        <label class="block text-sm font-medium leading-6 text-gray-900">
                                            {{ item.phone_number }}
                                        </label>
                                    </div>
                                    <Link :href="route('customers.show', item.address_id)"
                                          class="sticky top-0 z-10 border-y border-b-blue-500 border-t-blue-400 bg-blue-200 px-3 py-2 text-sm font-semibold leading-6 text-gray-900"
                                          method="get" as="button">
                                        <label class="block text-sm font-medium leading-6 text-gray-900">Customer
                                            Page</label>
                                    </Link>
                                    <h3 class="text-base font-semibold leading-6 text-gray-900">{{ item.name }}</h3>
                                    <h3 class="text-base font-semibold leading-6 text-gray-900">{{ item.status }}</h3>
                                </div>
                                <div class="py-2">
                                    <label for="description" class="block text-sm font-medium leading-6 text-gray-900">Description</label>
                                    <div class="mt-2">
                                        <input type="text" name="description" id="description"
                                               @blur="changeDescription(item)"
                                               class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1
                                       ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset
                                       focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                               v-model="item.description"/>
                                    </div>
                                </div>
                                <div class="py-2">
                                    <div class="flex justify-between">
                                        <label for="price"
                                               class="block text-sm font-medium leading-6 text-gray-900">Price</label>
                                        <label for="price" class="block text-sm font-medium leading-6 text-gray-900">${{
                                                item.price
                                            }}</label>
                                    </div>
                                    <div class="mt-2">
                                        <input type="text" name="price" id="price"
                                               @blur="updatePrice(item)"
                                               class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1
                                       ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset
                                       focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                               v-model="item.price"/>
                                    </div>
                                </div>

                                <div class="flex justify-between">
                                    <div
                                        class="py-2 pr-2 w-full">
                                        <label for="serviceman" class="block text-sm font-medium text-gray-700">Assigned
                                            Serviceman</label>
                                        <select id="serviceman" name="serviceman"
                                                v-model='item.assigned'
                                                @change="assignServiceman(item)"
                                                class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                            <option
                                                v-for="option in servicemen" :value="option.name">
                                                {{ option.name }}
                                            </option>
                                        </select>
                                    </div>

                                    <div
                                        class="py-2 pl-2 w-full">
                                        <label for="status" class="block text-sm font-medium text-gray-700">Change
                                            Status</label>
                                        <select id="status" name="status"
                                                v-model='item.status'
                                                @change="changeStatus(item)"
                                                class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                            <option
                                                v-for="option in status" :value="option.name">
                                                {{ option.name }}
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="flex items-center justify-between">
                                    <div>
                                        <label v-if="item.sent" for="price"
                                               class="block text-sm font-medium leading-6 text-green-800">Approval
                                            Sent</label>
                                        <button @click="requestApproval(item)"
                                                class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm
                                text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700
                                focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                            Request Approval
                                        </button>
                                    </div>

                                    <!--                            <div v-if="item.sent"><span class="-->
                                    <!--                            mt-4 text-sm rounded-md py-2 px-4 font-medium bg-green-800 text-white-->
                                    <!--                            ">Approval Sent</span></div>-->

                                    <button @click="deleteItem(item)"
                                            class="mt-4 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm
                                text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700
                                focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                        Delete
                                    </button>
                                </div>
                            </div>
                            <div v-if="item.deleted" class="rounded-md bg-red-50 p-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <XCircleIcon class="h-5 w-5 text-red-400" aria-hidden="true"/>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-red-800">You are about to delete this item.
                                            Do you
                                            wish to continue</h3>
                                        <div class="flex justify-around mt-2 text-sm text-red-700">
                                            <button @click="doTheDeletion(item)"
                                                    class="mt-4 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm
                                text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700
                                focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                                Yes
                                            </button>
                                            <button @click="cancelDeletion(item)"
                                                    class="mt-4 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm
                                text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700
                                focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                No
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </li>
                </ul>
            </div>
            <div v-show="showTab === 'DIY'">
                <!--            <div id="diy">-->
                <h1 class="py-3">DIY</h1>
                <ul role="list" class="">
                    <li v-for="item in tasks" :key="item.id" class="py-2">
                        <!-- Your content -->
                        <div v-if="item.status === 'diy'">
                            <div class="border-b border-gray-200 bg-white px-4 py-5 sm:px-6">
                                <div class="flex justify-between">
                                    <div class="flex" style="flex-direction: column;">
                                        <h3 class="text-base font-semibold leading-6 text-gray-900">
                                            {{ item.first_name }} {{ item.last_name }}
                                        </h3>
                                        <label class="block text-sm font-medium leading-6 text-gray-900">
                                            {{ item.phone_number }}
                                        </label>
                                    </div>
                                    <Link :href="route('customers.show', item.address_id)"
                                          class="sticky top-0 z-10 border-y border-b-blue-500 border-t-blue-400 bg-blue-200 px-3 py-2 text-sm font-semibold leading-6 text-gray-900"
                                          method="get" as="button">
                                        <label class="block text-sm font-medium leading-6 text-gray-900">Customer
                                            Page</label>
                                    </Link>
                                    <h3 class="text-base font-semibold leading-6 text-gray-900">{{ item.name }}</h3>
                                    <h3 class="text-base font-semibold leading-6 text-gray-900">{{ item.status }}</h3>
                                </div>
                                <div class="py-2">
                                    <label for="description" class="block text-sm font-medium leading-6 text-gray-900">Description</label>
                                    <div class="mt-2">
                                        <input type="text" name="description" id="description"
                                               @blur="changeDescription(item)"
                                               class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1
                                       ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset
                                       focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                               v-model="item.description"/>
                                    </div>
                                </div>
                                <div class="py-2">
                                    <div class="flex justify-between">
                                        <label for="price"
                                               class="block text-sm font-medium leading-6 text-gray-900">Price</label>
                                        <label for="price" class="block text-sm font-medium leading-6 text-gray-900">${{
                                                item.price
                                            }}</label>
                                    </div>
                                    <div class="mt-2">
                                        <input type="text" name="price" id="price"
                                               @blur="updatePrice(item)"
                                               class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1
                                       ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset
                                       focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                               v-model="item.price"/>
                                    </div>
                                </div>

                                <div class="flex justify-between">
                                    <div
                                        class="py-2 pr-2 w-full">
                                        <label for="serviceman" class="block text-sm font-medium text-gray-700">Assigned
                                            Serviceman</label>
                                        <select id="serviceman" name="serviceman"
                                                v-model='item.assigned'
                                                @change="assignServiceman(item)"
                                                class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                            <option
                                                v-for="option in servicemen" :value="option.name">
                                                {{ option.name }}
                                            </option>
                                        </select>
                                    </div>

                                    <div
                                        class="py-2 pl-2 w-full">
                                        <label for="status" class="block text-sm font-medium text-gray-700">Change
                                            Status</label>
                                        <select id="status" name="status"
                                                v-model='item.status'
                                                @change="changeStatus(item)"
                                                class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                            <option
                                                v-for="option in status" :value="option.name">
                                                {{ option.name }}
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="flex items-center justify-between">
                                    <div>
                                        <label v-if="item.sent" for="price"
                                               class="block text-sm font-medium leading-6 text-green-800">Approval
                                            Sent</label>
                                        <button @click="requestApproval(item)"
                                                class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm
                                text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700
                                focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                            Request Approval
                                        </button>
                                    </div>

                                    <!--                            <div v-if="item.sent"><span class="-->
                                    <!--                            mt-4 text-sm rounded-md py-2 px-4 font-medium bg-green-800 text-white-->
                                    <!--                            ">Approval Sent</span></div>-->

                                    <button @click="deleteItem(item)"
                                            class="mt-4 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm
                                text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700
                                focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                        Delete
                                    </button>
                                </div>
                            </div>
                            <div v-if="item.deleted" class="rounded-md bg-red-50 p-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <XCircleIcon class="h-5 w-5 text-red-400" aria-hidden="true"/>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-red-800">You are about to delete this item.
                                            Do you
                                            wish to continue</h3>
                                        <div class="flex justify-around mt-2 text-sm text-red-700">
                                            <button @click="doTheDeletion(item)"
                                                    class="mt-4 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm
                                text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700
                                focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                                Yes
                                            </button>
                                            <button @click="cancelDeletion(item)"
                                                    class="mt-4 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm
                                text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700
                                focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                No
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>

        </div>
        <!--        <pre>{{ tasks }}</pre>-->

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
        tasks: Array,
        servicemen: Array,
        user: String
    },
    data() {
        return {
            approved: null,
            showTab: 'Created',
            myTab: 'Created',
            pickedUp: false,
            tabs: [
                {name: 'Created', href: '#created', current: true, count: 0},
                {name: 'Approval', href: '#approval', current: false, count: 0},
                {name: 'Denied', href: '#denied', current: false, count: 0},
                {name: 'DIY', href: '#diy', current: false, count: 0},
            ],
            servicemen: this.servicemen,
            status: [
                {
                    id: 1,
                    name: 'created',
                },
                {
                    id: 2,
                    name: 'approved',
                },
                {
                    id: 3,
                    name: 'denied',
                },
                {
                    id: 4,
                    name: 'diy',
                },
                {
                    id: 5,
                    name: 'completed',
                }
            ],
            createdObjects: 0,
            approvedObjects: 0,
            deniedObjects: 0,
            diydObjects: 0
        }
    },
    mounted() {
        this.updateCount();
    },
    beforeUpdate() {
        this.updateCount();
    },
    updated() {
        this.updateCount();
    },
    methods: {
        updateCount() {

            this.createdObjects = 0;
            this.approvedObjects = 0;
            this.deniedObjects = 0;
            this.diydObjects = 0;

            for (let i = 0; i < this.tasks.length; i++) {
                if (this.tasks[i].status === 'created') {
                    this.createdObjects = this.createdObjects + 1;
                } else if (this.tasks[i].status === 'approved') {
                    this.approvedObjects = this.approvedObjects + 1;
                } else if (this.tasks[i].status === 'denied') {
                    this.deniedObjects = this.deniedObjects + 1;
                } else if (this.tasks[i].status === 'diy') {
                    this.diydObjects = this.diydObjects + 1;
                }

                this.tabs[0].count = 0
                this.tabs[1].count = 0
                this.tabs[2].count = 0
                this.tabs[3].count = 0

                this.tabs[0].count = this.createdObjects;
                this.tabs[1].count = this.approvedObjects;
                this.tabs[2].count = this.deniedObjects;
                this.tabs[3].count = this.diydObjects;
            }
        },
        changeTab(tab) {
            for (let i = 0; i < this.tabs.length; i++) {
                this.tabs[i].current = false

                // this.tabs[i].current = this.tabs[i].name === tab.name;

                if (this.tabs[i].name === tab.name) {
                    this.tabs[i].current = true
                    this.showTab = tab.name
                }
            }

        },
        changeSmallTab() {
            for (let i = 0; i < this.tabs.length; i++) {
                this.tabs[i].current = false
                if (this.tabs[i].name === this.myTab) {
                    this.tabs[i].current = true

                    if (this.tabs[i].name === 'Created') {
                        this.showTab = 'Created';
                    } else if (this.tabs[i].name === 'Approval') {
                        this.showTab = 'Approval';
                    } else if (this.tabs[i].name === 'Denied') {
                        this.showTab = 'Denied';
                    } else if (this.tabs[i].name === 'DIY') {
                        this.showTab = 'DIY';
                    }
                }
            }
        },
        approveItem(item) {
            item.approved = !item.approved
            Inertia.post('/task/approveItem', item)
        },
        updatePrice(item) {
            Inertia.post('/task/updatePrice', item)
        },
        changeStatus(item) {
            Inertia.post('/task/changeStatus', item)
        },
        changeDescription(item) {
            Inertia.post('/task/changeDescription', item)
        },
        changeProductNumber(item) {
            Inertia.post('/task/changeProductNumber', item)
        },
        deleteItem(item) {
            item.deleted = true;
        },
        cancelDeletion(item) {
            item.deleted = false;
        },
        doTheDeletion(item) {
            Inertia.post('/task/deleteItem', item)
        },
        assignServiceman(servicemanId) {
            Inertia.post('/task/assignServiceman', servicemanId)
        },
        requestApproval(item) {
            Inertia.post('/task/requestApproval', item)
        }
    },
    computed: {
        errorClass() {
            return this.errors.timeIn ? 'text-red-600' : ''
        },
        approvedTasks() {
            let approved = [];
            for (let i = 0; i < this.tasks.length; i++) {
                if (this.tasks[i].status === 'approved') {
                    approved.push(this.tasks[i])
                }
            }
            return approved
        },
        inventoryTasks() {
            let pickedUp = [];
            for (let i = 0; i < this.tasks.length; i++) {
                if (this.tasks[i].status === 'pickedUp') {
                    pickedUp.push(this.tasks[i])
                }
            }
            return pickedUp
        }
    }
}
</script>

<style scoped>

</style>

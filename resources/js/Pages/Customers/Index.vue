<template>
    <layout
        title="Customers"
        :user="user"
    >

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
                                               class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                               placeholder="type customer last name"/>
                                    </div>

                                    <div v-for="name in dbNames" :key="name.id">
                                        <Link :href="route('customers.show', name.addressId)"
                                              class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                              style="padding: 1rem; background: white"
                                        >
                                            <div>
                                                {{ name.first_name }} {{ name.last_name }} - {{ name.address_line_1}}
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


        <!--        <Combobox as="div" v-model="selectedPerson">-->
        <!--            <ComboboxLabel v-if="selectedPerson" class="block text-sm font-medium leading-6 text-gray-900">-->
        <!--                {{ selectedPerson.first_name }} {{ selectedPerson.last_name }} - {{ selectedPerson.address_line_1 }}-->
        <!--            </ComboboxLabel>-->
        <!--            <div class="relative mt-2">-->
        <!--                &lt;!&ndash;              @change="query = $event.target.value" :display-value="person && (person?.first_name + ' ' + person?.last_name) !== undefined ? '' : person.first_name + ' ' + person.last_name"/>&ndash;&gt;-->
        <!--                &lt;!&ndash;              @change="query = $event.target.value" :display-value="(person) {if(person?.first_name !== undefined && person?.last_name !== undefined){ return ''} else {return person.first_name + ' ' + person.last_name}}"/>&ndash;&gt;-->
        <!--                <ComboboxInput-->
        <!--                    class="w-full rounded-md border-0 bg-white py-1.5 pl-3 pr-10 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"-->
        <!--                    @change="query = $event.target.value" :display-value="(person) => person?.last_name"/>-->
        <!--                &lt;!&ndash;                <ComboboxButton&ndash;&gt;-->
        <!--                &lt;!&ndash;                    class="absolute inset-y-0 right-0 flex items-center rounded-r-md px-2 focus:outline-none">&ndash;&gt;-->
        <!--                &lt;!&ndash;                    <ChevronUpDownIcon class="h-5 w-5 text-gray-400" aria-hidden="true"/>&ndash;&gt;-->
        <!--                &lt;!&ndash;                </ComboboxButton>&ndash;&gt;-->

        <!--                <ComboboxOptions v-if="filteredPeople.length > 0"-->
        <!--                                 class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm">-->
        <!--                    <ComboboxOption v-for="person in filteredPeople" :key="person.id" :value="person" as="template"-->
        <!--                                    v-slot="{ active, selected }">-->
        <!--                        <li :class="['relative cursor-default select-none py-2 pl-8 pr-4', active ? 'bg-indigo-600 text-white' : 'text-gray-900']">-->
        <!--            <span :class="['block truncate', selected && 'font-semibold']">-->
        <!--              {{ person.first_name }} {{ person.last_name }} - {{ person.address_line_1 }}-->
        <!--            </span>-->

        <!--                            <span v-if="selected"-->
        <!--                                  :class="['absolute inset-y-0 left-0 flex items-center pl-1.5', active ? 'text-white' : 'text-indigo-600']">-->
        <!--              <CheckIcon class="h-5 w-5" aria-hidden="true"/>-->
        <!--            </span>-->
        <!--                        </li>-->
        <!--                    </ComboboxOption>-->
        <!--                </ComboboxOptions>-->
        <!--            </div>-->
        <!--            <button type="button"-->
        <!--                    class="-ml-px relative inline-flex items-center px-4 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500"-->
        <!--                    @click="goToCustomer(selectedPerson.addressId)"-->
        <!--            >Go-->
        <!--            </button>-->
        <!--        </Combobox>-->


        <br>

        <div class="flex">
            <button @click="open = !open" type="button" class="items-center justify-center rounded-lg lg:ml-8" style="margin-right: 1rem;">Search
                Customers
            </button>
            <svg class="h-6 w-6 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </div>

        <br>

        <!--    <pre>{{ selectedPerson }}</pre>-->

        <span class="relative z-0 inline-flex shadow-sm rounded-md" style="margin-bottom: 1rem">
    <button type="button"
            class="relative inline-flex items-center px-4 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500"
            @click="showRoute('monday')"
    >Monday</button>
    <button type="button"
            class="-ml-px relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500"
            @click="showRoute('tuesday')"
    >Tuesday</button>
    <button type="button"
            class="-ml-px relative inline-flex items-center px-4 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500"
            @click="showRoute('wednesday')"
    >Wednesday</button>
  </span>
        <br>
        <span class="relative z-0 inline-flex shadow-sm rounded-md" style="margin-bottom: 1rem">
            <button type="button"
                    class="-ml-px relative inline-flex items-center px-4 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500"
                    @click="showRoute('thursday')"
            >Thursday</button>
            <button type="button"
                    class="-ml-px relative inline-flex items-center px-4 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500"
                    @click="showRoute('friday')"
            >Friday</button>
            <button type="button"
                    class="-ml-px relative inline-flex items-center px-4 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500"
                    @click="showRoute('saturday')"
            >Saturday</button>
            <button type="button"
                    class="-ml-px relative inline-flex items-center px-4 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500"
                    @click="showRoute('all')"
            >all</button>
            <button type="button"
                    class="-ml-px relative inline-flex items-center px-4 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500"
                    @click="showRoute('none')"
            >none</button>


  </span>

        <br>


        <!--        <div class="px-4 sm:px-6 lg:px-8">-->
        <!--            <div class="sm:flex sm:items-center">-->
        <!--                <div class="sm:flex-auto">-->
        <!--                    <h1 class="text-base font-semibold leading-6 text-gray-900">Users</h1>-->
        <!--                    <p class="mt-2 text-sm text-gray-700">A list of all the users in your account including their name, title, email and role.</p>-->
        <!--                </div>-->
        <!--                <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">-->
        <!--                    <button type="button" class="block rounded-md bg-indigo-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Add user</button>-->
        <!--                </div>-->
        <!--            </div>-->
        <!--            <div class="mt-8 flow-root">-->
        <!--                <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">-->
        <!--                    <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">-->
        <!--                        <table class="min-w-full divide-y divide-gray-300">-->
        <!--                            <thead>-->
        <!--                            <tr class="divide-x divide-gray-200">-->
        <!--                                <th scope="col" class="py-3.5 pl-4 pr-4 text-left text-sm font-semibold text-gray-900 sm:pl-0">Name</th>-->
        <!--                                <th scope="col" class="px-4 py-3.5 text-left text-sm font-semibold text-gray-900">Address</th>-->
        <!--                                <th scope="col" class="px-4 py-3.5 text-left text-sm font-semibold text-gray-900">Notes</th>-->
        <!--                            </tr>-->
        <!--                            </thead>-->
        <!--                            <tbody class="divide-y divide-gray-200 bg-white">-->
        <!--                            <tr v-for="row in valueObjectArray" :key="row.address" class="divide-x divide-gray-200">-->
        <!--                                <td class="whitespace-nowrap py-4 pl-4 pr-4 text-sm font-medium text-gray-900 sm:pl-0">{{  row[1] }}</td>-->
        <!--                                <td class="whitespace-nowrap p-4 text-sm text-gray-500">{{ row[3] }}</td>-->
        <!--                                <td class="whitespace-nowrap p-4 text-sm text-gray-500">{{ row[4] }}</td>-->
        <!--                            </tr>-->
        <!--                            </tbody>-->
        <!--                        </table>-->
        <!--                    </div>-->
        <!--                </div>-->
        <!--            </div>-->
        <!--        </div>-->

        <nav class="h-full overflow-y-auto" aria-label="Directory">
            <!--      <div v-for="row in customers" :key="row.id" class="relative">-->
            <div v-show="monday">
                <div v-for="row in sortedRoute('Monday')" :key="row.id" class="relative">
                    <Link :href="route('customers.show', row.addressId)"
                          class="sticky top-0 z-10 border-y border-b-gray-200 border-t-gray-100 bg-gray-50 px-3 py-2 text-sm font-semibold leading-6 text-gray-900"
                          :class="row.completed ? 'completed' : 'notCompleted'"
                          style="border: solid thin black; width: -webkit-fill-available;"
                          method="get" as="button">
                        <div class="flex justify-center">
                            <h3 style="font-size: 1.4rem" :class="row.needsBackwash ? 'text-red-700 dark:bg-red-200 dark:text-red-800' : 'text-green-700 dark:bg-green-200 dark:text-green-800'">{{ row.first_name }} {{ row.last_name }}</h3>
                            <div v-if="row.needsBackwash">
                                <svg class="h-6 w-6 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.366-.011.668.279.707.648l.007.106v8a.75.75 0 01-1.493.102l-.007-.102v-8c0-.414.335-.75.75-.75zm.006 10.77a.75.75 0 110 1.5.75.75 0 010-1.5z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div v-else>
                                <svg class="h-6 w-6 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </div>
                        <div>{{ row.address_line_1 }}, {{ row.city }} AZ {{ row.zip }}</div>
                        <div>{{ row.community_gate_code }}</div>
                        <div>{{ row.assigned_serviceman }}</div>
                        <!--              <div>{{ row.order }}</div>-->
                        <div v-if="user.is_admin === 1">{{ row.phone_number }}</div>
                    </Link>
                    <div v-if="user.is_admin === 1" class="flex justify-between"
                         style="width: 220px; margin-bottom: 20px; margin-top: 5px;"
                    >
                        <div>
                            <select v-model="row.newServicemanId" id="location" name="location"
                                    class="mt-2 block w-full rounded-md border-0 py-1.5 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                <option v-for="serviceman in servicemen" :key="serviceman.id">{{ serviceman.name }}
                                    {{ serviceman.id }} {{ row.addressId }}
                                </option>
                            </select>
                        </div>
                        <button type="button"
                                class="relative inline-flex items-center px-4 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500"
                                @click="transfer(row.newServicemanId)">
                            transfer
                        </button>
                    </div>
                </div>
            </div>

            <div v-show="tuesday">
                <div v-for="row in sortedRoute('Tuesday')" :key="row.id" class="relative">
                    <Link :href="route('customers.show', row.addressId)"
                          class="sticky top-0 z-10 border-y border-b-gray-200 border-t-gray-100 bg-gray-50 px-3 py-2 text-sm font-semibold leading-6 text-gray-900"
                          :class="row.completed ? 'completed' : 'notCompleted'"
                          style="border: solid thin black; width: -webkit-fill-available;"
                          method="get" as="button">
                        <div class="flex justify-center">
                            <h3 style="font-size: 1.4rem" :class="row.needsBackwash ? 'text-red-700 dark:bg-red-200 dark:text-red-800' : 'text-green-700 dark:bg-green-200 dark:text-green-800'">{{ row.first_name }} {{ row.last_name }}</h3>
                            <div v-if="row.needsBackwash">
                                <svg class="h-6 w-6 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.366-.011.668.279.707.648l.007.106v8a.75.75 0 01-1.493.102l-.007-.102v-8c0-.414.335-.75.75-.75zm.006 10.77a.75.75 0 110 1.5.75.75 0 010-1.5z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div v-else>
                                <svg class="h-6 w-6 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </div>
                        <div>{{ row.address_line_1 }}, {{ row.city }} AZ {{ row.zip }}</div>
                        <div>{{ row.community_gate_code }}</div>
                        <div>{{ row.assigned_serviceman }}</div>
                        <div v-if="user.is_admin === 1">{{ row.phone_number }}</div>
                    </Link>
                    <div v-if="user.is_admin === 1" class="flex justify-between"
                         style="width: 220px; margin-bottom: 20px; margin-top: 5px;"
                    >
                        <div>
                            <select v-model="row.newServicemanId" id="location" name="location"
                                    class="mt-2 block w-full rounded-md border-0 py-1.5 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                <option v-for="serviceman in servicemen" :key="serviceman.id">{{ serviceman.name }}
                                    {{ serviceman.id }} {{ row.addressId }}
                                </option>
                            </select>
                        </div>
                        <button type="button"
                                class="relative inline-flex items-center px-4 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500"
                                @click="transfer(row.newServicemanId)">
                            transfer
                        </button>
                    </div>
                </div>
            </div>

            <div v-show="wednesday">
                <div v-for="row in sortedRoute('Wednesday')" :key="row.id" class="relative">
                    <Link :href="route('customers.show', row.addressId)"
                          class="sticky top-0 z-10 border-y border-b-gray-200 border-t-gray-100 bg-gray-50 px-3 py-2 text-sm font-semibold leading-6 text-gray-900"
                          :class="row.completed ? 'completed' : 'notCompleted'"
                          style="border: solid thin black; width: -webkit-fill-available;"
                          method="get" as="button">
                        <div class="flex justify-center">
                            <h3 style="font-size: 1.4rem" :class="row.needsBackwash ? 'text-red-700 dark:bg-red-200 dark:text-red-800' : 'text-green-700 dark:bg-green-200 dark:text-green-800'">{{ row.first_name }} {{ row.last_name }}</h3>
                            <div v-if="row.needsBackwash">
                                <svg class="h-6 w-6 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.366-.011.668.279.707.648l.007.106v8a.75.75 0 01-1.493.102l-.007-.102v-8c0-.414.335-.75.75-.75zm.006 10.77a.75.75 0 110 1.5.75.75 0 010-1.5z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div v-else>
                                <svg class="h-6 w-6 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </div>
                        <div>{{ row.address_line_1 }}, {{ row.city }} AZ {{ row.zip }}</div>
                        <div>{{ row.community_gate_code }}</div>
                        <div>{{ row.assigned_serviceman }}</div>
                        <div v-if="user.is_admin === 1">{{ row.phone_number }}</div>
                    </Link>
                    <div v-if="user.is_admin === 1" class="flex justify-between"
                         style="width: 220px; margin-bottom: 20px; margin-top: 5px;"
                    >
                        <div>
                            <select v-model="row.newServicemanId" id="location" name="location"
                                    class="mt-2 block w-full rounded-md border-0 py-1.5 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                <option v-for="serviceman in servicemen" :key="serviceman.id">{{ serviceman.name }}
                                    {{ serviceman.id }} {{ row.addressId }}
                                </option>
                            </select>
                        </div>
                        <button type="button"
                                class="relative inline-flex items-center px-4 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500"
                                @click="transfer(row.newServicemanId)">
                            transfer
                        </button>
                    </div>
                </div>
            </div>

            <div v-show="thursday">
                <div v-for="row in sortedRoute('Thursday')" :key="row.id" class="relative">
                    <Link :href="route('customers.show', row.addressId)"
                          class="sticky top-0 z-10 border-y border-b-gray-200 border-t-gray-100 bg-gray-50 px-3 py-2 text-sm font-semibold leading-6 text-gray-900"
                          :class="row.completed ? 'completed' : 'notCompleted'"
                          style="border: solid thin black; width: -webkit-fill-available;"
                          method="get" as="button">
                        <div class="flex justify-center">
                            <h3 style="font-size: 1.4rem" :class="row.needsBackwash ? 'text-red-700 dark:bg-red-200 dark:text-red-800' : 'text-green-700 dark:bg-green-200 dark:text-green-800'">{{ row.first_name }} {{ row.last_name }}</h3>
                            <div v-if="row.needsBackwash">
                                <svg class="h-6 w-6 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.366-.011.668.279.707.648l.007.106v8a.75.75 0 01-1.493.102l-.007-.102v-8c0-.414.335-.75.75-.75zm.006 10.77a.75.75 0 110 1.5.75.75 0 010-1.5z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div v-else>
                                <svg class="h-6 w-6 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </div>
                        <div>{{ row.address_line_1 }}, {{ row.city }} AZ {{ row.zip }}</div>
                        <div>{{ row.community_gate_code }}</div>
                        <div>{{ row.assigned_serviceman }}</div>
                        <div v-if="user.is_admin === 1">{{ row.phone_number }}</div>
                    </Link>
                    <div v-if="user.is_admin === 1" class="flex justify-between"
                         style="width: 220px; margin-bottom: 20px; margin-top: 5px;"
                    >
                        <div>
                            <select v-model="row.newServicemanId" id="location" name="location"
                                    class="mt-2 block w-full rounded-md border-0 py-1.5 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                <option v-for="serviceman in servicemen" :key="serviceman.id">{{ serviceman.name }}
                                    {{ serviceman.id }} {{ row.addressId }}
                                </option>
                            </select>
                        </div>
                        <button type="button"
                                class="relative inline-flex items-center px-4 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500"
                                @click="transfer(row.newServicemanId)">
                            transfer
                        </button>
                    </div>
                </div>
            </div>

            <div v-show="friday">
                <div v-for="row in sortedRoute('Friday')" :key="row.id" class="relative">
                    <Link :href="route('customers.show', row.addressId)"
                          class="sticky top-0 z-10 border-y border-b-gray-200 border-t-gray-100 bg-gray-50 px-3 py-2 text-sm font-semibold leading-6 text-gray-900"
                          :class="row.completed ? 'completed' : 'notCompleted'"
                          style="border: solid thin black; width: -webkit-fill-available;"
                          method="get" as="button">
                        <div class="flex justify-center">
                            <h3 style="font-size: 1.4rem" :class="row.needsBackwash ? 'text-red-700 dark:bg-red-200 dark:text-red-800' : 'text-green-700 dark:bg-green-200 dark:text-green-800'">{{ row.first_name }} {{ row.last_name }}</h3>
                            <div v-if="row.needsBackwash">
                                <svg class="h-6 w-6 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.366-.011.668.279.707.648l.007.106v8a.75.75 0 01-1.493.102l-.007-.102v-8c0-.414.335-.75.75-.75zm.006 10.77a.75.75 0 110 1.5.75.75 0 010-1.5z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div v-else>
                                <svg class="h-6 w-6 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </div>
                        <div>{{ row.address_line_1 }}, {{ row.city }} AZ {{ row.zip }}</div>
                        <div>{{ row.community_gate_code }}</div>
                        <div>{{ row.assigned_serviceman }}</div>
                        <div v-if="user.is_admin === 1">{{ row.phone_number }}</div>
                    </Link>
                    <div v-if="user.is_admin === 1" class="flex justify-between"
                         style="width: 220px; margin-bottom: 20px; margin-top: 5px;"
                    >
                        <div>
                            <select v-model="row.newServicemanId" id="location" name="location"
                                    class="mt-2 block w-full rounded-md border-0 py-1.5 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                <option v-for="serviceman in servicemen" :key="serviceman.id">{{ serviceman.name }}
                                    {{ serviceman.id }} {{ row.addressId }}
                                </option>
                            </select>
                        </div>
                        <button type="button"
                                class="relative inline-flex items-center px-4 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500"
                                @click="transfer(row.newServicemanId)">
                            transfer
                        </button>
                    </div>
                </div>
            </div>

            <div v-show="saturday">
                <div v-for="row in sortedRoute('Saturday')" :key="row.id" class="relative">
                    <Link :href="route('customers.show', row.addressId)"
                          class="sticky top-0 z-10 border-y border-b-gray-200 border-t-gray-100 bg-gray-50 px-3 py-2 text-sm font-semibold leading-6 text-gray-900"
                          :class="row.completed ? 'completed' : 'notCompleted'"
                          style="border: solid thin black; width: -webkit-fill-available;"
                          method="get" as="button">
                        <div class="flex justify-center">
                            <h3 style="font-size: 1.4rem" :class="row.needsBackwash ? 'text-red-700 dark:bg-red-200 dark:text-red-800' : 'text-green-700 dark:bg-green-200 dark:text-green-800'">{{ row.first_name }} {{ row.last_name }}</h3>
                            <div v-if="row.needsBackwash">
                                <svg class="h-6 w-6 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.366-.011.668.279.707.648l.007.106v8a.75.75 0 01-1.493.102l-.007-.102v-8c0-.414.335-.75.75-.75zm.006 10.77a.75.75 0 110 1.5.75.75 0 010-1.5z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div v-else>
                                <svg class="h-6 w-6 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </div>
                        <div>{{ row.address_line_1 }}, {{ row.city }} AZ {{ row.zip }}</div>
                        <div>{{ row.community_gate_code }}</div>
                        <div>{{ row.assigned_serviceman }}</div>
                        <div v-if="user.is_admin === 1">{{ row.phone_number }}</div>
                    </Link>
                    <div v-if="user.is_admin === 1" class="flex justify-between"
                         style="width: 220px; margin-bottom: 20px; margin-top: 5px;"
                    >
                        <div>
                            <select v-model="row.newServicemanId" id="location" name="location"
                                    class="mt-2 block w-full rounded-md border-0 py-1.5 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                <option v-for="serviceman in servicemen" :key="serviceman.id">{{ serviceman.name }}
                                    {{ serviceman.id }} {{ row.addressId }}
                                </option>
                            </select>
                        </div>
                        <button type="button"
                                class="relative inline-flex items-center px-4 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500"
                                @click="transfer(row.newServicemanId)">
                            transfer
                        </button>
                    </div>
                </div>
            </div>
        </nav>

    </layout>
</template>

<script setup>
import {computed, ref, defineProps} from 'vue'
import {CheckIcon, ChevronUpDownIcon} from '@heroicons/vue/20/solid'
import {
    Combobox,
    ComboboxButton,
    ComboboxInput,
    ComboboxLabel,
    ComboboxOption,
    ComboboxOptions,
} from '@headlessui/vue'
import {Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot} from '@headlessui/vue'

const open = ref(false)

const props = defineProps({
    customers: Array,
    servicemen: Array,
    user: Object
});

const people = props.customers.map(({id, first_name, last_name, address_line_1, addressId}) => ({
    id,
    first_name,
    last_name,
    address_line_1,
    addressId
}));

const query = ref('')
const selectedPerson = ref(null)
const filteredPeople = computed(() =>
    query.value === ''
        ? people
        : people.filter((person) => {
            return person.last_name.toLowerCase().includes(query.value.toLowerCase())
        })
)

</script>

<script>
import JetInput from '@/Jetstream/Input'
import SimpleTable from "../Shared/SimpleTable";
import Layout from "../Shared/Layout";
import {Link} from '@inertiajs/inertia-vue3';
import {Inertia} from '@inertiajs/inertia';


export default {
    name: 'CustomerIndex',
    components: {
        JetInput,
        Layout,
        SimpleTable,
        Link
    },
    data() {
        return {
            csrfToken: null,
            dbNames: [],
            liquidChlorine: null,
            monday: false,
            tuesday: false,
            wednesday: false,
            thursday: false,
            friday: false,
            saturday: false,
            customer_headers: [
                {
                    name: 'id',
                    key: 'id',
                },
                {
                    name: 'Name',
                    key: 'last_name',
                },
                {
                    name: 'Service Day',
                    key: 'service_day',
                },
                {
                    name: 'Address',
                    key: 'address',
                },
                {
                    name: 'Community Gate Code',
                    key: 'gate_code',
                },
                {
                    name: 'Completed',
                    key: 'completed',
                },
                {
                    name: 'Assigned Serviceman',
                    key: 'assigned_serviceman',
                },
                {
                    name: 'Phone Number',
                    key: 'phone_number',
                }
            ],
            valueObjectArray: []
        }
    },
    mounted() {
        this.csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        this.valueObjectArray = this.tableValues();
        let date = new Date()
        let d = date.toDateString();
        let theDay = d.split(" ")[0]

        this.hideDays();

        if (theDay === 'Mon') {
            this.monday = true;
        } else if (theDay === 'Tue') {
            this.tuesday = true;
        } else if (theDay === 'Wed') {
            this.wednesday = true;
        } else if (theDay === 'Thu') {
            this.thursday = true;
        } else if (theDay === 'Fri') {
            this.friday = true;
        }


    },
    methods: {
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


            // const promise = Inertia.post('/customers/getNames', {'name': name})
            //
            // promise.then(response => {
            //     // Handle the response data here
            //     // const $element = document.getElementById('response')
            //     // $element.innerHTML = this.response;
            //
            //     console.log(response)
            //
            // }).catch(error => {
            //     // Handle any errors that occurred during the request
            //     console.error(error);
            // });

        },
        sortedRoute(day) {

            const dayOfWeek = this.customers.filter(obj => obj.service_day === day);
            return dayOfWeek.sort((a, b) => a.order - b.order);

        },
        transfer(address) {
            Inertia.post('/transfer/storeFromCustomers', {'address': address})
        },
        goToCustomer(id) {
            Inertia.get('customers/show/' + id);
        },
        customerDropdownList() {
            return [
                {id: 1, name: 'Leslie Alexander'},
                {id: 2, name: 'Leslie Alexander1'},
                {id: 3, name: 'Leslie Alexander2'},
                {id: 4, name: 'Leslie Alexander3'},
                {id: 5, name: 'Leslie Alexander4'},
            ]
        },
        showRoute(day) {
            if (day === 'all') {
                this.monday = true;
                this.tuesday = true;
                this.wednesday = true;
                this.thursday = true;
                this.friday = true;
            } else if (day === 'none') {
                this.hideDays();
            } else {
                this.hideDays();
                if (day === 'monday') {
                    this.monday = true
                } else if (day === 'tuesday') {
                    this.tuesday = true
                } else if (day === 'wednesday') {
                    this.wednesday = true
                } else if (day === 'thursday') {
                    this.thursday = true
                } else if (day === 'friday') {
                    this.friday = true
                } else if (day === 'saturday') {
                    this.saturday = true
                }
            }
        },

        hideDays() {
            this.monday = false;
            this.tuesday = false;
            this.wednesday = false;
            this.thursday = false;
            this.friday = false;
            this.saturday = false;
        },

        getName() {
            const firstEntry = this.valueObjectArray[1];

            return typeof firstEntry;

        },

        tableValues() {
            let mArray = []
            for (let i = 0; i < this.customers.length; i++) {
                let nArray = []
                for (let j = 0; j < this.customer_headers.length; j++) {
                    if (this.customer_headers[j].key === "last_name") {
                        let customerName = this.customers[i]['first_name'] + " " + this.customers[i][this.customer_headers[j].key]
                        nArray.push(customerName)
                    } else if (this.customer_headers[j].key === "address") {
                        let address =
                            this.customers[i]['address_line_1'] + " " +
                            this.customers[i]['city'] + " " +
                            'AZ' + " " +
                            this.customers[i]['zip'];
                        nArray.push(address)
                    } else if (this.customer_headers[j].key === "gate_code"
                        && this.customers[i]['community_gate_code']) {
                        nArray.push(this.customers[i]['community_gate_code'])
                    } else if (this.customer_headers[j].key === "completed"
                        && this.customers[i]['completed']) {
                        nArray.push(this.customers[i]['completed'])
                    } else if (this.customer_headers[j].key === "assigned_serviceman") {
                        nArray.push(this.customers[i]['assigned_serviceman'])
                    } else if (this.customer_headers[j].key === "phone_number") {
                        nArray.push(this.customers[i]['phone_number'])
                    } else {
                        nArray.push(this.customers[i][this.customer_headers[j].key])
                    }


                }
                mArray.push(nArray)
            }
            return mArray;
        }
    },
    props: {
        customers: Array,
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

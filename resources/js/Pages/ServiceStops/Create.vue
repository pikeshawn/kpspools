<template>
    <layout
        :user="user"
        :addressId="address.id"
    >

        <!--    <pre>{{ address }}</pre>-->

        <div class="bg-gray-900 px-6 py-24 sm:py-8 lg:px-8">
            <div class="mx-auto max-w-2xl text-center">
                <h2 class="text-4xl font-bold tracking-tight text-white sm:text-6xl">{{ customer.first_name }}
                    {{ customer.last_name }}</h2>
                <div>
                    <div class="mt-6 border-t border-white/10">
                        <dl class="">
                            <div class="px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                <dt class="text-sm font-medium leading-6 text-white">Address</dt>
                                <dd class="mt-1 text-sm leading-6 text-gray-400 sm:col-span-2 sm:mt-0">
                                    {{ address.address_line_1 }}, {{ address.city }} {{ address.state }}
                                    {{ address.zip }}
                                </dd>
                            </div>
                            <div class="px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                <dt class="text-sm font-medium leading-6 text-white">Gate Code</dt>
                                <dd class="mt-1 text-sm leading-6 text-gray-400 sm:col-span-2 sm:mt-0">
                                    {{ address.community_gate_code }}
                                </dd>
                            </div>
                            <div class="px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                <dt class="text-sm font-medium leading-6 text-white">Phone Number</dt>
                                <dd class="mt-1 text-sm leading-6 text-gray-400 sm:col-span-2 sm:mt-0">
                                    {{ customer.phone_number }}
                                </dd>
                            </div>
                            <div v-if="user.is_admin === 1">
                                <div class="px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                    <dt class="text-sm font-medium leading-6 text-white">Plan Price</dt>
                                    <dd class="mt-1 text-sm leading-6 text-gray-400 sm:col-span-2 sm:mt-0">
                                        {{ customer.plan_price }}
                                    </dd>
                                </div>
                                <div class="px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                    <dt class="text-sm font-medium leading-6 text-white">Chemicals Included</dt>
                                    <dd class="mt-1 text-sm leading-6 text-gray-400 sm:col-span-2 sm:mt-0">
                                        {{ customer.chemicals_included }}
                                    </dd>
                                </div>
                                <div class="px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                    <dt class="text-sm font-medium leading-6 text-white">Service Day</dt>
                                    <dd class="mt-1 text-sm leading-6 text-gray-400 sm:col-span-2 sm:mt-0">
                                        {{ customer.service_day }}
                                    </dd>
                                </div>
                                <div class="px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                    <dt class="text-sm font-medium leading-6 text-white">Terms</dt>
                                    <dd class="mt-1 text-sm leading-6 text-gray-400 sm:col-span-2 sm:mt-0">
                                        {{ customer.terms }}
                                    </dd>
                                </div>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>
        </div>


<!--        <pre>{{ cya }}</pre>-->

        <div class="px-4 sm:px-6 lg:px-8">
            <div class="mt-8 flow-root">
                <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="inline-block min-w-full py-2 align-middle">
                        <table class="min-w-full divide-y divide-gray-300">
                            <thead>
                            <tr>
                                <th scope="col"
                                    class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                    Items to Complete
                                </th>
                            </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                            <tr v-for="item in tasks" :key="item.id">
                                <td v-if="item.status === 'pickedUp'"
                                    class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                    <div class="flex" style="flex-direction: column">
                                        <div>{{ item.description }}</div>
                                        <div>_______________________</div>
                                        <div>{{ item.assigned }}</div>
                                        <button @click="completed(item)"
                                                v-if="item.status === 'pickedUp'"
                                                class="mt-6 inline-flex justify-center py-1 px-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                            Complete
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-10 sm:mt-0 mb-60 py-6">
            <div class="flex flex-col">
                <div class="md:col-span-1">
                    <div class="px-4 sm:px-0 mb-3">
                        <div>
                            <div class="col-span-1">
                                <label for="service_type" class="block text-sm font-medium text-gray-700">Service
                                    Type</label>
                                <select id="service_type" name="service_type"
                                        v-model="form.service_type"
                                        class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option v-for="option in [
                                                           'Service Stop', 'Repair', 'Missed Service', 'Clear Green Pool', 'Chemical Stop', 'Intro'
                                                           ]">{{ option }}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <p class="mt-1 text-sm text-gray-600">
                            Please input the service information below
                        </p>
                    </div>
                </div>
                <div class="mt-5 md:mt-0 md:col-span-2">
                    <form @submit.prevent="store">
                        <div class="shadow overflow-hidden sm:rounded-md">
                            <div class="px-4 py-5 bg-white sm:p-6">
                                <div class="grid grid-cols-2 gap-6">

                                    <div v-if="equipment">
                                        <div v-if="equipment.type === 'cartridge'">
                                            Cartridge Filter - No Need to Backwash
                                        </div>
                                        <div v-else>
                                            <div v-if="lastBackwash">
                                                <div class="bg-red-500 text-white p-4 rounded-md">
                                                    <p class="font-bold">Warning!</p>
                                                    <p>Pool Needs a Backwash</p>
                                                </div>
                                            </div>
                                            <div v-else>
                                                <div class="bg-green-500 text-white p-4 rounded-md shadow">
                                                    <p>No Need to Backwash This week</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div v-else>
                                        <div class="bg-red-500 text-white p-4 rounded-md">
                                            <p class="font-bold">Warning!</p>
                                            <p>Please record the filter type</p>
                                        </div>
                                    </div>

                                    <br>

                                    <div class="flex flex-col"
                                         :class="errorClass"
                                    >
                                        <label :class="errorClass" for="timeIn">Time In <span
                                            v-if="errorClass">- This is Required</span></label>
                                        <input
                                            id="timeIn"
                                            type="datetime-local" v-model="form.timeIn">
                                    </div>

                                    <div class="flex flex-col">
                                        <label for="timeOut">Time Out</label>
                                        <input
                                            id="timeOut"
                                            type="datetime-local" v-model="form.timeOut">
                                    </div>


                                    <div
                                        v-if="form.service_type === 'Repair' ||
                                              form.service_type === 'Intro'"
                                        class="flex flex-col">
                                        <label for="checkedChems">Checked Chems</label>
                                        <Switch
                                            @click="form.checkedChems = !form.checkedChems"
                                            id="checkedChems" v-model="form.checkedChems"
                                            :class="[form.checkedChems ? 'bg-indigo-600' : 'bg-gray-200', 'relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500']">
                                            <span class="sr-only">Use setting</span>
                                            <span aria-hidden="true"
                                                  :class="[form.checkedChems ? 'translate-x-5' : 'translate-x-0', 'pointer-events-none inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200']"/>
                                        </Switch>
                                        <div class="mt-2">{{ form.checkedChems }}</div>
                                    </div>

                                    <div v-if="
                  form.service_type === 'Repair' ||
                  form.service_type === 'Intro'
">
                                        <div
                                            v-if="form.checkedChems"
                                            class="col-span-1">
                                            <label for="chlorine" class="block text-sm font-medium text-gray-700">Chlorine</label>
                                            <select id="chlorine" name="chlorine"
                                                    v-model="form.chlorine_level"
                                                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                                <option
                                                    v-for="option in [ '0.0', '1.0', '2.0', '3.0', '4.0', '5.0', '6.0', '7.0', '8.0', '9.0', '10.0']">
                                                    {{ option }}
                                                </option>
                                            </select>
                                        </div>

                                        <div v-if="form.checkedChems"></div>

                                        <div v-if="form.checkedChems" class="col-span-1">
                                            <label for="pH" class="block text-sm font-medium text-gray-700">pH</label>
                                            <select id="pH" name="pH"
                                                    v-model="form.ph_level"
                                                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                                <option v-for="option in [ '7.0', '7.2', '7.4', '7.6', '7.8', '8.0' ]">
                                                    {{ option }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>

                                    <div
                                        v-if="form.service_type !== 'Repair' &&
                                              form.service_type !== 'Intro' &&
                      form.service_type !== 'Missed Service'"
                                        class="col-span-1">
                                        <label for="chlorine"
                                               class="block text-sm font-medium text-gray-700">Chlorine</label>
                                        <select id="chlorine" name="chlorine"
                                                v-model="form.chlorine_level"
                                                class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                            <option
                                                v-for="option in [ '0.0', '1.0', '2.0', '3.0', '4.0', '5.0', '6.0', '7.0', '8.0', '9.0', '10.0']">
                                                {{ option }}
                                            </option>
                                        </select>
                                    </div>
                                    <div
                                        v-if="form.service_type !== 'Repair' &&
                                              form.service_type !== 'Intro' &&
                      form.service_type !== 'Missed Service'"
                                        class="col-span-1">
                                        <label for="pH" class="block text-sm font-medium text-gray-700">pH</label>
                                        <select id="pH" name="pH"
                                                v-model="form.ph_level"
                                                class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                            <option v-for="option in [ '7.0', '7.2', '7.4', '7.6', '7.8', '8.0' ]">
                                                {{ option }}
                                            </option>
                                        </select>
                                    </div>

                                    <div>
                                        <div class="col-span-1">
                                            <label for="tabsWholeMine" class="block text-sm font-medium text-gray-700">My
                                                Tabs</label>
                                            <select id="tabsWholeMine" name="tabsWholeMine"
                                                    v-model="form.tabsWholeMine"
                                                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                                <option v-for="option in [ '0','1','2','3','4','5','6','7','8','9','10',
                                        '11','12','13','14','15','16','17','18','19','20']">{{ option }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>

                                    <div>
                                        <div class="col-span-1">
                                            <label for="tabsWholeTheirs"
                                                   class="block text-sm font-medium text-gray-700">Their Tabs</label>
                                            <select id="tabsWholeTheirs" name="tabsWholeTheirs"
                                                    v-model="form.tabsWholeTheirs"
                                                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                                <option v-for="option in [ '0','1','2','3','4','5','6','7','8','9','10',
                                        '11','12','13','14','15','16','17','18','19','20']">{{ option }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>

                                    <div>
                                        <div class="col-span-1">
                                            <label for="liquidChlorine" class="block text-sm font-medium text-gray-700">Liquid
                                                Chlorine - gallon(s)</label>
                                            <select id="liquidChlorine" name="liquidChlorine"
                                                    v-model="form.liquidChlorine"
                                                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                                <option v-for="option in [ '0.0','0.5', '1.0', '1.5', '2.0', '2.5', '3.0', '3.5',
                                                           '4.0', '4.5', '5.0', '5.5', '6.0', '6.5',
                                                           '7.0', '7.5', '8.0', '8.5', '9.0', '9.5',
                                                           '10.0', '10.5', '11.0', '11.5', '12.0', '12.5',
                                                           '13.0', '13.5', '14.0', '14.5', '15.0', '15.5',
                                                           '16.0', '16.5', '17.0', '17.5', '18.0', '18.5',
                                                           '19.0', '19.5', '20.0', '20.5', '21.0', '21.5'
                                                           ]">{{ option }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>

                                    <div>
                                        <div class="col-span-1">
                                            <label for="acid" class="block text-sm font-medium text-gray-700">Acid -
                                                gallon(s)</label>
                                            <select id="acid" name="acid"
                                                    v-model="form.acid"
                                                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                                <option v-for="option in [
                                                           '0.0','0.25', '0.5', '0.75', '1.0', '1.25', '1.5',
                                                           '1.75', '2.0', '2.25', '2.5', '2.75', '3.0'
                                                           ]">{{ option }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>

                                    <div>
                                        <div class="col-span-1">
                                            <label for="salt_level" class="block text-sm font-medium text-gray-700">Salt
                                                Level</label>
                                            <select id="salt_level" name="salt_level"
                                                    v-model="form.salt_level"
                                                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                                <option v-for="option in saltRange">{{ option }}</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div>
                                        <div class="col-span-1">
                                            <label for="powder_chlorine"
                                                   class="block text-sm font-medium text-gray-700">Powder
                                                Chlorine</label>
                                            <select id="powder_chlorine" name="powder_chlorine"
                                                    v-model="form.powder_chlorine"
                                                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                                <option v-for="option in powderChlorineRange">{{ option }}</option>
                                            </select>
                                        </div>
                                    </div>


                                    <div>
                                        <div class="col-span-1">
                                            <label for="powder_chlorine"
                                                   class="block text-sm font-medium text-gray-700">Super Black Algeacide (oz)</label>
                                            <input
                                                id="quantity"
                                                v-model="form.super_black_algaecide"
                                                type="number"
                                                class="block w-1/2 rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                            >
                                        </div>
                                    </div>


                                    <div>
                                        <div class="col-span-1">
                                            <label for="powder_chlorine"
                                                   class="block text-sm font-medium text-gray-700">No Phos (oz)</label>
                                            <input
                                                id="quantity"
                                                v-model="form.no_phos"
                                                type="number"
                                                class="block w-1/2 rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                            >
                                        </div>
                                    </div>






                                    <div>
                                        <div class="col-span-1">
                                            <label for="powder_chlorine"
                                                   class="block text-sm font-medium text-gray-700">Filter Type</label>
                                            <select id="powder_chlorine" name="powder_chlorine"
                                                    v-model="form.filter_type"
                                                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                                <option v-for="option in ['de', 'cartridge', 'sand']">{{ option }}</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div>
                                        <div class="col-span-1">
                                            <label for="powder_chlorine"
                                                   class="block text-sm font-medium text-gray-700">Cyanuric Acid<span v-if="cya"> - {{ cya.tested_date }}</span></label>
                                            <select id="powder_chlorine" name="powder_chlorine"
                                                    v-model="form.cya"
                                                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                                <option v-for="option in [
                                                    0,
                                                    10,
                                                    20,
                                                    30,
                                                    40,
                                                    50,
                                                    60,
                                                    70,
                                                    80,
                                                    90,
                                                    100
                                                ]">{{ option }}</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="flex flex-col">
                                        <label for="emptyBaskets">Empty Baskets</label>
                                        <Switch
                                            @click="form.emptyBaskets = !form.emptyBaskets"
                                            id="emptyBaskets" v-model="form.emptyBaskets"
                                            :class="[form.emptyBaskets ? 'bg-indigo-600' : 'bg-gray-200', 'relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500']">
                                            <span class="sr-only">Use setting</span>
                                            <span aria-hidden="true"
                                                  :class="[form.emptyBaskets ? 'translate-x-5' : 'translate-x-0', 'pointer-events-none inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200']"/>
                                        </Switch>
                                        <div class="mt-2">{{ form.emptyBaskets }}</div>
                                    </div>

                                    <div class="flex flex-col">
                                        <label for="vacuum">Vacuum</label>
                                        <Switch
                                            @click="form.vacuum = !form.vacuum"
                                            id="vacuum" v-model="form.vacuum"
                                            :class="[form.vacuum ? 'bg-indigo-600' : 'bg-gray-200', 'relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500']">
                                            <span class="sr-only">Use setting</span>
                                            <span aria-hidden="true"
                                                  :class="[form.vacuum ? 'translate-x-5' : 'translate-x-0', 'pointer-events-none inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200']"/>
                                        </Switch>
                                        <div class="mt-2">{{ form.vacuum }}</div>
                                    </div>

                                    <div class="flex flex-col">
                                        <label for="brush">Brush</label>
                                        <Switch
                                            @click="form.brush = !form.brush"
                                            id="brush" v-model="form.brush"
                                            :class="[form.brush ? 'bg-indigo-600' : 'bg-gray-200', 'relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500']">
                                            <span class="sr-only">Use setting</span>
                                            <span aria-hidden="true"
                                                  :class="[form.brush ? 'translate-x-5' : 'translate-x-0', 'pointer-events-none inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200']"/>
                                        </Switch>
                                        <div class="mt-2">{{ form.brush }}</div>
                                    </div>

                                    <div class="flex flex-col">
                                        <label for="backwash">Backwash</label>
                                        <Switch
                                            @click="form.backwash = !form.backwash"
                                            id="backwash" v-model="form.backwash"
                                            :class="[form.backwash ? 'bg-indigo-600' : 'bg-gray-200', 'relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500']">
                                            <span class="sr-only">Use setting</span>
                                            <span aria-hidden="true"
                                                  :class="[form.backwash ? 'translate-x-5' : 'translate-x-0', 'pointer-events-none inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200']"/>
                                        </Switch>
                                        <div class="mt-2">{{ form.backwash }}</div>
                                    </div>

                                    <div class="flex flex-col col-span-2">
                                        <label
                                            class="block text-sm font-medium text-gray-700"
                                            for="notes">Notes</label>
                                        <textarea
                                            class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                            name="notes" id="notes" cols="30" rows="10" v-model="form.notes">
                                    </textarea>
                                    </div>

                                    <div></div>

                                </div>
                                <div class="flex justify-around">
<!--                                    <button @click="submit()"-->
<!--                                            class="mt-4 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">-->
<!--                                        Save - All Customers-->
<!--                                    </button>-->
                                    <button @click="submitToCustomer()"
                                            class="mt-4 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        Save - Customer
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="mt-24">
                </div>
            </div>
        </div>
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
    name: "Create",
    components: {
        DropDown,
        LoadingButton,
        Link,
        Layout,
        Toggle
    },
    props: {
        customerId: String,
        customer: String,
        address: String,
        equipment: String,
        cya: String,
        lastBackwash: String,
        customerName: String,
        user: String,
        tasks: Array
    },
    data() {
        return {
            saltRange: [
                'not checked',
                'low',
                '400',
                '500',
                '600',
                '700',
                '800',
                '900',
                '1000',
                '1100',
                '1200',
                '1300',
                '1400',
                '1500',
                '1600',
                '1700',
                '1800',
                '1900',
                '2000',
                '2100',
                '2200',
                '2300',
                '2400',
                '2500',
                '2600',
                '2700',
                '2800',
                '2900',
                '3000',
                '3100',
                '3200',
                '3300',
                '3400',
                '3500',
                '3600',
                '3700',
                '3800',
                '3900',
                '4000',
                '4100',
                '4200',
                '4300',
                '4400',
                '4500',
                '4600',
                'high'
            ],
            powderChlorineRange: [
                '0',
                '1',
                '2',
                '3',
                '4',
                '5',
                '6',
                '7',
                '8',
                '9',
                '10',
                '11',
                '12',
                '13',
                '14',
                '15',
                '16',
                '17',
                '18',
                '19',
                '20',
                '21',
                '22',
                '23',
                '24',
                '25',
                '26',
                '27',
                '28',
                '29',
                '30',
            ]
        }
    },
    remember: 'form',
    setup() {
        const form = reactive({
            id: null,
            acid: '0.0',
            backwash: false,
            address: null,
            brush: true,
            chlorine_level: null,
            checkedChems: true,
            customerId: null,
            cya: null,
            toCustomer: false,
            emptyBaskets: true,
            liquidChlorine: '0.0',
            notes: null,
            ph_level: null,
            salt_level: 'not checked',
            powder_chlorine: '0',
            super_black_algaecide: '0',
            no_phos: '0',
            tabsCrushedMine: '0',
            tabsCrushedTheirs: '0',
            tabsWholeMine: '0',
            tabsWholeTheirs: '0',
            filter_type: null,
            newTimeIn: null,
            newTimeOut: null,
            timeIn: null,
            timeOut: null,
            vacuum: null,
            service_type: 'Service Stop'
        })

        const errors = reactive({
            id: null,
            acid: '0.0',
            backwash: false,
            brush: true,
            chlorine_level: null,
            checkedChems: true,
            customerId: null,
            toCustomer: false,
            emptyBaskets: true,
            liquidChlorine: '0.0',
            notes: null,
            ph_level: null,
            powder_chlorine: '0',
            tabsCrushedMine: '0',
            tabsCrushedTheirs: '0',
            tabsWholeMine: '0',
            tabsWholeTheirs: '0',
            filter_type: null,
            newTimeIn: null,
            newTimeOut: null,
            timeIn: null,
            timeOut: null,
            vacuum: null
        })

        function formatTime(time) {
            if (time) {
                let split = time.split(":");
                return split[0] + ":" + split[1]
            }
        }

        function store() {

            if (form.service_type === 'Missed Service') {
                form.checkedChems = false
            }

            if (form.timeIn === null) {
                errors.timeIn = true
            } else {
                form.newTimeIn = formatTime(form.timeIn);
                errors.timeIn = false
            }

            if (form.timeOut === null) {
                errors.timeOut = true
            } else {
                form.newTimeOut = formatTime(form.timeOut);
                errors.timeOut = false
            }

            if (form.checkedChems) {
                if (form.ph_level === null) {
                    errors.ph_level = true
                } else {
                    errors.ph_level = false
                }

                if (form.chlorine_level === null) {
                    errors.chlorine_level = true
                } else {
                    errors.chlorine_level = false
                }
            }

            if (form.vacuum === null) {
                form.vacuum = false
            }

            if (form.checkedChems) {
                if (
                    form.timeIn
                    && form.timeOut
                    && form.ph_level
                    && form.chlorine_level
                ) {
                    Inertia.post('/service_stops/store', form)
                }
            } else {
                if (
                    form.timeIn
                    && form.timeOut
                ) {
                    Inertia.post('/service_stops/store', form)
                }
            }

            localStorage.removeItem("timeIn");
            localStorage.removeItem("timeOut");

        }

        return {form, errors, store, formatTime}

    },
    mounted() {
        this.form.id = this.customerId
        this.equipment && this.equipment.type ? this.form.filter_type = this.equipment.type : this.form.filter_type = null;
        this.cya && this.cya.level ? this.form.cya = this.cya.level : this.form.cya = null;
        if (localStorage.getItem('timeIn')) {
            this.form.timeIn = localStorage.getItem('timeIn')
        }
        if (localStorage.getItem('timeOut')) {
            this.form.timeIn = localStorage.getItem('timeOut')
        }
    },
    methods: {
        submit() {
            this.form.address = this.address.id;
            this.store()
        },
        submitToCustomer() {
            this.form.address = this.address.id;
            this.form.customerId = this.customer.id;
            this.form.toCustomer = true;
            this.store()
        },
        completed(item) {
            Inertia.post('/task/completed', item)
        },
    },
    computed: {
        errorClass() {
            return this.errors.timeIn ? 'text-red-600' : ''
        },
        saveTimeIn() {
            if (this.form.timeIn) {
                localStorage.setItem('timeIn', this.form.timeIn)
            }
            return this.formatTime(this.form.timeIn)
        },
        saveTimeOut() {
            if (this.form.timeOut) {
                localStorage.setItem('timeOut', this.form.timeOut)
            }
            return this.formatTime(this.form.timeOut)
        }
    }
}
</script>

<style scoped>

</style>

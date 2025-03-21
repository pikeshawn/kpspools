<template>
    <layout
        title="Add Customer"
        :user="user"
    >
    <div class="mt-10 sm:mt-0">
        <div class="md:grid md:grid-cols-3 md:gap-6">
            <!--                <div class="md:col-span-1">-->
            <!--                    <div class="px-4 sm:px-0">-->
            <!--                        <h3 class="text-lg font-medium leading-6 text-gray-900">Personal</h3>-->
            <!--                        <p class="mt-1 text-sm text-gray-600">-->
            <!--                            Information-->
            <!--                        </p>-->
            <!--                    </div>-->
            <!--                </div>-->
            <div class="mt-5 md:mt-0 md:col-span-2">
                <div class="shadow overflow-hidden sm:rounded-md">
                    <div class="px-4 py-5 bg-white sm:p-6">
                        <div class="grid grid-cols-6 gap-6">
                            <div class="col-span-6 sm:col-span-6">
                                <div>
                                    <label for="time_in" class="text-sm font-medium text-gray-700">
                                        Time In
                                    </label>
                                </div>
                                <div>
                                    <input
                                        id="time_in"
                                        type="datetime-local"
                                        v-model="form.personal_information.time_in">
                                </div>
                                <br>
                                <div>
                                    <label for="time_out" class="text-sm font-medium text-gray-700">
                                        Time Out
                                    </label>
                                </div>
                                <div>
                                    <input
                                        id="time_out"
                                        type="datetime-local"
                                        v-model="form.personal_information.initial_consultation_date">
                                </div>
                                <br>
                                <div>
                                    <label for="initialConsultationDate" class="text-sm font-medium text-gray-700">
                                        Initial Consultation Date
                                    </label>
                                </div>
                                <div>
                                    <input
                                        id="initialConsultationDate"
                                        type="datetime-local"
                                        v-model="form.personal_information.initial_consultation_date">
                                </div>
                                <br>
                                <div>
                                    <label for="startDate" class="text-sm font-medium text-gray-700">Start
                                        Date</label>
                                </div>
                                <div>
                                    <input
                                        id="startDate"
                                        type="datetime-local" v-model="form.personal_information.start_date">
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-6">
                                <div class="col-span-6 sm:col-span-6">
                                    <label for="primaryPhone"
                                           class="block text-sm font-medium leading-6 text-gray-900">Phone
                                        Number</label>
                                    <div class="relative mt-2 rounded-md shadow-sm">
                                        <input type="text" name="phone-number" id="primaryPhone"
                                               v-model="form.personal_information.primary_phone"
                                               class="block w-full rounded-md border-0 py-1.5 pl-16 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"/>
                                    </div>
                                </div>
                                <br>
                                <div class="col-span-6 sm:col-span-6">
                                    <label for="secondaryPhone"
                                           class="block text-sm font-medium leading-6 text-gray-900">Secondary
                                        Number</label>
                                    <div class="relative mt-2 rounded-md shadow-sm">
                                        <input type="text" name="phone-number" id="secondaryPhone"
                                               v-model="form.personal_information.secondary_phone"
                                               class="block w-full rounded-md border-0 py-1.5 pl-16 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"/>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-2" v-if="form.timeIn">{{ saveTimeIn }}</div>
                            <div class="col-span-6 sm:col-span-6">
                                <label class="text-sm font-medium text-gray-700">Owner</label></div>
                            <div class="col-span-6 sm:col-span-3">
                                <label for="first-name" class="block text-sm font-medium text-gray-700">First
                                    name</label>
                                <input type="text" name="first-name" id="first-name" autocomplete="given-name"
                                       v-model="form.personal_information.first_name"
                                       class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"/>
                            </div>

                            <div class="col-span-6 sm:col-span-3">
                                <label for="last-name" class="block text-sm font-medium text-gray-700">Last
                                    name</label>
                                <input type="text" name="last-name" id="last-name" autocomplete="family-name"
                                       v-model="form.personal_information.last_name"
                                       class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"/>
                            </div>

                            <div class="col-span-6 sm:col-span-6">
                                <label class="text-sm font-medium text-gray-700">Renter</label>
                            </div>

                            <div class="col-span-6 sm:col-span-3">
                                <label for="first-name" class="block text-sm font-medium text-gray-700">First
                                    name</label>
                                <input type="text" name="first-name" id="first-name_2" autocomplete="given-name"
                                       v-model="form.personal_information.first_name_2"
                                       class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"/>
                            </div>
                            <div class="col-span-6 sm:col-span-3">
                                <label for="last-name" class="block text-sm font-medium text-gray-700">Last
                                    name</label>
                                <input type="text" name="last-name" id="last-name_2" autocomplete="family-name"
                                       v-model="form.personal_information.last_name_2"
                                       class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"/>
                            </div>


                            <div class="col-span-6 sm:col-span-4">
                                <label for="plan_price" class="block text-sm font-medium text-gray-700">Plan
                                    Price</label>
                                <input type="text" name="email-address" id="plan_price" autocomplete="email"
                                       v-model="form.personal_information.plan_price"
                                       class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"/>
                            </div>

                            <div class="col-span-6 sm:col-span-4">
                                <label for="plan_duration" class="block text-sm font-medium text-gray-700">Plan
                                    Duration</label>
                                <input type="text" name="email-address" id="plan_duration" autocomplete="email"
                                       v-model="form.personal_information.plan_duration"
                                       class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"/>
                            </div>

                            <div class="relative flex items-start">
                                <div class="flex items-center h-5">
                                    <label for="type" class="font-medium text-gray-700">Service Customer</label>
                                    <input id="type" name="type" type="checkbox"
                                           v-model="form.personal_information.type"
                                           class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded"/>
                                </div>

                            </div>

                            <div class="relative flex items-start">
                                <div class="flex items-center h-5">
                                    <div class="ml-3 text-sm">
                                        <label for="chems_included" class="font-medium text-gray-700">Chems
                                            Included</label>
                                    </div>
                                    <input id="chems_included" name="type" type="checkbox"
                                           v-model="form.personal_information.chems_included"
                                           class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded"/>
                                </div>
                            </div>

                            <div class="col-span-6 sm:col-span-3">
                                <label for="plan" class="block text-sm font-medium text-gray-700">Plan</label>
                                <select id="plan" name="plan" autocomplete="plan"
                                        v-model="form.personal_information.plan"
                                        class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option>Monthly 100 Includes Chems</option>
                                    <option>Monthly 100 Excludes Chems</option>
                                </select>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-10">
        <div class="mt-10 sm:mt-0">
            <div class="md:grid md:grid-cols-3 md:gap-6">
                <div class="mt-5 md:mt-0 md:col-span-2">
                    <div class="shadow overflow-hidden sm:rounded-md">
                        <div class="px-4 py-5 bg-white sm:p-6">
                            <div class="grid grid-cols-6 gap-6">
                                <div class="col-span-6 sm:col-span-3">
                                    <label for="pool_difficulty" class="block text-sm font-medium text-gray-700">Pool
                                        Difficulty</label>
                                    <select id="pool_difficulty" name="country" autocomplete="country"
                                            v-model="form.personal_information.pool_difficulty"
                                            class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                        <option>Easy</option>
                                        <option>Medium</option>
                                        <option>Hard</option>
                                    </select>
                                </div>

                                <div class="col-span-6 sm:col-span-6 lg:col-span-2">
                                    <label for="gallons"
                                           class="block text-sm font-medium text-gray-700">Gallons</label>
                                    <input type="number" name="city" id="gallons"
                                           v-model="form.personal_information.gallons"
                                           class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"/>
                                </div>

                                <div class="col-span-6">
                                    <label for="service_man" class="block text-sm font-medium text-gray-700">Service
                                        Man</label>
                                    <select id="service_man" name="country" autocomplete="country"
                                            v-model="form.personal_information.service_man"
                                            class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white
                                                rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                    >
                                        <option>Jeremiah</option>
                                        <option>Reid</option>
                                        <option>Zach</option>
                                    </select>
                                </div>

                                <div class="col-span-6">
                                    <label for="street-address" class="block text-sm font-medium text-gray-700">Street
                                        address</label>
                                    <input type="text" name="street-address" id="street-address"
                                           autocomplete="street-address"
                                           class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"/>
                                </div>

                                <div class="col-span-6 sm:col-span-6 lg:col-span-2">
                                    <label for="city" class="block text-sm font-medium text-gray-700">City</label>
                                    <input type="text" name="city" id="city"
                                           class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"/>
                                </div>

                                <div class="col-span-6 sm:col-span-3 lg:col-span-2">
                                    <label for="state" class="block text-sm font-medium text-gray-700">State /
                                        Province</label>
                                    <input type="text" name="state" id="state"
                                           class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"/>
                                </div>

                                <div class="col-span-6 sm:col-span-3 lg:col-span-2">
                                    <label for="postal-code" class="block text-sm font-medium text-gray-700">ZIP /
                                        Postal</label>
                                    <input type="text" name="postal-code" id="postal-code"
                                           autocomplete="postal-code"
                                           class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"/>
                                </div>
                                <div class="relative flex items-start">
                                    <div class="flex items-center h-5">
                                        <label for="saturday_service" class="font-medium text-gray-700">Saturday
                                            Service</label>
                                        <input id="saturday_service" name="type" type="checkbox"
                                               v-model="form.personal_information.saturday_service"
                                               class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded"/>
                                    </div>
                                </div>
                                <div class="relative flex items-start">
                                    <div class="flex items-center h-5">
                                        <label for="texting_ok" class="font-medium text-gray-700">Texting?</label>
                                        <input id="texting_ok" name="type" type="checkbox"
                                               v-model="form.personal_information.texting_ok"
                                               class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded"/>
                                    </div>
                                </div>
                                <div class="col-span-6">
                                    <label for="preferred_communication"
                                           class="block text-sm font-medium text-gray-700">
                                        Preferred Communication
                                    </label>
                                    <select id="preferred_communication" name="country" autocomplete="country"
                                            v-model="form.personal_information.preferred_communication"
                                            class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white
                                                rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                    >
                                        <option>Text</option>
                                        <option>Email</option>
                                        <option>Phone Call</option>
                                        <option>Mail</option>
                                    </select>
                                </div>
                                <div class="relative flex items-start">
                                    <div class="flex items-center h-5">
                                        <label for="early_morning" class="font-medium text-gray-700">Early
                                            Morning</label>
                                        <input id="early_morning" name="type" type="checkbox"
                                               v-model="form.personal_information.early_morning"
                                               class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded"/>
                                    </div>
                                </div>
                                <div class="col-span-6">
                                    <label for="filter_type" class="block text-sm font-medium text-gray-700">
                                        Filter Type
                                    </label>
                                    <select id="filter_type" name="country" autocomplete="country"
                                            v-model="form.personal_information.filter_type"
                                            class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white
                                                rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                    >
                                        <option>DE</option>
                                        <option>Cartridge</option>
                                        <option>Sand</option>
                                    </select>
                                </div>
                                <div class="col-span-6">
                                    <label for="surface_type" class="block text-sm font-medium text-gray-700">
                                        Surface Type
                                    </label>
                                    <select id="surface_type" name="country" autocomplete="country"
                                            v-model="form.personal_information.surface_type"
                                            class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white
                                                rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                    >
                                        <option>Plaster</option>
                                        <option>Pebble</option>
                                    </select>
                                </div>
                                <div class="col-span-6">
                                    <label for="pump_type" class="block text-sm font-medium text-gray-700">
                                        Pump Type
                                    </label>
                                    <select id="pump_type" name="country" autocomplete="country"
                                            v-model="form.personal_information.pump_type"
                                            class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white
                                                rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                    >
                                        <option>Hayward VS</option>
                                        <option>Pentair VS</option>
                                        <option>Single Speed</option>
                                    </select>
                                </div>
                                <div class="col-span-6">
                                    <label for="chlorine" class="block text-sm font-medium text-gray-700">
                                        Chlorine Type
                                    </label>
                                    <select id="chlorine" name="country" autocomplete="country"
                                            v-model="form.personal_information.chlorine"
                                            class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white
                                                rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                    >
                                        <option>Tabs</option>
                                        <option>Salt</option>
                                    </select>
                                </div>
                                <div class="relative flex items-start">
                                    <div class="flex items-center h-5">
                                        <label for="automation" class="font-medium text-gray-700">Automation</label>
                                        <input id="automation" name="type" type="checkbox"
                                               v-model="form.personal_information.automation"
                                               class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded"/>
                                    </div>
                                </div>
                                <div class="col-span-6">
                                    <label for="automation_type" class="block text-sm font-medium text-gray-700">
                                        Automation Type
                                    </label>
                                    <select id="automation_type" name="country" autocomplete="country"
                                            v-model="form.personal_information.automation_type"
                                            class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white
                                                rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                    >
                                        <option>Pentair Intellitouch</option>
                                        <option>Aqualink</option>
                                        <option>Intermatic timer</option>
                                    </select>
                                </div>
                                <div class="col-span-6">
                                    <label for="number_of_lights" class="block text-sm font-medium text-gray-700">
                                        Number Of Lights
                                    </label>
                                    <select id="number_of_lights" name="country" autocomplete="country"
                                            v-model="form.personal_information.number_of_lights"
                                            class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white
                                                rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                    >
                                        <option>0</option>
                                        <option>1</option>
                                        <option>2</option>
                                        <option>3</option>
                                        <option>4</option>
                                        <option>5</option>
                                    </select>
                                </div>
                                <div class="relative flex items-start">
                                    <label class="font-medium text-gray-700">Light Type</label>
                                </div>
                                <br>
                                <div class="flex">
                                    <div class="flex-grow">
                                        <div>
                                            <label for="fiber_optic" class="font-medium text-gray-700 w-36 mr-2">Fiber
                                                Optic</label>
                                            <input id="fiber_optic" name="type" type="checkbox"
                                                   v-model="form.personal_information.fiber_optic"
                                                   class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded"/>
                                        </div>
                                        <div>
                                            <label for="amerilite"
                                                   class="font-medium text-gray-700 w-36 mr-4">Amerlite</label>
                                            <input id="amerilite" name="type" type="checkbox"
                                                   v-model="form.personal_information.amerilite"
                                                   class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded"/>
                                        </div>
                                        <div>
                                            <label for="led" class="font-medium text-gray-700 w-36 mr-4">LED</label>
                                            <input id="led" name="type" type="checkbox"
                                                   v-model="form.personal_information.led"
                                                   class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded"/>
                                        </div>
                                        <div>
                                            <label for="spa_bulb" class="font-medium text-gray-700 w-36 mr-4">Spa
                                                light</label>
                                            <input id="spa_bulb" name="type" type="checkbox"
                                                   v-model="form.personal_information.spa_bulb"
                                                   class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="relative flex items-start">
                                    <div class="flex items-center h-5">
                                        <label for="dogs" class="font-medium text-gray-700">Dogs</label>
                                        <input id="dogs" name="type" type="checkbox"
                                               v-model="form.personal_information.dogs"
                                               class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded"/>
                                    </div>
                                </div>
                                <div class="col-span-6 sm:col-span-6 lg:col-span-2">
                                    <label for="dog_name_1"
                                           class="block text-sm font-medium text-gray-700">Dog Name 1</label>
                                    <input type="number" name="city" id="dog_name_1"
                                           v-model="form.personal_information.dog_name_1"
                                           class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"/>
                                </div>
                                <div class="col-span-6 sm:col-span-6 lg:col-span-2">
                                    <label for="dog_name_2"
                                           class="block text-sm font-medium text-gray-700">Dog Name 2</label>
                                    <input type="number" name="city" id="dog_name_2"
                                           v-model="form.personal_information.dog_name_2"
                                           class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"/>
                                </div>
                                <div class="col-span-6 sm:col-span-6 lg:col-span-2">
                                    <label for="dog_name_3"
                                           class="block text-sm font-medium text-gray-700">Dog Name 3</label>
                                    <input type="number" name="city" id="dog_name_3"
                                           v-model="form.personal_information.dog_name_3"
                                           class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"/>
                                </div>
                                <div class="col-span-6 sm:col-span-6 lg:col-span-2">
                                    <label for="dog_name_4"
                                           class="block text-sm font-medium text-gray-700">Dog Name 4</label>
                                    <input type="number" name="city" id="dog_name_4"
                                           v-model="form.personal_information.dog_name_4"
                                           class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"/>
                                </div>
                                <div class="col-span-6">
                                    <label for="special_instructions_dog"
                                           class="block text-sm font-medium leading-6 text-gray-900">Special
                                        Instructions for Dog</label>
                                    <div class="mt-2">
                                        <textarea rows="4" name="comment" id="special_instructions_dog"
                                                  class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"/>
                                    </div>
                                </div>

                                <div class="relative flex items-start">
                                    <div class="flex items-center h-5">
                                        <label for="gate_locked" class="font-medium text-gray-700">Gate Locked</label>
                                        <input id="gate_locked" name="type" type="checkbox"
                                               v-model="form.personal_information.gate_locked"
                                               class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded"/>
                                    </div>
                                </div>

                                <div class="relative flex items-start">
                                    <div class="flex items-center h-5">
                                        <label for="key" class="font-medium text-gray-700">Key</label>
                                        <input id="key" name="type" type="checkbox"
                                               v-model="form.personal_information.key"
                                               class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded"/>
                                    </div>
                                </div>

                                <div class="col-span-6">
                                    <label for="code" class="block text-sm font-medium text-gray-700">
                                        Code
                                    </label>
                                    <input type="text" name="street-address" id="code"
                                           class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"/>
                                </div>

                                <div class="col-span-6">
                                    <label for="special_instructions_gate"
                                           class="block text-sm font-medium leading-6 text-gray-900">Special
                                        Instructions for Gate</label>
                                    <div class="mt-2">
                                        <textarea rows="4" name="comment" id="special_instructions_gate"
                                                  class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"/>
                                    </div>
                                </div>

                                <div class="col-span-6">
                                    <label for="repairs" class="block text-sm font-medium leading-6 text-gray-900">Repairs</label>
                                    <div class="mt-2">
                                        <textarea rows="4" name="comment" id="repairs"
                                                  class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"/>
                                    </div>
                                </div>

                                <div>
                                    <div class="col-span-1">
                                        <label for="chlorine_level" class="block text-sm font-medium text-gray-700">Chlorine</label>
                                        <select id="chlorine_level" name="chlorine"
                                                v-model="form.chlorine_level"
                                                class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                            <option
                                                v-for="option in [ '0.0', '1.0', '2.0', '3.0', '4.0', '5.0', '6.0', '7.0', '8.0', '9.0', '10.0']">
                                                {{ option }}
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <div>
                                    <div class="col-span-1">
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

                                <div class="col-span-1">
                                    <label for="number_of_drops" class="block text-sm font-medium text-gray-700">
                                        Number Of Drops
                                    </label>
                                    <select id="number_of_drops" name="country" autocomplete="country"
                                            v-model="form.personal_information.number_of_drops"
                                            class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white
                                                rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                    >
                                        <option>0</option>
                                        <option>1</option>
                                        <option>2</option>
                                        <option>3</option>
                                        <option>4</option>
                                        <option>5</option>
                                        <option>6</option>
                                        <option>7</option>
                                        <option>8</option>
                                        <option>9</option>
                                        <option>10</option>
                                        <option>11</option>
                                        <option>12</option>
                                        <option>12+</option>
                                    </select>
                                </div>


                                <div class="col-span-1">
                                    <label for="tds" class="block text-sm font-medium text-gray-700">
                                        TDS
                                    </label>
                                    <select id="tds" name="country" autocomplete="country"
                                            v-model="form.personal_information.tds"
                                            class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white
                                                rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                    >
                                        <option>High</option>
                                        <option>Medium</option>
                                        <option>Low</option>
                                    </select>
                                </div>

                                <!--                                    <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">-->
                                <!--                                        <button type="submit"-->
                                <!--                                                @click="addAddressFields()"-->
                                <!--                                                class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">-->
                                <!--                                            Add An Address-->
                                <!--                                        </button>-->
                                <!--                                    </div>-->
                                <!--                                    <div id="additionalAddress"></div>-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!--        <div>-->
    <!--            <div class="md:grid md:grid-cols-3 md:gap-6">-->
    <!--                <div class="md:col-span-1">-->
    <!--                    <div class="px-4 sm:px-0">-->
    <!--                        <h3 class="text-lg font-medium leading-6 text-gray-900">Profile</h3>-->
    <!--                        <p class="mt-1 text-sm text-gray-600">-->
    <!--                            This information will be displayed publicly so be careful what you share.-->
    <!--                        </p>-->
    <!--                    </div>-->
    <!--                </div>-->
    <!--                <div class="mt-5 md:mt-0 md:col-span-2">-->
    <!--                    <form action="#" method="POST">-->
    <!--                        <div class="shadow sm:rounded-md sm:overflow-hidden">-->
    <!--                            <div class="px-4 py-5 bg-white space-y-6 sm:p-6">-->
    <!--                                <div class="grid grid-cols-3 gap-6">-->
    <!--                                    <div class="col-span-3 sm:col-span-2">-->
    <!--                                        <label for="company-website" class="block text-sm font-medium text-gray-700">-->
    <!--                                            Website-->
    <!--                                        </label>-->
    <!--                                        <div class="mt-1 flex rounded-md shadow-sm">-->
    <!--                    <span-->
    <!--                        class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">-->
    <!--                      http://-->
    <!--                    </span>-->
    <!--                                            <input type="text" name="company-website" id="company-website"-->
    <!--                                                   class="focus:ring-indigo-500 focus:border-indigo-500 flex-1 block w-full rounded-none rounded-r-md sm:text-sm border-gray-300"-->
    <!--                                                   placeholder="www.example.com"/>-->
    <!--                                        </div>-->
    <!--                                    </div>-->
    <!--                                </div>-->

    <!--                                <div>-->
    <!--                                    <label for="about" class="block text-sm font-medium text-gray-700">-->
    <!--                                        About-->
    <!--                                    </label>-->
    <!--                                    <div class="mt-1">-->
    <!--                                        <textarea id="about" name="about" rows="3"-->
    <!--                                                  class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 mt-1 block w-full sm:text-sm border border-gray-300 rounded-md"-->
    <!--                                                  placeholder="you@example.com"/>-->
    <!--                                    </div>-->
    <!--                                    <p class="mt-2 text-sm text-gray-500">-->
    <!--                                        Brief description for your profile. URLs are hyperlinked.-->
    <!--                                    </p>-->
    <!--                                </div>-->

    <!--                                <div>-->
    <!--                                    <label class="block text-sm font-medium text-gray-700">-->
    <!--                                        Photo-->
    <!--                                    </label>-->
    <!--                                    <div class="mt-1 flex items-center">-->
    <!--                  <span class="inline-block h-12 w-12 rounded-full overflow-hidden bg-gray-100">-->
    <!--                    <svg class="h-full w-full text-gray-300" fill="currentColor" viewBox="0 0 24 24">-->
    <!--                      <path-->
    <!--                          d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z"/>-->
    <!--                    </svg>-->
    <!--                  </span>-->
    <!--                                        <button type="button"-->
    <!--                                                class="ml-5 bg-white py-2 px-3 border border-gray-300 rounded-md shadow-sm text-sm leading-4 font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">-->
    <!--                                            Change-->
    <!--                                        </button>-->
    <!--                                    </div>-->
    <!--                                </div>-->

    <!--                                <div>-->
    <!--                                    <label class="block text-sm font-medium text-gray-700">-->
    <!--                                        Cover photo-->
    <!--                                    </label>-->
    <!--                                    <div-->
    <!--                                        class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">-->
    <!--                                        <div class="space-y-1 text-center">-->
    <!--                                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor"-->
    <!--                                                 fill="none" viewBox="0 0 48 48" aria-hidden="true">-->
    <!--                                                <path-->
    <!--                                                    d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"-->
    <!--                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>-->
    <!--                                            </svg>-->
    <!--                                            <div class="flex text-sm text-gray-600">-->
    <!--                                                <label for="file-upload"-->
    <!--                                                       class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">-->
    <!--                                                    <span>Upload a file</span>-->
    <!--                                                    <input id="file-upload" name="file-upload" type="file"-->
    <!--                                                           class="sr-only"/>-->
    <!--                                                </label>-->
    <!--                                                <p class="pl-1">or drag and drop</p>-->
    <!--                                            </div>-->
    <!--                                            <p class="text-xs text-gray-500">-->
    <!--                                                PNG, JPG, GIF up to 10MB-->
    <!--                                            </p>-->
    <!--                                        </div>-->
    <!--                                    </div>-->
    <!--                                </div>-->
    <!--                            </div>-->
    <!--                            <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">-->
    <!--                                <button type="submit"-->
    <!--                                        class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">-->
    <!--                                    Save-->
    <!--                                </button>-->
    <!--                            </div>-->
    <!--                        </div>-->
    <!--                    </form>-->
    <!--                </div>-->
    <!--            </div>-->
    <!--        </div>-->

    <!--        <div class="hidden sm:block" aria-hidden="true">-->
    <!--            <div class="py-5">-->
    <!--                <div class="border-t border-gray-200"/>-->
    <!--            </div>-->
    <!--        </div>-->

    <!--        <div class="hidden sm:block" aria-hidden="true">-->
    <!--            <div class="py-5">-->
    <!--                <div class="border-t border-gray-200"/>-->
    <!--            </div>-->
    <!--        </div>-->

    <!--                <div class="mt-10 sm:mt-0">-->
    <!--                    <div class="md:grid md:grid-cols-3 md:gap-6">-->
    <!--                        <div class="md:col-span-1">-->
    <!--                            <div class="px-4 sm:px-0">-->
    <!--                                <h3 class="text-lg font-medium leading-6 text-gray-900">Notifications</h3>-->
    <!--                                <p class="mt-1 text-sm text-gray-600">-->
    <!--                                    Decide which communications you'd like to receive and how.-->
    <!--                                </p>-->
    <!--                            </div>-->
    <!--                        </div>-->
    <!--                        <div class="mt-5 md:mt-0 md:col-span-2">-->
    <!--                            <form action="#" method="POST">-->
    <!--                                <div class="shadow overflow-hidden sm:rounded-md">-->
    <!--                                    <div class="px-4 py-5 bg-white space-y-6 sm:p-6">-->
    <!--                                        <fieldset>-->
    <!--                                            <legend class="text-base font-medium text-gray-900">By Email</legend>-->
    <!--                                            <div class="mt-4 space-y-4">-->
    <!--                                                <div class="flex items-start">-->
    <!--                                                    <div class="flex items-center h-5">-->
    <!--                                                        <input id="comments" name="comments" type="checkbox"-->
    <!--                                                               class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded"/>-->
    <!--                                                    </div>-->
    <!--                                                    <div class="ml-3 text-sm">-->
    <!--                                                        <label for="comments" class="font-medium text-gray-700">Comments</label>-->
    <!--                                                        <p class="text-gray-500">Get notified when someones posts a comment on a-->
    <!--                                                            posting.</p>-->
    <!--                                                    </div>-->
    <!--                                                </div>-->
    <!--                                                <div class="flex items-start">-->
    <!--                                                    <div class="flex items-center h-5">-->
    <!--                                                        <input id="type" name="type" type="checkbox"-->
    <!--                                                               class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded"/>-->
    <!--                                                    </div>-->
    <!--                                                    <div class="ml-3 text-sm">-->
    <!--                                                        <label for="type"-->
    <!--                                                               class="font-medium text-gray-700">type</label>-->
    <!--                                                        <p class="text-gray-500">Get notified when a candidate applies for a-->
    <!--                                                            job.</p>-->
    <!--                                                    </div>-->
    <!--                                                </div>-->
    <!--                                                <div class="flex items-start">-->
    <!--                                                    <div class="flex items-center h-5">-->
    <!--                                                        <input id="offers" name="offers" type="checkbox"-->
    <!--                                                               class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded"/>-->
    <!--                                                    </div>-->
    <!--                                                    <div class="ml-3 text-sm">-->
    <!--                                                        <label for="offers" class="font-medium text-gray-700">Offers</label>-->
    <!--                                                        <p class="text-gray-500">Get notified when a candidate accepts or-->
    <!--                                                            rejects an offer.</p>-->
    <!--                                                    </div>-->
    <!--                                                </div>-->
    <!--                                            </div>-->
    <!--                                        </fieldset>-->
    <!--                                        <fieldset>-->
    <!--                                            <div>-->
    <!--                                                <legend class="text-base font-medium text-gray-900">Push Notifications</legend>-->
    <!--                                                <p class="text-sm text-gray-500">These are delivered via SMS to your mobile-->
    <!--                                                    phone.</p>-->
    <!--                                            </div>-->
    <!--                                            <div class="mt-4 space-y-4">-->
    <!--                                                <div class="flex items-center">-->
    <!--                                                    <input id="push-everything" name="push-notifications" type="radio"-->
    <!--                                                           class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300"/>-->
    <!--                                                    <label for="push-everything"-->
    <!--                                                           class="ml-3 block text-sm font-medium text-gray-700">-->
    <!--                                                        Everything-->
    <!--                                                    </label>-->
    <!--                                                </div>-->
    <!--                                                <div class="flex items-center">-->
    <!--                                                    <input id="push-email" name="push-notifications" type="radio"-->
    <!--                                                           class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300"/>-->
    <!--                                                    <label for="push-email"-->
    <!--                                                           class="ml-3 block text-sm font-medium text-gray-700">-->
    <!--                                                        Same as email-->
    <!--                                                    </label>-->
    <!--                                                </div>-->
    <!--                                                <div class="flex items-center">-->
    <!--                                                    <input id="push-nothing" name="push-notifications" type="radio"-->
    <!--                                                           class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300"/>-->
    <!--                                                    <label for="push-nothing"-->
    <!--                                                           class="ml-3 block text-sm font-medium text-gray-700">-->
    <!--                                                        No push notifications-->
    <!--                                                    </label>-->
    <!--                                                </div>-->
    <!--                                            </div>-->
    <!--                                        </fieldset>-->
    <!--                                    </div>-->
    <!--                                    <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">-->
    <!--                                        <button type="submit"-->
    <!--                                                class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">-->
    <!--                                            Save-->
    <!--                                        </button>-->
    <!--                                    </div>-->
    <!--                                </div>-->
    <!--                            </form>-->
    <!--                        </div>-->
    <!--                    </div>-->
    <!--                </div>-->
    </layout>
</template>

<script>
import Layout from "../Shared/Layout.vue";

export default {
    components: {Layout},
    props: {
        user: Object
    },
    data() {
        return {
            form: {
                personal_information: {
                    time_in: null,
                    time_out: null,
                    initial_consultation_date: null,
                    primary_phone: null,
                    secondary_phone: null,
                    start_date: null,
                    first_name: null,
                    last_name: null,
                    first_name_2: null,
                    last_name_2: null,
                    plan_price: 115,
                    plan_duration: "weekly",
                    type: true,
                    chems_included: true,
                    plan: "Monthly 100 Includes Chems",
                    service_day: null
                },
                pool_information: {
                    pool_difficulty: null,
                    service_man: null,
                    saturday_service: null,
                    preferred_communication: null,
                    texting_ok: null,
                    early_morning: null,
                    filter_type: null,
                    surface_type: null,
                    pump_type: null,
                    chlorine: null,
                    automation: null,
                    automation_type: null,
                    number_of_lights: null,
                    fiber_optic: null,
                    amerilite: null,
                    led: null,
                    spa_bulb: null,
                    dogs: null,
                    dog_name_1: null,
                    dog_name_2: null,
                    dog_name_3: null,
                    dog_name_4: null,
                    special_instructions_dog: null,
                    gate_locked: null,
                    key: null,
                    special_instructions_gate: null,
                    code: null,
                    repairs: null,
                    chlorine_level: null,
                    pH: null,
                    tds: null,
                    number_of_drops: null,
                    gallons: null
                },
                addresses: []
            }
        }
    },
    methods: {
        addAddressFields() {
            var field = document.getElementById('additionalAddress')

            // create a new div element
            const newDiv = document.createElement("h1");

            // and give it some content
            ``           // const newContent = document.createTextNode("Hi there and greetings!");

            // add the text node to the newly created div
            field.appendChild(newDiv);
        },
    }
}
</script>

<style scoped>

</style>

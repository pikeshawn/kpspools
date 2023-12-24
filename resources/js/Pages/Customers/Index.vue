<template>
  <layout
      title="Customers"
      :user="user"
  >

      <Combobox as="div" v-model="selectedPerson">
        <ComboboxLabel v-if="selectedPerson" class="block text-sm font-medium leading-6 text-gray-900">{{selectedPerson.first_name }} {{ selectedPerson.last_name }} - {{ selectedPerson.address_line_1 }}</ComboboxLabel>
        <div class="relative mt-2">
          <!--              @change="query = $event.target.value" :display-value="person && (person?.first_name + ' ' + person?.last_name) !== undefined ? '' : person.first_name + ' ' + person.last_name"/>-->
          <!--              @change="query = $event.target.value" :display-value="(person) {if(person?.first_name !== undefined && person?.last_name !== undefined){ return ''} else {return person.first_name + ' ' + person.last_name}}"/>-->
          <ComboboxInput
              class="w-full rounded-md border-0 bg-white py-1.5 pl-3 pr-10 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
              @change="query = $event.target.value" :display-value="(person) => person?.last_name"/>
          <ComboboxButton class="absolute inset-y-0 right-0 flex items-center rounded-r-md px-2 focus:outline-none">
            <ChevronUpDownIcon class="h-5 w-5 text-gray-400" aria-hidden="true"/>
          </ComboboxButton>

          <ComboboxOptions v-if="filteredPeople.length > 0"
                           class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm">
            <ComboboxOption v-for="person in filteredPeople" :key="person.id" :value="person" as="template"
                            v-slot="{ active, selected }">
              <li :class="['relative cursor-default select-none py-2 pl-8 pr-4', active ? 'bg-indigo-600 text-white' : 'text-gray-900']">
            <span :class="['block truncate', selected && 'font-semibold']">
              {{ person.first_name }} {{ person.last_name }} - {{ person.address_line_1 }}
            </span>

                <span v-if="selected"
                      :class="['absolute inset-y-0 left-0 flex items-center pl-1.5', active ? 'text-white' : 'text-indigo-600']">
              <CheckIcon class="h-5 w-5" aria-hidden="true"/>
            </span>
              </li>
            </ComboboxOption>
          </ComboboxOptions>
        </div>
        <button type="button"
                class="-ml-px relative inline-flex items-center px-4 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500"
                @click="goToCustomer(selectedPerson.addressId)"
        >Go</button>
      </Combobox>



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
      <div v-for="row in customers" :key="row.id" class="relative">
        <div v-show="monday">
          <div v-if="row.service_day === 'Monday'">
            <Link :href="route('customers.show', row.addressId)"
                  class="sticky top-0 z-10 border-y border-b-gray-200 border-t-gray-100 bg-gray-50 px-3 py-2 text-sm font-semibold leading-6 text-gray-900"
                  :class="row.completed ? 'completed' : 'notCompleted'"
                  style="border: solid thin black; width: -webkit-fill-available;"
                  method="get" as="button">
              <h3 style="font-size: 1.4rem">{{ row.first_name }} {{ row.last_name }}</h3>
              <div>{{ row.address_line_1 }}, {{ row.city }} AZ {{ row.zip }}</div>
              <div>{{ row.community_gate_code }}</div>
              <div>{{ row.assigned_serviceman }}</div>
              <div v-if="user.is_admin === 1">{{ row.phone_number }}</div>
            </Link>
          </div>
        </div>

        <div v-show="tuesday">
          <div v-if="row.service_day === 'Tuesday'">
            <Link :href="route('customers.show', row.addressId)"
                  class="sticky top-0 z-10 border-y border-b-gray-200 border-t-gray-100 bg-gray-50 px-3 py-2 text-sm font-semibold leading-6 text-gray-900"
                  :class="row.completed ? 'completed' : 'notCompleted'"
                  style="border: solid thin black; width: -webkit-fill-available;"
                  method="get" as="button">
              <h3 style="font-size: 1.4rem">{{ row.first_name }} {{ row.last_name }}</h3>
              <div>{{ row.address_line_1 }}, {{ row.city }} AZ {{ row.zip }}</div>
              <div>{{ row.community_gate_code }}</div>
              <div>{{ row.assigned_serviceman }}</div>
              <div v-if="user.is_admin === 1">{{ row.phone_number }}</div>
            </Link>
          </div>
        </div>

        <div v-show="wednesday">
          <div v-if="row.service_day === 'Wednesday'">
            <Link :href="route('customers.show', row.addressId)"
                  class="sticky top-0 z-10 border-y border-b-gray-200 border-t-gray-100 bg-gray-50 px-3 py-2 text-sm font-semibold leading-6 text-gray-900"
                  :class="row.completed ? 'completed' : 'notCompleted'"
                  style="border: solid thin black; width: -webkit-fill-available;"
                  method="get" as="button">
              <h3 style="font-size: 1.4rem">{{ row.first_name }} {{ row.last_name }}</h3>
              <div>{{ row.address_line_1 }}, {{ row.city }} AZ {{ row.zip }}</div>
              <div>{{ row.community_gate_code }}</div>
              <div>{{ row.assigned_serviceman }}</div>
              <div v-if="user.is_admin === 1">{{ row.phone_number }}</div>
            </Link>
          </div>
        </div>

        <div v-show="thursday">
          <div v-if="row.service_day === 'Thursday'">
            <Link :href="route('customers.show', row.addressId)"
                  class="sticky top-0 z-10 border-y border-b-gray-200 border-t-gray-100 bg-gray-50 px-3 py-2 text-sm font-semibold leading-6 text-gray-900"
                  :class="row.completed ? 'completed' : 'notCompleted'"
                  style="border: solid thin black; width: -webkit-fill-available;"
                  method="get" as="button">
              <h3 style="font-size: 1.4rem">{{ row.first_name }} {{ row.last_name }}</h3>
              <div>{{ row.address_line_1 }}, {{ row.city }} AZ {{ row.zip }}</div>
              <div>{{ row.community_gate_code }}</div>
              <div>{{ row.assigned_serviceman }}</div>
              <div v-if="user.is_admin === 1">{{ row.phone_number }}</div>
            </Link>
          </div>
        </div>

        <div v-show="friday">
          <div v-if="row.service_day === 'Friday'">
            <Link :href="route('customers.show', row.addressId)"
                  class="sticky top-0 z-10 border-y border-b-gray-200 border-t-gray-100 bg-gray-50 px-3 py-2 text-sm font-semibold leading-6 text-gray-900"
                  :class="row.completed ? 'completed' : 'notCompleted'"
                  style="border: solid thin black; width: -webkit-fill-available;"
                  method="get" as="button">
              <h3 style="font-size: 1.4rem">{{ row.first_name }} {{ row.last_name }}</h3>
              <div>{{ row.address_line_1 }}, {{ row.city }} AZ {{ row.zip }}</div>
              <div>{{ row.community_gate_code }}</div>
              <div>{{ row.assigned_serviceman }}</div>
              <div v-if="user.is_admin === 1">{{ row.phone_number }}</div>
            </Link>
          </div>
        </div>

        <div v-show="saturday">
          <div v-if="row.service_day === 'Saturday'">
            <Link :href="route('customers.show', row.addressId)"
                  class="sticky top-0 z-10 border-y border-b-gray-200 border-t-gray-100 bg-gray-50 px-3 py-2 text-sm font-semibold leading-6 text-gray-900"
                  :class="row.completed ? 'completed' : 'notCompleted'"
                  style="border: solid thin black; width: -webkit-fill-available;"
                  method="get" as="button">
              <h3 style="font-size: 1.4rem">{{ row.first_name }} {{ row.last_name }}</h3>
              <div>{{ row.address_line_1 }}, {{ row.city }} AZ {{ row.zip }}</div>
              <div>{{ row.community_gate_code }}</div>
              <div>{{ row.assigned_serviceman }}</div>
              <div v-if="user.is_admin === 1">{{ row.phone_number }}</div>
            </Link>
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

const props = defineProps({
  customers: Array,
  user: Object
});

const people = props.customers.map(({id, first_name, last_name, address_line_1, addressId}) => ({id, first_name, last_name, address_line_1, addressId}));

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
    goToCustomer(id){
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

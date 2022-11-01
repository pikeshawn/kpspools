<template>
    <div class="flex flex-col">
        <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                        <tr>
                            <th>Update</th>
                            <th>Delete</th>
                            <th v-for="header in headers"
                                scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ header.name }}
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(row) in valueObjectArray">

                            <td>
                                <svg @click="edit(row)" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                </svg>
                            </td>
                            <td>
                                <svg @click="confirmDelete(row)" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 m-3"
                                     fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"
                                v-for="(value) in row"
                            >
                                {{ value }}
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

import {Inertia} from '@inertiajs/inertia'

export default {
    props: {
        headers: Array,
        keys: Array,
        objectArray: Array
    },
    data() {
        return {
            valueObjectArray: null
        }
    },
    mounted() {
        this.valueObjectArray = this.tableValues();
        Inertia.reload();
    },
    methods: {
        tableValues() {
            let mArray = []
            for (let i = 0; i < this.objectArray.length; i++) {
                let nArray = []
                for (let j = 0; j < this.headers.length; j++) {
                    nArray.push(this.objectArray[i][this.headers[j].key])
                }
                mArray.push(nArray)
            }
            return mArray;
        },

        confirmDelete(row) {

            console.log('row id', row[0])

            Inertia.delete(`/service_stops/` + row[0], {
                onBefore: () => confirm(`Are you sure you want to delete this service stop? ID: ` + row[0] + "\r" +
                    `Time In: ` + row[6] + "\r" +
                    `Time Out: ` + row[7] + "\r" +
                    `pH Level: ` + row[1] + "\r" +
                    `chlorine Level: ` + row[2]
                ),
                onSuccess: () => {
                    Inertia.reload()
                },
            })
        },

        edit(row) {

            console.log('row id', row[0])

            Inertia.get(`/ss/` + row[0])
        },

        doThing() {
            console.log('doThing',)
        },

        doAnotherThing() {
            console.log('doAnotherThing',)
        }

    }
}
</script>

<style scoped>

</style>

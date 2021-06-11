<template>
    <div class="flex flex-col">
        <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                        <tr>
                            <th v-for="header in headers"
                           scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ header.name }}</th>
                        </tr>
                        </thead>
                        <tbody>
<!--                        <tr>-->
<!--                            {{ valueObjectArray }}-->
<!--                        </tr>-->
                        <tr v-for="(row) in valueObjectArray">
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
    mounted () {
        this.valueObjectArray = this.tableValues();
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
        }
    }
}
</script>

<style scoped>

</style>

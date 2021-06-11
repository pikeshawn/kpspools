<template>
    <div>
        <div :class="dropDownWidths()">
            <label :for="dropDownId" class="block text-sm font-medium text-gray-700">{{ dropDownLabel }}</label>
            <select :id="dropDownId" :name="dropDownId"
                    v-model="selectedOption"
                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                <option v-for="option in selectOptions">{{ option }}</option>
            </select>
<!--            <button v-on:click="$emit('phvalue', 'adviceText')">-->
<!--                Click me for sending advice-->
<!--            @click="$emit('phvalue', selectedOption)"-->
<!--            </button>-->
        </div>
    </div>
</template>

<script>
export default {
    name: 'dropdown',
    emits: ['phvalue'],
    data() {
        return {
            selectedOptiowewn: null
        }
    },

    props: {
        largeColumnWidth: {
            type: String,
            default: 'col-span-1'
        },
        smallColumnWidth: {
            type: String,
            default: 'col-span-1'
        },
        dropDownLabel: String,
        dropDownId: String,
        selectOptions: Array
    },
    watch: {
        selectedOptions: function (val) {
            console.log(val)
            this.$emit('selected-option', '4.0')
        }
    },
    computed: {
        selectedOption: {
            get() {return this.value},
            set(v) {
                console.log(v)
                this.$emit('phvalue', v)
            }
        }
    },
    methods: {
        dropDownWidths() {
            let widths = '"' + this.largeColumnWidth + " " + this.smallColumnWidth + '"'
            return widths
        }
    }
}
</script>

<style scoped>

</style>

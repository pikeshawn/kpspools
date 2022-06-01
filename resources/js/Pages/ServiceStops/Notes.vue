<template>
    <layout
        title="Service Stops Notes"
    >

        <h1>{{ customer_name }}</h1>

        <br>

        <table style="width:100%">
            <thead>
            <tr>
            <th>Date</th>
            <th>Note</th>
            <th>Service Type</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="n in notes" class="border-8">
                <td v-if="n.notes">{{ n.updated_at }}</td>
                <td v-if="n.notes" style="width:60%">{{ n.notes }}</td>
                <td v-if="n.notes" style="width:15%">{{ n.service_type }}</td>
            </tr>
            </tbody>
        </table>

        <div >
            <div class="flex justify-between">

            </div>
        </div>

    </layout>
</template>

<script>
import Layout from "../Shared/Layout";

export default {
    name: "Notes",
    components: {
        Layout
    },
    data() {
        return {
        }
    },
    props: {
        notes: Array,
        customer_name: String
    }
}
</script>

<style scoped>

</style>

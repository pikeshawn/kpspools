<template>
    <layout
        :user="user"
    >

        <div class="max-w-4xl mx-auto p-6">
            <h1 class="text-2xl font-bold mb-4">Summary Report</h1>

            <div class="bg-white shadow-lg rounded-lg p-6 mb-6">
                <div class="mb-4">
                    <label class="block font-semibold">Select Date Range</label>
                    <input type="date" v-model="startDate" :disabled="selectedPeriod" class="border p-2 rounded w-full mt-2">
                    <input type="date" v-model="endDate" :disabled="selectedPeriod" class="border p-2 rounded w-full mt-2">
                </div>

                <div class="mb-4">
                    <label class="block font-semibold">Quick Select</label>
                    <div class="grid grid-cols-2 gap-2 mt-2">
                        <label v-for="(label, value) in periods" :key="value" class="flex items-center space-x-2">
                            <input type="radio" v-model="selectedPeriod" :value="value" class="form-radio text-blue-500">
                            <span>{{ label }}</span>
                        </label>
                    </div>
                </div>

                <div class="flex justify-between">
                    <button @click="fetchReport" class="bg-blue-500 text-white px-4 py-2 rounded">Submit</button>
                    <button v-if="reports.length" @click="downloadCsv(this.reports)" class="bg-blue-500 text-white px-4 py-2 rounded">Download CSV</button>
                </div>
            </div>

            <div v-if="reports.length" class="space-y-4">
                <div v-for="report in reports" :key="report.id" class="bg-gray-100 p-4 rounded-lg shadow">
                    <inertia-link :href="`/reports/customer/${report.id}`" class="block text-lg font-bold text-blue-600">
                        {{ report.name }}
                    </inertia-link>
                    <p>Revenue: ${{ report.revenue }}</p>
                    <p>Chemicals: ${{ report.chemicals }}</p>
                    <p>Labor: ${{ report.labor }}</p>
                    <p>Gross: {{ report.gross }}%</p>
                </div>
            </div>
        </div>

    </layout>

</template>

<script>
import Layout from "../Shared/Layout";
import { Inertia } from '@inertiajs/inertia';


export default {
    name: "Index",
    components: {
        Layout,
        Inertia,
    },
    props: {
        tasks: Array,
        user: String
    },
    data() {
        return {
            startDate: '',
            endDate: '',
            selectedPeriod: null,
            reports: [],
            csrfToken: null,
            periods: {
                last_month: 'Last Month',
                last_year: 'Last Year',
                q1: 'Q1',
                q2: 'Q2',
                q3: 'Q3',
                q4: 'Q4',
                winter: 'Winter (Nov - Mar)',
                summer: 'Summer (Apr - Oct)'
            }
        };
    },
    mounted() {
        this.csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    },
    methods: {
        async fetchReport() {
            const payload = {
                start_date: this.selectedPeriod ? null : this.startDate,
                end_date: this.selectedPeriod ? null : this.endDate,
                period: this.selectedPeriod
            };

            const response = await fetch('/reports/accrual', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken
                },
                body: JSON.stringify(payload)
            });

            if (response.ok) {
                this.reports = await response.json();
            }
        },
        jsonToCsv(jsonData) {
            const headers = Object.keys(jsonData[0]).join(",") + "\n";
            const rows = jsonData.map(obj => Object.values(obj).join(",")).join("\n");
            return headers + rows;
        },
        downloadCsv(jsonData, filename = "report.csv") {
            const csvData = this.jsonToCsv(jsonData);
            const blob = new Blob([csvData], { type: "text/csv" });
            const url = URL.createObjectURL(blob);

            const a = document.createElement("a");
            a.href = url;
            a.download = filename;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
        }
    }
}
</script>

<style scoped>

</style>

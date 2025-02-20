<template>
    <div class="max-w-3xl mx-auto p-6 bg-white shadow-lg rounded-lg">
        <h1 class="text-3xl font-bold text-gray-800 mb-4">Service Terms & Conditions</h1>

        <section class="mb-6">
            <h2 class="text-xl font-semibold text-gray-700">Service Call Fee</h2>
            <p class="text-gray-600 mt-2">
                We charge a standard <strong>$80 Service Call Fee</strong> for on-site diagnostics, troubleshooting, and consultation. If a repair is required and approved, this fee will be credited toward the total repair cost.
            </p>
        </section>

        <section class="mb-6">
            <h2 class="text-xl font-semibold text-gray-700">Multiple Visits Policy</h2>
            <p class="text-gray-600 mt-2">
                If a follow-up visit is required within <strong>48 hours</strong> for the same issue, we will not charge another fee.
                If a separate visit is required after <strong>3-7 days</strong> for a related issue, we may apply a <strong>discounted fee</strong> instead of the full charge.
                For <strong>completely unrelated issues</strong> or visits beyond 7 days, a new Service Call Fee will apply.
            </p>
        </section>

        <section class="mb-6">
            <h2 class="text-xl font-semibold text-gray-700">Service Call Fee Expiration</h2>
            <p class="text-gray-600 mt-2">
                The Service Call Fee remains valid for <strong>60 days</strong>. If the repair is scheduled within this period, the original fee applies toward the repair.
                However, if pool conditions have significantly changed, requiring a new diagnosis, a new Service Call Fee may be necessary.
            </p>
        </section>

        <section class="mb-6">
            <h2 class="text-xl font-semibold text-gray-700">Handling Technician Mistakes</h2>
            <p class="text-gray-600 mt-2">
                If a service technician fails to complete a required task and another technician must visit to correct the mistake, <strong>the customer will not be charged</strong> for the additional visit.
                In such cases, the company will absorb the cost, and the original technician may be held accountable internally if this becomes a recurring issue.
            </p>
        </section>

        <section class="mb-6">
            <h2 class="text-xl font-semibold text-gray-700">Compensation for Multiple Tech Visits</h2>
            <p class="text-gray-600 mt-2">
                If one technician diagnoses an issue and another performs the repair, the Service Call Fee will be <strong>split fairly</strong> between them.
                For larger repair jobs (over $500), the repair technician may receive full compensation while the diagnosing technician keeps the Service Call Fee.
            </p>
        </section>

        <section class="mb-6">
            <h2 class="text-xl font-semibold text-gray-700">Customer Approval & Notifications</h2>
            <p class="text-gray-600 mt-2">
                Customers will receive an initial notification to acknowledge and accept these terms before any service visit.
                Before each visit, customers will receive a <strong>reminder text</strong> requiring approval of the Service Call Fee.
            </p>
        </section>
        <div class="flex justify-center mt-6">
            <button
                @click="acceptTerms"
                class="px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                Accept Terms
            </button>
        </div>
    </div>
</template>

<script>
export default {
    name: "TermsOfService",
    props: {
      customerId: Number
    },
    methods: {
        async serviceFee(code) {
            try {
                const response = await fetch(`/terms_of_service/accept/` + this.customerId);
                const data = await response.json();
                if (data.user_id) {
                    this.$inertia.render('TermsOfService/ServiceFee', { code: code });
                } else {
                    console.error("Invalid code or user not found");
                }
            } catch (error) {
                console.error("Error fetching user by code", error);
            }
        },
        acceptTerms() {
            console.log("Terms accepted");
            // Here you can send an API request to record the user's acceptance
        }
    }
};
</script>

<style scoped>
</style>

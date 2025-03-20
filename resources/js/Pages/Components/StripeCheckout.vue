<script setup>
import { ref } from "vue";
import { loadStripe } from "@stripe/stripe-js";

// const stripeKey = import.meta.env.VITE_STRIPE_KEY; // Ensure your .env has VITE_STRIPE_KEY
const stripeKey = "pk_test_iAX3DPtpLj5RiG3FCexe1r0Z"; // Ensure your .env has VITE_STRIPE_KEY
const stripe = ref(null);

const initializeStripe = async () => {
    if (!stripe.value) {
        stripe.value = await loadStripe(stripeKey);
    }
};

const checkout = async () => {
    await initializeStripe();

    try {
        const response = await fetch("/create-checkout-session", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
            },
        });

        const session = await response.json();

        if (session.id) {
            await stripe.value.redirectToCheckout({ sessionId: session.id });
        } else {
            console.error("Error creating Stripe session:", session.error);
        }
    } catch (error) {
        console.error("Checkout error:", error);
    }
};
</script>

<template>
    <button @click="checkout" class="stripe-button">
        Pay with Stripe
    </button>
</template>

<style scoped>
.stripe-button {
    background-color: #6772e5;
    color: white;
    border: none;
    padding: 10px 20px;
    font-size: 16px;
    cursor: pointer;
    border-radius: 5px;
    transition: background-color 0.2s;
}

.stripe-button:hover {
    background-color: #5469d4;
}
</style>

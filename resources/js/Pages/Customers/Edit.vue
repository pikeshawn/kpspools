<template>
    <div>
        <label for="first_name">First Name</label>
        <input id="first_name" type="text" v-model="form.first_name">
    </div>
</template>

<script>
export default {
    name:'edit',
    data() {
        return {
            form: this.$inertia.form({
                first_name: this.customer.first_name,
                last_name: this.customer.last_name,
                organization_id: this.customer.organization_id,
            }),
        }
    },
    props: {
        customer: Object
    }
}
</script>

<style scoped>

</style>

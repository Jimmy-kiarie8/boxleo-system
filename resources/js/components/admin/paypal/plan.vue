<template>
<!-- <v-card style="padding: 20px"> -->
<v-card-text>
    <v-layout row wrap>
        <v-flex sm4 style="text-align: center">
            <h3>My Plan</h3>
            <h3>{{ plan.plan }} plan</h3>
                    <v-switch v-model="form.anual" label="Anual" color="red" inset></v-switch>
            <strong style="color: #41B779;font-size: 22px;font-weight: 900;">$ {{ payment_total }} </strong>
            <br>
            <br>
            <v-btn rounded color="primary" dark outlined @click="checkout">Pay</v-btn>
            <v-btn rounded color="primary" dark outlined @click="upgrade">Upgrade</v-btn>
        </v-flex>
        <v-flex sm4 style="text-align: center;1px solid rgb(230 230 230);">
            <h3 v-if="tenant.subscriber.at_trial">Trial period</h3>
            <h3 v-else>Subscription period</h3>
            <p>
                <strong style="color: rgb(25 118 210);font-size: 22px;font-weight: 900;">{{ tenant.subscriber.subscription_start | moment }}</strong>
            </p>
            <p style="color: #000;font-size: 22px;font-weight: 900;">to</p>
            <p>
                <strong style="color: rgb(74 187 128);font-size: 22px;font-weight: 900;" v-if="tenant.subscriber.at_trial">{{ tenant.subscriber.trial_ends | moment }}</strong>
                <strong style="color: rgb(74 187 128);font-size: 22px;font-weight: 900;" v-else>{{ tenant.subscriber.subscription_expire | moment }}</strong>
            </p>
        </v-flex>
        <v-divider vertical></v-divider>
    </v-layout>
</v-card-text>
<!-- </v-card> -->
</template>

<script>
import moment from 'moment'
export default {
    props: ['plan', 'tenant', 'payment_total', 'form'],

    methods: {
        upgrade() {
            eventBus.$emit('upgradeEvent')
        },
        checkout() {
            eventBus.$emit('checkoutEvent')
        }
    },
    filters: {
        moment(date) {
            return moment(date).format('ddd, MMM Do YYYY');
        },
    },

}
</script>

<style>

</style>

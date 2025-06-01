<template>
<div>
    <img style="width: 100px;" src="/home/logo.png" />
    <h2 class="title">Start your <b>10-days trial</b></h2>
    <v-divider dark></v-divider>
    <div style="height: 30px"></div>
    <div class="input-group">
        <input class="input--style-3" v-model="form.name" type="text" placeholder="Enter Your Name">
    </div>
    <small class="has-text-danger" v-if="errors.name">{{ errors.name[0] }}</small>
    <div class="input-group">
        <input class="input--style-3" v-model="form.company" type="text" placeholder="Company">
    </div>
    <small class="has-text-danger" v-if="errors.company">{{ errors.company[0] }}</small>
    <div class="input-group">
        <input class="input--style-3" v-model="form.email" type="email" placeholder="Email">
    </div>
    <small class="has-text-danger" v-if="errors.email">{{ errors.email[0] }}</small>
    <div class="input-group">
        <input class="input--style-3" v-model="form.phone" type="text" placeholder="Phone">
    </div>
    <small class="has-text-danger" v-if="errors.phone">{{ errors.phone[0] }}</small>
    <div class="input-group">
        <input class="input--style-3" v-model="form.address" type="text" placeholder="Address">
    </div>
    <small class="has-text-danger" v-if="errors.address">{{ errors.address[0] }}</small>
    <!-- <div class="input-group">
        <input class="input--style-3" v-model="form.password" type="password" placeholder="Password">
    </div>
    <small class="has-text-danger" v-if="errors.password">{{ errors.password[0] }}</small>
    <div class="input-group">
        <input class="input--style-3" v-model="form.password_confirmation" type="password" placeholder="Confirm Password">
    </div> -->

</div>
</template>

<script>
import {
    mapState
} from 'vuex'
export default {
    props: ['form'],
    data() {
        return {
        }
    },
    computed: {
        ...mapState(['errors'])
    },
}
</script>

<style scoped>
.has-text-danger {
    color: red;
}
</style>

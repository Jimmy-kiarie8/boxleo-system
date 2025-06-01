<template>
<div>
    <img style="width: 100px;" src="/home/logo.png" />
    <h2 class="title">Choose your <b>account url</b></h2>
    <v-divider dark></v-divider>
    <small>The registered url will take you to your account</small>
    <div style="height: 30px"></div>
    <div class="input-group">
        <span> <input class="input--style-3" v-model="form.id" type="text" placeholder="Account Domain"><b style="position: absolute;color: white;margin-left: -100px;margin-top: 10px;background: #2196f375;">{{ central_domain }}</b></span>
        <small class="has-text-danger" v-if="errors.id">{{ errors.id[0] }}</small>
    </div>
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
            central_domain: process.env.MIX_CENTRAL_DOMAIN
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

<template>
<!-- <router-view v-loading="page_loader" :element-loading-text="app_name + '...'" element-loading-spinner="el-icon-loading" element-loading-background="rgba(0, 0, 0, 0.8)"></router-view> -->
<v-main :style="[ ($vuetify.theme.dark) ? {'background': '#1e1e1e'} : '']" style="height: 100vh !important;">
    <myHeader :tenant="tenant" :user="user" />
    <v-app>
        <router-view :tenant="tenant" :user="user"></router-view>
    </v-app>
</v-main>
</template>

<script>
import myHeader from "../inc/Header";
import {
    mapState
} from 'vuex'
export default {
    props: ['user', 'tenant'],
    components: {
        myHeader,
    },
    data() {
        return {
            app_name: 'Lux'
        }
    },
    computed: {
        ...mapState(['page_loader'])
    },
}
</script>

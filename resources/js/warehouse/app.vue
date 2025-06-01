<template>
<v-main :style="[ ($vuetify.theme.dark) ? {'background': '#1e1e1e'} : '']" style="height: 100vh !important;">
    <Sidebar :drawer="drawer" :user="user" />
    <router-view :user="user" v-loading="page_loader" :element-loading-text="tenant + '...'" element-loading-spinner="el-icon-loading" element-loading-background="rgba(0, 0, 0, 0.8)"></router-view>
</v-main>
</template>

<script>
import Sidebar from "./inc/sidebar.vue";
import {
    mapState
} from 'vuex'
export default {
    props: ['user', 'tenant'],
    components: {
        Sidebar
    },
    data() {
        return {
            app_name: 'Logixsaas',
            drawer: null,
        }
    },
    computed: {
        ...mapState(['page_loader'])
    },
}
</script>

<style>
.el-loading-mask {
    position: fixed !important;
}
</style>

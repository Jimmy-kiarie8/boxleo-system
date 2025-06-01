<template>
<!-- <v-main :style="[ ($vuetify.theme.dark) ? {'background': '#1e1e1e'} : '']" style="height: 100vh !important;">
    <router-view v-loading="page_loader" :element-loading-text="app_name + '...'" element-loading-spinner="el-icon-loading" element-loading-background="rgba(0, 0, 0, 0.8)"></router-view>
</v-main> -->

<!-- <router-view v-loading="page_loader" :element-loading-text="app_name + '...'" element-loading-spinner="el-icon-loading" element-loading-background="rgba(0, 0, 0, 0.8)"></router-view> -->
<div>
    <myHeader />
    <v-app>
        <router-view></router-view>
    </v-app>
</div>
</template>

<script>
import myHeader from "./inc/header";
import {
    mapState
} from 'vuex'
export default {
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

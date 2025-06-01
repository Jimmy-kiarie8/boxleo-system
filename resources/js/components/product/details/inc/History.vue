<template>
<div>
    <VCard style="padding: 30px;width: 100%;">
        <el-breadcrumb separator-class="el-icon-arrow-right">
            <el-breadcrumb-item :to="{ path: '/' }">Dashboard</el-breadcrumb-item>
            <el-breadcrumb-item :to="{ path: '/orderHistory' }">Order History</el-breadcrumb-item>
            <el-breadcrumb-item>order <b style="color: red">{{ ord_history.order_no }}</b> history</el-breadcrumb-item>
        </el-breadcrumb>
    </VCard>
    <v-card class="mx-auto" width="100%">
        <v-tooltip right>
            <template v-slot:activator="{ on }">
                <v-btn icon v-on="on" slot="activator" class="mx-0" @click="getHistory()">
                    <v-icon color="blue darken-2" small>mdi-refresh</v-icon>
                </v-btn>
            </template>
            <span>Refresh</span>
        </v-tooltip>
        <v-card dark text>
            <v-card-title class="pa-2 lighten-3">
                <h3 class="title font-weight-light text-xs-center grow" style="color: white">History</h3>
                <v-avatar>
                    <v-img src="/storage/landS.jpg"></v-img>
                </v-avatar>
            </v-card-title>
            <v-img src="https://cdn.vuetifyjs.com/images/cards/forest.jpg" gradient="to top, rgba(0,0,0,.44), rgba(0,0,0,.44)" style="height: 10vh">
                <v-container fill-height>
                    <v-layout align-center>
                        <!-- <strong class="display-4 font-weight-regular mr-4">8</strong> -->
                        <v-layout column justify-end>
                            <div class="headline font-weight-light">{{ new Date() | moment2 }}</div>
                            <div class="text-uppercase font-weight-light">{{ new Date() | moment }}</div>
                        </v-layout>
                    </v-layout>
                </v-container>
            </v-img>
        </v-card>
        <v-card-text class="py-0">
            <v-timeline align-top dense>
                <v-timeline-item :color="history.color" small v-for="history in product_history" :key="history.id">
                    <v-layout pt-3>
                        <v-flex xs3>
                            <span>{{ history.created_at }}</span>
                        </v-flex>
                        <v-flex>
                            Event:<strong v-html="history.update_comment"> </strong>
                            <p>Reference No: <b>{{ history.reference_no }}</b></p>
                            <p class="caption">Updated by: <b style="color: red">{{ history.user.name }}</b></p>
                        </v-flex>
                        <!-- <v-flex xs12 lg5 mb-3>
                                    <v-expansion-panel popout>
                                        <v-expansion-panel-content>
                                            <template v-slot:header>
                                                <div>Changed Fields</div>
                                            </template>
                                            <v-card>
                                                <v-card-text>{{ history.updated_fields }}</v-card-text>
                                            </v-card>
                                        </v-expansion-panel-content>
                                    </v-expansion-panel>
                                </v-flex> -->
                    </v-layout>
                </v-timeline-item>
            </v-timeline>
        </v-card-text>
    </v-card>
</div>
</template>

<script>
import moment from 'moment'
import {
    mapState
} from 'vuex';
export default {
    props: ['user'],
    data() {
        return {
            histories: [],
            ord_history: {},
        }
    },
    methods: {
        getHistory() {
            eventBus.$emit('historyEvent')
        }
    },
    mounted() {
        // this.getHistory(this.$route.params.id);
        // this.ord_history = this.$route.params.data
    },
    filters: {
        moment(date) {
            return moment(date).format('MMMM Do YYYY');
        },
        moment2(date) {
            return moment(date).format('dddd');
        },
    },

    computed: {
        ...mapState(['product_history'])
    },
}
</script>

<style scoped>
.v-image__image--cover {
    height: 20vh !important;
}
</style>

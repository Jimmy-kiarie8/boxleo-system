<template>
<div style="padding: 20px">
    <!-- <v-card class="mx-auto" width="600" style="margin-bottom: 20px;">
        <v-toolbar width="600" style="margin:auto;padding: 7px 0">
            <v-text-field v-model="form.waybill" color="primary" :loading="loading" :disabled="loading" label="Track waybill" @keyup.enter="getOrder"></v-text-field>
            <v-btn class="" color="primary" dark outlined rounded small style="margin-left: 15px" @click="getOrder">
                <v-icon dark>mdi-magnify</v-icon>
                Search
            </v-btn>

        </v-toolbar>
        <v-card-text class="py-0" v-if="searched">
            <v-row style="background: #17478c;color: #fff;">
                <v-col sm="6">
                    <b>Consignor:</b> {{ waybill.seller.name }} <br>
                    <b>From Location: </b>{{ waybill.seller.address }}<br>
                </v-col>
            </v-row>
        </v-card-text>
        <v-divider></v-divider>
        <v-card-text class="py-0" v-if="searched">
            <v-timeline align-top dense>
                <v-timeline-item color="pink" small v-for="item in waybill.history" :key="item.id">
                    <v-row class="pt-1">
                        <v-col cols="6">
                            <strong>{{ item.created_at }}</strong>
                        </v-col>
                        <v-col>
                            <v-card>
                                <v-card-title style="color: #fff; background-color: #f08c25!important;font-size: 15px">
                                    {{ item.action }}
                                </v-card-title>
                                <v-card-text>
                                    <div v-html="item.update_comment"></div>
                                </v-card-text>
                            </v-card>
                        </v-col>
                    </v-row>
                </v-timeline-item>

            </v-timeline>
        </v-card-text>
    </v-card>-->

    <v-card class="mx-auto" max-width="900">
        <v-card style="background: #f0f0f0;" flat>
            <v-btn absolute bottom color="primary" right fab>
                <v-icon>mdi-refresh</v-icon>
            </v-btn>
            <v-card-title class="primary">
                <h3 class="text-h6 font-weight-light text-center grow" style="color:#fff">Tracking page</h3>
            </v-card-title>
            <v-card-text class="py-0">
                <v-text-field v-model="form.waybill" color="primary" :loading="loading" :disabled="loading" label="Track waybill" @keyup.enter="getOrder"></v-text-field>
            </v-card-text>
        </v-card>
        <v-card-text class="py-0">
            <el-steps :active="steps" finish-status="success" style="margin-top: 35px;">
                <el-step title="Order received" icon="el-icon-edit"></el-step>
                <el-step title="Confirmed" icon="el-icon-phone"></el-step>
                <el-step title="In transit" icon="el-icon-loading"></el-step>
                <el-step title="Waiting pickup" icon="el-icon-s-check"></el-step>
                <el-step title="Delivered" icon="el-icon-success"></el-step>
            </el-steps>

            <v-timeline align-top dense>
                <div v-for="item in waybill.history" :key="item.id">
                    <v-timeline-item :color="item.color" small v-if="item.tracking_comment">
                        <v-row class="pt-1">
                            <v-col cols="3">
                                <strong>{{ item.created_at }}</strong>
                            </v-col>
                            <v-col>
                                <strong v-if="item.tracking_comment == 'Order received'" style="color: #ff0000"> {{ item.tracking_comment }} </strong>
                                <strong v-if="item.tracking_comment == 'Order confirmed'" style="color: #1976d2"> {{ item.tracking_comment }} </strong>
                                <strong v-if="item.tracking_comment == 'In transit'" style="color: #445d98"> {{ item.tracking_comment }} </strong>
                                <strong v-if="item.tracking_comment == 'Delivered'" style="color: #19d237"> {{ item.tracking_comment }} </strong>
                                <strong v-if="item.tracking_comment == 'Returned' || item.tracking_comment == 'Cancelled' || item.tracking_comment == 'Refused'" style="color: #f00"> {{ item.tracking_comment }} </strong>
                                <div class="text-caption" v-if="waybill.delivery_date">Delivery date: {{ waybill.delivery_date | moment }}</div>
                            </v-col>
                        </v-row>
                    </v-timeline-item>
                </div>
            </v-timeline>
        </v-card-text>
    </v-card>
    <v-snackbar v-model="snackbar">{{ text }}</v-snackbar>
</div>
</template>

<script>
import moment from 'moment'
export default {
    props: ['order_no'],
    data() {
        return {
            text: "",
            snackbar: false,
            waybill: {},
            form: {
                waybill: ""
            },
            searched: false,
            loading: false,
            colors: [
                'error', 'primary', 'secondary'
            ],
            steps: 0
        };
    },

    filters: {
        moment(date) {
            return moment(date).format('ddd, MMM Do YYYY');
        },
    },
    methods: {
        getOrder() {
            var headers = {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
            };
            this.loading = true;
            console.log(this.$route);
            axios
                .get("waybill/" + this.form.waybill, {
                    headers: headers
                })
                .then(response => {
                    console.log(response.data.data);
                    this.loading = false;
                    this.waybill = response.data.data;
                    this.searched = true;
                    this.level()
                })
                .catch(error => {
                    this.text = "Order not found";
                    this.snackbar = true;
                    this.searched = false;
                    this.loading = false;
                });
        },

        level() {
            this.steps = this.waybill.history[this.waybill.history.length - 1].step;
        }
    },
    created() {
        let uri = window.location.search.substring(1);
        let params = new URLSearchParams(uri);
        // console.log(params.get("order_no"));
        this.form.waybill = params.get("order_no");
        this.getOrder();

        // alert(this.$route.query.order_no)
        // console.log(this.$route.query.order_no) ;
    },
    mounted() {
        // this.getOrder()
    },
    computed: {}
};
</script>

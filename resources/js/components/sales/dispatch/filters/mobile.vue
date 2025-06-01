<template>
<div>
    <v-container fluid fill-height>
        <v-layout justify-center align-center wrap>
            <v-flex sm12>
                <v-card style="padding: 20px 0;">
                    <el-breadcrumb separator-class="el-icon-arrow-right">
                        <el-breadcrumb-item :to="{ path: '/' }">Dashboard</el-breadcrumb-item>
                        <el-breadcrumb-item>Dispatch</el-breadcrumb-item>
                    </el-breadcrumb>
                </v-card>
            </v-flex>
            <v-flex sm12>
                <v-card style="padding: 10px 0;">
                    <v-layout row wrap style="margin-left: 5px">
                        <v-flex sm2>
                            <label for="">Zone from</label>
                            <el-select v-model="form.zone_from" placeholder="Select" filterable clearable>
                                <el-option v-for="item in zones" :key="item.id" :label="item.name" :value="item.id"></el-option>
                            </el-select>
                            <small class="has-text-danger" v-if="errors.zone_from">{{ errors.zone_from[0] }}</small>
                        </v-flex>
                        <v-flex sm2>
                            <label for="">Zone to</label>
                            <el-select v-model="form.zone_to" placeholder="Select" filterable clearable>
                                <el-option v-for="item in zones" :key="item.id" :label="item.name" :value="item.id"></el-option>
                            </el-select>
                            <small class="has-text-danger" v-if="errors.zone_to">{{ errors.zone_to[0] }}</small>
                        </v-flex>
                        <v-flex sm2>
                            <label for="">Rider/Agent</label>
                            <el-select v-model="form.rider_id" placeholder="Select" filterable clearable>
                                <el-option v-for="item in riders" :key="item.id" :label="item.name" :value="item.id"></el-option>
                            </el-select>
                            <small class="has-text-danger" v-if="errors.rider_id">{{ errors.rider_id[0] }}</small>
                        </v-flex>
                        <v-flex sm2>
                            <label>Status</label>
                            <el-select v-model="form.status" filterable clearable placeholder="Select" style="width: 100%;">
                                <el-option v-for="(item, index) in statuses" :key="index" :label="item.value" :value="item.value">
                                </el-option>
                            </el-select>
                            <small class="has-text-danger" v-if="errors.status">{{ errors.status[0] }}</small>
                        </v-flex>
                        <v-flex sm3>
                            <div class="block">
                                <label style="float: left">Dispatch Start date</label>
                                <el-date-picker format="yyyy/MM/dd" value-format="yyyy-MM-dd" v-model="form.start_date" type="date" placeholder="Pick a day" style="width:100%">
                                </el-date-picker>
                            </div>
                            <small class="has-text-danger" v-if="errors.start_date">{{ errors.start_date[0] }}</small>
                        </v-flex>
                        <v-flex sm3>
                            <div class="block">
                                <label style="float: left">Dispatch End date</label>
                                <el-date-picker format="yyyy/MM/dd" value-format="yyyy-MM-dd" v-model="form.end_date" type="date" placeholder="Pick a day" style="width:100%">
                                </el-date-picker>
                            </div>
                            <small class="has-text-danger" v-if="errors.end_date">{{ errors.end_date[0] }}</small>
                        </v-flex>
                        <v-flex sm2 style="margin-left: 5px">

                            <v-btn-toggle mandatory style="margin-top: 20px;">
                                <v-tooltip bottom>
                                    <template v-slot:activator="{ on, attrs }">
                                        <v-btn icon v-bind="attrs" v-on="on" @click="filter" color="primary">
                                            <v-icon small color="grey">mdi-magnify</v-icon>
                                        </v-btn>
                                    </template>
                                    <span>Filter</span>
                                </v-tooltip>
                            </v-btn-toggle>
                        </v-flex>
                    </v-layout>

                    <v-divider></v-divider>

                    <v-layout row>
                        <v-flex sm12>
                            <v-text-field v-model="search" append-icon="mdi-magnify" label="Quick Search" single-line hide-details></v-text-field>
                            <v-data-table :headers="headers" :items="dispatch_sales.sales" :search="search" :loading="loading">

                                <template v-slot:item.pod="{ item }">
                                    <img :src="item.pod.signature" alt="" v-if="item.pod" style="width: 35px;" @click="open_pod(item)">
                                    
                                     <v-tooltip bottom v-else>
                                        <template v-slot:activator="{ on }">
                                            <v-btn v-on="on" icon class="mx-0" @click="open_pod(item)" slot="activator">
                                                <v-icon small color="red">mdi-eye</v-icon>
                                            </v-btn>
                                        </template>
                                        <span>More details</span>
                                    </v-tooltip>
                                </template>
                                <template v-slot:item.delivery_status="{ item }">
                                    <div>
                                        <el-tag v-if="item.delivery_status == 'Delivered'" type="success">{{ item.delivery_status }}</el-tag>
                                        <el-tag v-else-if="item.delivery_status == 'Dispatched'" type="info">{{ item.delivery_status }}</el-tag>
                                        <el-tag v-else-if="item.delivery_status == 'Returned' || item.delivery_status == 'Cancelled'" type="danger">{{ item.delivery_status }}</el-tag>
                                        <el-tag v-else-if="item.delivery_status == 'pending approval'">Delivered {{ item.delivery_status }}</el-tag>
                                        <el-tag v-else type="warning">{{ item.delivery_status }}</el-tag>
                                    </div>
                                </template>

                                <template v-slot:item.actions="{ item }" v-if="user.roles[0].name == 'Super admin' || user.roles[0].name == 'Finance'">
                                    <v-tooltip bottom v-if="item.delivery_status == 'pending approval'">
                                        <template v-slot:activator="{ on }">
                                            <v-btn v-on="on" icon class="mx-0" @click="updateStatus(item)" slot="activator">
                                                <v-icon small color="success">mdi-check-circle</v-icon>
                                            </v-btn>
                                        </template>
                                        <span>Approve</span>
                                    </v-tooltip>
                                </template>
                            </v-data-table>
                        </v-flex>
                    </v-layout>
                </v-card>
            </v-flex>
        </v-layout>
        <form action="/dispatch_list" method="post" ref="dispatch_list" target="_blank">
            <input type="hidden" name="_token" :value="csrf">
            <input type="hidden" name="packages" :value="serialize_data">
        </form>
    </v-container>
    <myPod />
</div>
</template>

<script>
import {
    mapState
} from 'vuex';
import myPod from '../../saleorder/pod'

export default {
    props: ['user'],
    components: {
        myPod,
    },
    data() {
        return {
            search: '',
            csrf: document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content"),
            form: {
                zone_from: null,
                zone_to: null,
                rider_id: null,
                status: null,
                start_date: null,
                end_date: null
            },
            statuses: [{
                value: 'Dispatched'
            }],
            headers: [{
                    text: "Created On",
                    value: "created_at"
                },
                {
                    text: "Order no",
                    value: "order_no"
                },
                {
                    text: "Client Name",
                    value: "client.name"
                },
                {
                    text: "Client Address",
                    value: "client.address"
                },
                {
                    text: "Client Phone",
                    value: "client.phone"
                },
                {
                    text: "Delivery Status",
                    value: "delivery_status"
                },
                {
                    text: "Total",
                    value: "total_price"
                },
                {
                    text: "Pod",
                    value: "pod"
                },
                {
                    text: "Actions",
                    value: "actions",
                    sortable: false
                }
            ],
            json_fields: {
                'Order Number': 'order_no',
                'Client Name': 'client.name',
                // 'Client Address': 'client.address',
                // 'Client Phone': 'client.phone',
                // 'Delivery status': 'delivery_status',
                'Product Name': 'product_name',
                'Cod amount': 'total_price',
                'Quantity': 'qty',
                'Remarks': 'notes',
            },
            speedaf_fields: {
                'S.O.': 'order_no',
                'Goods type': '',
                'Goods name': 'product_name',
                'Quantity of Item': 'quantity',
                'Weight': 0.5,
                'COD': 'total_price',
                'Insure price': '',
                'Remark': 'notes',
                'C Name': 'company_name',
                'C Telephone': 'company_phone',
                'C Country': 'Kenyan',
                'C Region': 'company_city',
                'C City': 'company_city',
                'C Area': 'company_city',
                'Senders address': 'company_address',
                'Name': 'client.name',
                'Telephone': 'client.phone',
                'Country': 'Kenya',
                'Region': 'client.city',
                'City': 'client.city',
                'Area': 'client.city',
                'Receivers address': 'client.address',

            },
        }
    },

    methods: {
        dispatch_list() {
            this.$refs.dispatch_list.submit()
        },
        getZones() {
            var payload = {
                model: 'zones',
                update: 'updateZone',
            }
            this.$store.dispatch('getItems', payload);
        },
        getRiders() {
            var payload = {
                model: 'rider/riders',
                update: 'updateRidersList'
            }
            this.$store.dispatch("getItems", payload);
        },
        filter() {
            this.form.paginate = true
            var payload = {
                model: 'mobile_filter',
                update: 'updateDispatchList',
                data: this.form
            }
            this.$store.dispatch('filterItems', payload);
        },
        open_pod(data) {
            eventBus.$emit('openPod', data)
        },
        resetSales() {
            var payload = {
                item: 'sales',
                update: 'updateDispatchList',
            }
            this.$store.dispatch('resetItems', payload);
        },
        updateStatus(item, index) {
            var payload = {
                model: 'approve_delivery/' + item.id,
                data: item
            }
            this.$store.dispatch('postItems', payload).then(() => {
                this.filter()
            })
        }
    },
    computed: {
        ...mapState(['zones', 'loading', 'riders', 'dispatch_sales', 'errors']),
        serialize_data() {
            return JSON.stringify(this.form)
        },
    },
    mounted() {
        // this.resetSales()
        this.getZones();
        this.getRiders();
    },
};
</script>

<style scoped>
.has-text-danger {
    font-size: 10px !important;
}
</style>

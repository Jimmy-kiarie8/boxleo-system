<template>
<div>
    <div>
        <v-layout justify-center align-center wrap>
            <v-card style="width: 100%;padding: 30px 0 40px 20px;">
                <v-progress-linear color="primary" :active="loading" :indeterminate="loading" rounded height="4"></v-progress-linear>

                <v-card-text>

                    <v-layout row wrap>
                        <v-flex sm2 style="margin-left: 10px; margin-bottom: 20px;">
                            <label for="">Report</label>
                            <el-select v-model="form.report" placeholder="Report" clearable filterable @change="get_custom_report">
                                <el-option v-for="(item, index) in reports" :key="index" :label="item.report" :value="item.report">
                                </el-option>
                            </el-select>
                            <small class="has-text-danger" v-if="errors.report">{{ errors.report[0] }}</small>
                        </v-flex>

                        <v-flex sm2 style="margin-left: 10px; margin-bottom: 20px;" v-if="form.report == 'Custom'">
                            <label for="">Report</label>
                            <el-select v-model="form.custom" placeholder="Custom Report" clearable filterable>
                                <el-option v-for="(item, index) in custom_report" :key="index" :label="item.name" :value="item.id">
                                </el-option>
                            </el-select>
                            <small class="has-text-danger" v-if="errors.custom">{{ errors.custom[0] }}</small>
                        </v-flex>

                        <v-flex sm2 style="margin-left: 10px; margin-bottom: 20px;"  v-if="!user.is_vendor">
                            <label for="">Client</label>
                            <el-select v-model="form.client" multiple placeholder="Client" clearable filterable>
                                <el-option v-for="item in sellers.data" :key="item.id" :label="item.name" :value="item.id">
                                </el-option>
                            </el-select>
                        </v-flex>

                        <v-flex sm3 style="margin-left: 10px; margin-bottom: 20px;" v-if="!user.is_vendor && form.report == 'Zone'">
                            <label for="">Zone to</label>
                            <el-select v-model="form.zone_to" placeholder="Zone to" clearable filterable style="width: 100%;" multiple>
                                <el-option v-for="item in zones" :key="item.id" :label="item.name" :value="item.id">
                                </el-option>
                            </el-select>
                        </v-flex>
                        <v-flex sm2 v-if="!user.is_vendor && form.report == 'Agents'">
                            <label for="">Agent</label>
                            <el-select v-model="form.user_name" placeholder="User" clearable filterable>
                                <el-option v-for="item in users.data" :key="item.id" :label="item.name" :value="item.name">
                                </el-option>
                            </el-select>
                        </v-flex>

                        <v-flex sm2 style="margin-left: 10px; margin-bottom: 20px;">
                            <label for="">Product</label>
                            <el-select v-model="form.product" placeholder="Product" clearable filterable>
                                <el-option v-for="item in products.data" :key="item.id" :label="item.product_name" :value="item.id">
                                </el-option>
                            </el-select>
                            <small class="has-text-danger" v-if="errors.product">{{ errors.product[0] }}</small>
                        </v-flex>

                        <v-flex sm2 v-if="form.report == 'Status'">
                            <label>Status</label>
                            <el-select v-model="form.delivery_status" filterable clearable placeholder="Select" style="width: 100%;" multiple>
                                <el-option v-for="item in statuses" :key="item.id" :label="item.status" :value="item.status">
                                </el-option>
                            </el-select>
                        </v-flex>

                        <v-flex sm3 style="margin-left: 10px">
                            <div class="block">
                                <label style="float: left">Date</label>
                                <!-- <el-date-picker format="yyyy/MM/dd" value-format="yyyy-MM-dd" v-model="form.start_date" type="date" placeholder="Pick a day">
                                </el-date-picker> -->
                                <el-date-picker v-model="form.created_at" type="daterange" align="right" start-placeholder="Start Date" end-placeholder="End Date" style="width: 100%;" format="yyyy/MM/dd" value-format="yyyy-MM-dd">
                                </el-date-picker>
                            </div>
                            <small class="has-text-danger" v-if="errors.start_date">{{ errors.start_date[0] }}</small>
                        </v-flex>
                        <!-- <v-flex sm2 style="margin-left: 10px">
                            <div class="block">
                                <label style="float: left">End date</label>
                                <el-date-picker format="yyyy/MM/dd" value-format="yyyy-MM-dd" v-model="form.end_date" type="date" placeholder="Pick a day">
                                </el-date-picker>
                            </div>
                            <small class="has-text-danger" v-if="errors.end_date">{{ errors.end_date[0] }}</small>
                        </v-flex> -->

                        <!-- Delivery -->
                        <v-flex sm3 style="margin-left: 10px" v-if="form.report == 'Delivered'">
                            <div class="block">
                                <label style="float: left">Delivery Date</label>
                                <!-- <el-date-picker format="yyyy/MM/dd" value-format="yyyy-MM-dd" v-model="form.delivery_start_date" type="date" placeholder="Pick a day">
                                </el-date-picker> -->

                                <el-date-picker v-model="form.delivered_on" type="daterange" align="right" start-placeholder="Start Date" end-placeholder="End Date" style="width: 100%;" format="yyyy/MM/dd" value-format="yyyy-MM-dd">
                                </el-date-picker>
                            </div>
                        </v-flex>
                        <v-flex sm3 style="margin-left: 10px" v-if="form.report == 'Dispatched'">
                            <div class="block">
                                <label style="float: left">Dispatch date</label>

                                <el-date-picker v-model="form.dispatched_on" type="daterange" align="right" start-placeholder="Start Date" end-placeholder="End Date" style="width: 100%;" format="yyyy/MM/dd" value-format="yyyy-MM-dd">
                                </el-date-picker>
                            </div>
                        </v-flex>
                        <!-- Delivery -->

                        <!-- Return -->
                        <v-flex sm3 style="margin-left: 10px" v-if="form.report == 'Returned'">
                            <div class="block">
                                <label style="float: left">Return Date</label>
                                <!-- <el-date-picker format="yyyy/MM/dd" value-format="yyyy-MM-dd" v-model="form.return_start_date" type="date" placeholder="Pick a day">
                                </el-date-picker> -->

                                <el-date-picker v-model="form.returned_on" type="daterange" align="right" start-placeholder="Start Date" end-placeholder="End Date" style="width: 100%;" format="yyyy/MM/dd" value-format="yyyy-MM-dd">
                                </el-date-picker>
                            </div>
                        </v-flex>
                        <!-- <v-flex sm2 style="margin-left: 10px" v-if="form.report == 'Returned'">
                            <div class="block">
                                <label style="float: left">Return End date</label>
                                <el-date-picker format="yyyy/MM/dd" value-format="yyyy-MM-dd" v-model="form.return_end_date" type="date" placeholder="Pick a day">
                                </el-date-picker>
                            </div>
                        </v-flex> -->
                        <!-- Return -->

                        <v-flex sm2 style="margin-left: 10px">
                            <v-btn-toggle dense background-color="primary" dark style="margin-top: 22px;">
                                <v-btn @click="get_report">
                                    <v-icon>mdi-magnify</v-icon>
                                </v-btn>

                                <!-- <v-btn @click="ex_download">
                                    <v-icon>mdi-file</v-icon>
                                </v-btn> -->

                                <v-btn  v-if="report_show">
                                    <download-excel :data="sales.sales.data" :fields="json_fields" :name="today + form.report + ' report.xls'">
                                        <v-tooltip bottom>
                                            <template v-slot:activator="{ on }">
                                                <v-btn icon v-on="on" slot="activator" class="mx-0">
                                                    <v-icon>mdi-file-excel</v-icon>
                                                </v-btn>
                                            </template>
                                            <span>Download report</span>
                                        </v-tooltip>

                                    </download-excel>
                                </v-btn>
                            </v-btn-toggle>
                        </v-flex>

                        <!-- <v-flex sm2 style="margin-left: 10px">
                        <v-btn text icon color="primary" @click="get_report" style="margin-top: 30px">
                            <v-icon>mdi-magnify</v-icon>
                        </v-btn>
                    </v-flex>
                    <v-flex sm2 style="margin-left: 10px">
                        <download-excel :data="sales.sales" :fields="json_fields" name="Report.xls">
                            <v-tooltip bottom>
                                <template v-slot:activator="{ on }">
                                    <v-btn icon v-on="on" slot="activator" class="mx-0" color="primary">

                                        <v-icon>mdi-file-excel</v-icon>
                                    </v-btn>
                                </template>
                                <span>Download report</span>
                            </v-tooltip>

                        </download-excel>
                    </v-flex> -->

                    </v-layout>
                </v-card-text>

                <v-spacer></v-spacer>
            </v-card>

            <!-- <v-card style="width: 100%;padding: 30px 0 40px 20px;">
                <v-card-text>
                    <el-collapse accordion>
                        <el-collapse-item name="1">
                            <template slot="title">
                                Files<i class="header-icon el-icon-info"></i>
                            </template>
                            <div>
                                <v-btn text icon color="primary" @click="getFiles">
                                    <v-icon>mdi-refresh</v-icon>
                                </v-btn>
                                <v-list dense>
                                    <v-subheader>Files</v-subheader>
                                    <v-list-item-group v-model="selectedItem" color="primary">
                                        <v-list-item v-for="(item, i) in files" :key="i" :href="item.path" target="_blank">
                                            <v-list-item-icon>
                                                <v-icon>mdi-file-excel</v-icon>
                                            </v-list-item-icon>
                                            <v-list-item-content>
                                                <v-list-item-title>{{ item.name }}</v-list-item-title>
                                                <v-list-item-sub-title>{{ item.date }}</v-list-item-sub-title>
                                            </v-list-item-content>
                                        </v-list-item>
                                    </v-list-item-group>
                                </v-list>
                            </div>
                        </el-collapse-item>
                    </el-collapse>
                </v-card-text>
            </v-card> -->

            <v-chip-group mandatory>
                <v-chip color="success">
                    Total {{stats_data.total}}
                </v-chip>
                <v-chip color="success">
                    Delivered {{ stats_data.delivered }}
                </v-chip>
                <v-chip color="primary">
                    Dispatched {{ stats_data.dispatched }}
                </v-chip>
                <v-chip>
                    Scheduled {{ stats_data.scheduled }}
                </v-chip>
                <v-chip color="error">
                    Returned {{ stats_data.returned }}
                </v-chip>
                <v-chip color="warning">
                    Pending {{ stats_data.pending }}
                </v-chip>
            </v-chip-group>
            <!-- <Kanbanview v-show="kanban"></Kanbanview> -->
            <Tableview v-if="report_show" :headers="headers" />
        </v-layout>
    </div>

    <form action="/package_download" method="post" ref="form" target="_blank">
        <input type="hidden" name="_token" :value="csrf">
        <input type="hidden" name="packages" :value="serialize_data">
    </form>
    <form action="/picking_list" method="post" ref="picking_form" target="_blank">
        <input type="hidden" name="_token" :value="csrf">
        <input type="hidden" name="packages" :value="serialize_pick">
    </form>
    <form action="/export_dispatch" method="post" ref="dispatch_form" target="_blank">
        <input type="hidden" name="_token" :value="csrf">
        <input type="hidden" name="packages" :value="serialize_data">
    </form>
    <form action="/ex_download" method="post" ref="ex_download" target="_blank">
        <input type="hidden" name="_token" :value="csrf">
        <input type="hidden" name="packages" :value="serialize_data">
    </form>
</div>
</template>

<script>
// import Kanbanview from './Kanbanview'
import Tableview from './Tableview'
import moment from 'moment'
import {
    mapState
} from "vuex";
export default {
    name: 'report',
    props: ['user'],
    components: {
        // Kanbanview,
        Tableview
    },
    data() {
        return {
            selectedItem: 0,
            stats_data: {
                total: 0,
                delivered: 0,
                returned: 0,
                dispatched: 0,
                scheduled: 0,
                pending: 0
            },
            csrf: document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content"),
            kanban: false,
            toggle_exclusive: 2,
            form: {},
            report_show: false,
            reports: [{
                    report: 'Delivered',
                },
                {
                    report: 'Returned',
                },
                // {
                //     report: 'Reshipped',
                // },
                {
                    report: 'Dispatched',
                },
                {
                    report: 'Status',
                },
                {
                    report: 'Product',
                },
                {
                    report: 'Zone',
                },
                {
                    report: 'Custom',
                }
            ],

            json_fields: {
                'Order Number': 'order_no',
                'Created On': 'created_at',
                'Cod amount': 'total',
                'Quantity': 'quantity',
                // 'Cod amount': 'amount',
                'Product Name': 'product_name',
                'Client Name': 'client.name',
                'Client Phone': 'client.phone',
                'Client Address': 'client.address',
                'Vendor Name': 'seller.name',
                'Vendor Phone': 'seller.phone',
                'Vendor Address': 'seller.address',
                'Delivery status': 'delivery_status',
                'status': 'status',
                'Delivery Date': 'delivery_date',
                'Delivered On': 'delivered_on',
                'Dispatched On': 'dispatched_on',
                'Returned On': 'returned_on',
                'Charges': 'shipping_charges',
                'Mpesa Code': 'mpesa_code',
                'Notes': 'customer_notes',
            },
            headers: []
        }
    },
    methods: {
        getZones() {
            var payload = {
                model: 'zones',
                update: 'updateZone',
            }
            this.$store.dispatch('getItems', payload);
        },
        get_report() {
            var payload = {
                model: 'reports',
                update: 'updateSaleList',
                data: this.form
            }
            this.$store.dispatch('filterItems', payload).then((response) => {
                // console.log(response.data);
                this.headers = response.data.table_columns
                // eventBus.$emit('changeHeaderEvent', response.data.columns)
                this.report_show = true
                if (this.form.report == 'Custom') {
                    this.json_fields = response.data.columns
                } else {
                    this.json_fields = {
                        'Order Number': 'order_no',
                        'Created On': 'created_at',
                        'Cod amount': 'total',
                        'Quantity': 'quantity',
                        // 'Cod amount': 'amount',
                        'Product Name': 'product_name',
                        'Client Name': 'client.name',
                        'Client Phone': 'client.phone',
                        'Client Address': 'client.address',
                        'Vendor Name': 'seller.name',
                        'Vendor Phone': 'seller.phone',
                        'Vendor Address': 'seller.address',
                        'Delivery status': 'delivery_status',
                        'status': 'status',
                        'Delivery Date': 'delivery_date',
                        'Delivered On': 'delivered_on',
                        'Dispatched On': 'dispatched_on',
                        'Returned On': 'returned_on',
                        // 'Charges': 'shipping_charges',
                        'Mpesa Code': 'mpesa_code',
                        'Notes': 'customer_notes',
                    }
                }
                // this.count_data();

                this.stats_data = {
                        total: 0,
                        delivered: 0,
                        returned: 0,
                        dispatched: 0,
                        scheduled: 0,
                        pending: 0
                    }
                this.stats();
            })
        },
        count_data() {

            var payload = {
                model: 'count_data',
                update: 'updateEmpty',
                data: this.form
            }
            this.$store.dispatch('filterItems', payload).then((response) => {
                this.stats_data = response.data;
            })

        },
        stats() {
            this.sales.sales.data.forEach((value) => {
                if (value.delivery_status == 'Delivered') {
                    this.stats_data.delivered += 1
                    this.stats_data.total += 1
                } else if (value.delivery_status == 'Returned') {
                    this.stats_data.returned += 1
                    this.stats_data.total += 1
                } else if (value.delivery_status == 'Dispatched') {
                    this.stats_data.dispatched += 1
                    this.stats_data.total += 1
                } else if (value.delivery_status == 'Scheduled') {
                    this.stats_data.scheduled += 1
                    this.stats_data.total += 1
                } else {
                    this.stats_data.pending += 1
                    this.stats_data.total += 1
                }
            });
        },

        set_fields(data) {
            this.json_fields = {
                'Order Number': data['Order no'],
                'Created On': 'created_at',
                'Cod amount': 'total',
                'Product Name': 'product_name',
                'Quantity': data['quantity'],
                'Client Name': 'client.name',
                'Client Phone': 'client.phone',
                'Client Address': 'client.address',
                'Vendor Name': 'seller.name',
                'Vendor Phone': 'seller.phone',
                'Vendor Address': 'seller.address',
                'Delivery status': 'delivery_status',
                'status': 'status',
                'Delivery Date': 'delivery_date',
                'Delivered On': 'delivered_on',
                'Dispatched On': 'dispatched_on',
                'Shipping Charges': 'shipping_charges',
                'Mpesa Code': 'mpesa_code',
                'Remarks': 'notes',
            }
        },
        get_custom_report(report) {
            if (report == 'Custom') {
                var payload = {
                    model: 'custom_reports',
                    update: 'updateCustomReport',
                    // data: this.form
                }
                this.$store.dispatch('getItems', payload)
            }
        },
        getStatus() {
            var payload = {
                model: 'statuses',
                update: 'updateStatusList',
            }
            this.$store.dispatch("getItems", payload);
        },
        downloadPackage() {
            this.$refs.form.submit()
        },
        picking_list() {
            this.$refs.picking_form.submit()
        },
        export_dispatch() {
            this.$refs.dispatch_form.submit()
        },
        ex_download() {
            this.$refs.ex_download.submit()
        },
        async serialize_d(packaged) {
            // alert('test')
            return this.serialize_data = JSON.stringify(packaged)
            // return JSON.stringify(this.form);
            //     this.$refs.form.submit()
            // eventBus.$emit('printPackageEvent', packaged)
        },
        getSellers() {
            var payload = {
                model: "/seller/sellers",
                update: "updateSellerList"
            };
            this.$store.dispatch("getItems", payload);
        },

        getFiles() {
            var payload = {
                model: "/files",
                update: "updateFiles"
            };
            this.$store.dispatch("getItems", payload);
        },
        getProducts() {
            var payload = {
                model: 'products',
                update: 'updateProductsList'
            }
            this.$store.dispatch("getItems", payload);

        }
    },
    mounted() {
        this.getSellers();
        this.getStatus();
        this.getZones();
        this.getProducts();
    },
    computed: {
        ...mapState(['sellers', 'statuses', 'sales', 'custom_report', 'loading', 'errors', 'products', 'zones', 'files']),
        serialize_data() {
            // var start_date = this.form.start_date
            // var end_date = this.form.end_date
            // var client = this.form.client
            // var dates_ = {
            //     'start_date': start_date,
            //     'end_date': end_date,
            //     'client': client,
            // }
            // return JSON.stringify(dates_)

            var data = {
                'created_at': this.form.created_at,
                'start_date': this.form.start_date,
                'end_date': this.form.end_date,
                'client': this.form.client,
                'report': this.form.report,
                'delivered_on': this.form.delivered_on,
                'delivery_start_date': this.form.delivery_start_date,
                'delivery_end_date': this.form.delivery_end_date,
                'returned_on': this.form.returned_on,
                'return_start_date': this.form.return_start_date,
                'return_end_date': this.form.return_end_date,
                'return_start_date': this.form.return_start_date,
                'return_end_date': this.form.return_end_date,
            }
            return JSON.stringify(data)
        },
        serialize_pick() {
            return JSON.stringify(this.packages)
        },
        today() {
            return moment().format('yyyy-MM-DD');
        }
    },
}
</script>

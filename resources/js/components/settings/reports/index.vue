<template>
    <div>
        <div>
            <v-layout justify-center align-center wrap>
                <v-card style="width: 100%;padding: 30px 0 40px 20px;">
                    <v-progress-linear color="primary" :active="loading" :indeterminate="loading" rounded
                        height="4"></v-progress-linear>

                    <v-card-text>

                        <v-layout row wrap>
                            <v-flex sm2 style="margin-left: 10px; margin-bottom: 20px;">
                                <label for="">Report</label>
                                <el-select v-model="form.report" placeholder="Report" clearable filterable
                                    @change="get_custom_report" v-if="user.is_vendor">
                                    <el-option v-for="(item, index) in m_reports" :key="index" :label="item.label"
                                        :value="item.report">
                                    </el-option>
                                </el-select>

                                <el-select v-model="form.report" placeholder="Report" clearable filterable
                                    @change="get_custom_report" v-else>
                                    <el-option v-for="(item, index) in reports" :key="index" :label="item.label"
                                        :value="item.report">
                                    </el-option>
                                </el-select>
                                <small class="has-text-danger" v-if="errors.report">{{ errors.report[0] }}</small>
                            </v-flex>




                            <v-flex sm2 style="margin-left: 10px; margin-bottom: 20px;">
                                <label for="">Operating unit</label>
                                <el-select v-model="form.ou_id" placeholder="Select" filterable clearable
                                    style="width: 100%;">
                                    <el-option v-for="item in ous" :key="item.id" :label="item.ou"
                                        :value="item.id"></el-option>
                                </el-select>
                                <small class="has-text-danger" v-if="errors.ou_id">{{ errors.ou_id[0] }}</small>
                            </v-flex>



                            <v-flex sm2 style="margin-left: 10px; margin-bottom: 20px;" v-if="form.report == 'Custom'">
                                <label for="">Report</label>
                                <el-select v-model="form.custom" placeholder="Custom Report" clearable filterable>
                                    <el-option v-for="(item, index) in custom_report" :key="index" :label="item.name"
                                        :value="item.id">
                                    </el-option>
                                </el-select>
                                <small class="has-text-danger" v-if="errors.custom">{{ errors.custom[0] }}</small>
                            </v-flex>

                            <v-flex sm2 style="margin-left: 10px; margin-bottom: 20px;" v-if="!user.is_vendor">
                                <label for="">Client</label>
                                <el-select v-model="form.client" multiple placeholder="Client" clearable filterable>
                                    <el-option v-for="item in sellers.data" :key="item.id" :label="item.name"
                                        :value="item.id">
                                    </el-option>
                                </el-select>
                            </v-flex>
                            <v-flex sm3 style="margin-left: 10px; margin-bottom: 20px;" v-if="!user.is_vendor">
                                <label for="">Zone to</label>
                                <el-select v-model="form.zone_to" placeholder="Zone to" clearable filterable
                                    style="width: 100%;" multiple>
                                    <el-option v-for="item in zones" :key="item.id" :label="item.name" :value="item.id">
                                    </el-option>
                                </el-select>
                            </v-flex>

                            <v-flex sm3 style="margin-left: 10px; margin-bottom: 20px;"
                                v-if="!user.is_vendor && form.report == 'Dispatched'">
                                <label for="">Rider</label>
                                <el-select v-model="form.rider_id" placeholder="Rider" clearable filterable
                                    style="width: 100%;">
                                    <el-option v-for="item in riders" :key="item.id" :label="item.name" :value="item.id">
                                    </el-option>
                                </el-select>
                            </v-flex>
                            <v-flex sm2 v-if="!user.is_vendor && form.report == 'Agents'">
                                <label for="">Agent</label>
                                <el-select v-model="form.user_name" placeholder="User" clearable filterable>
                                    <el-option v-for="item in users.data" :key="item.id" :label="item.name"
                                        :value="item.name">
                                    </el-option>
                                </el-select>
                            </v-flex>

                            <v-flex sm2 style="margin-left: 10px; margin-bottom: 20px;">
                                <label for="">Product</label>
                                <!-- <el-select v-model="form.product" placeholder="Product" clearable filterable>
                                    <el-option v-for="item in products.data" :key="item.id" :label="item.product_name"
                                        :value="item.id">
                                    </el-option>
                                </el-select>-->

                                <el-select v-model="form.product" filterable remote reserve-keyword multiple
                                    placeholder="type at least 3 characters" :remote-method="getProduct" :loading="loading"
                                    style="width: 100%;" value-key="id" clearable>
                                    <el-option v-for="item in products.data" :key="item.id" :label="item.product_name"
                                        :value="item.id">
                                    </el-option>
                                </el-select>
                                <small class="has-text-danger" v-if="errors.product">{{ errors.product[0] }}</small>
                            </v-flex>

                            <v-flex sm2 v-if="form.report == 'Status'">
                                <label>Status</label>
                                <el-select v-model="form.delivery_status" filterable clearable placeholder="Select"
                                    style="width: 100%;" multiple>
                                    <el-option v-for="item in statuses" :key="item.id" :label="item.status"
                                        :value="item.status">
                                    </el-option>
                                </el-select>
                            </v-flex>

                            <v-flex sm3 style="margin-left: 10px">
                                <div class="block">
                                    <label style="float: left">Date</label>
                                    <el-date-picker v-model="form.created_at" type="daterange" align="right"
                                        start-placeholder="Start Date" end-placeholder="End Date" style="width: 100%;"
                                        format="yyyy/MM/dd" value-format="yyyy-MM-dd">
                                    </el-date-picker>
                                </div>
                                <small class="has-text-danger" v-if="errors.start_date">{{ errors.start_date[0] }}</small>
                            </v-flex>

                            <!-- Delivery -->
                            <v-flex sm3 style="margin-left: 10px" v-if="form.report == 'Delivered'">
                                <div class="block">
                                    <label style="float: left">Delivery Date</label>

                                    <el-date-picker v-model="form.delivered_on" type="daterange" align="right"
                                        start-placeholder="Start Date" end-placeholder="End Date" style="width: 100%;"
                                        format="yyyy/MM/dd" value-format="yyyy-MM-dd">
                                    </el-date-picker>
                                </div>
                            </v-flex>
                            <v-flex sm3 style="margin-left: 10px" v-if="form.report == 'Dispatched'">
                                <div class="block">
                                    <label style="float: left">Dispatch date</label>

                                    <el-date-picker v-model="form.dispatched_on" type="daterange" align="right"
                                        start-placeholder="Start Date" end-placeholder="End Date" style="width: 100%;"
                                        format="yyyy/MM/dd" value-format="yyyy-MM-dd">
                                    </el-date-picker>
                                </div>
                            </v-flex>
                            <!-- Delivery -->

                            <!-- Return -->
                            <v-flex sm3 style="margin-left: 10px" v-if="form.report == 'Returned'">
                                <div class="block">
                                    <label style="float: left">Return Date</label>


                                    <el-date-picker v-model="form.returned_on" type="daterange" align="right"
                                        start-placeholder="Start Date" end-placeholder="End Date" style="width: 100%;"
                                        format="yyyy/MM/dd" value-format="yyyy-MM-dd">
                                    </el-date-picker>
                                </div>
                            </v-flex>
                            <!-- Return -->
                            <!-- Awaiting Return -->

                            <v-flex sm3 style="margin-left: 10px" v-if="form.report == 'awaiting_returned'">
                                <div class="block">
                                    <label style="float: left">Awaiting Return Date</label>


                                    <el-date-picker v-model="form.return_date" type="daterange" align="right"
                                        start-placeholder="Start Date" end-placeholder="End Date" style="width: 100%;"
                                        format="yyyy/MM/dd" value-format="yyyy-MM-dd">
                                    </el-date-picker>
                                </div>
                            </v-flex>
                            <!-- Awaiting Return -->

                            <!-- Return -->
                            <v-flex sm3 style="margin-left: 10px">
                                <div class="block">
                                    <label style="float: left">Status Date</label>


                                    <el-date-picker v-model="form.status_date" type="daterange" align="right"
                                        start-placeholder="Start Date" end-placeholder="End Date" style="width: 100%;"
                                        format="yyyy/MM/dd" value-format="yyyy-MM-dd">
                                    </el-date-picker>
                                </div>
                            </v-flex>
                            <!-- Return -->

                            <v-flex sm2 style="margin-left: 10px">
                                <v-btn-toggle dense background-color="primary" dark style="margin-top: 22px;">
                                    <v-btn @click="get_report">
                                        <v-icon>mdi-magnify</v-icon>
                                    </v-btn>

                                    <v-btn v-if="report_show">
                                        <download-excel :data="sales.sales.data" :fields="merchant_json"
                                            :name="today + form.report + ' report.xls'" v-if="form.report == 'Status'">
                                            <v-tooltip bottom>
                                                <template v-slot:activator="{ on }">
                                                    <v-btn icon v-on="on" slot="activator" class="mx-0">
                                                        <v-icon>mdi-file-excel</v-icon>
                                                    </v-btn>
                                                </template>
                                                <span>Download report</span>
                                            </v-tooltip>

                                        </download-excel>
                                        <download-excel :data="sales.sales.data" :fields="awaiting_json"
                                            :name="today + form.report + ' report.xls'" v-else-if="form.report == 'awaiting_returned'">
                                            <v-tooltip bottom>
                                                <template v-slot:activator="{ on }">
                                                    <v-btn icon v-on="on" slot="activator" class="mx-0">
                                                        <v-icon>mdi-file-excel</v-icon>
                                                    </v-btn>
                                                </template>
                                                <span>Download report</span>
                                            </v-tooltip>

                                        </download-excel>
                                        <download-excel :data="sales.sales.data"
                                            :fields="(form.report == 'Dispatched') ? json_fields : remittance_json"
                                            :name="today + form.report + ' report.xls'" v-else>
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

                                    <v-btn v-if="report_show">
                                        <v-tooltip bottom>
                                            <template v-slot:activator="{ on }">
                                                <v-btn icon v-on="on" slot="activator" class="mx-0" @click="report_form">
                                                    <v-icon>mdi-file-excel</v-icon>
                                                </v-btn>
                                            </template>
                                            <span>Download report</span>
                                        </v-tooltip>

                                    </v-btn>
                                </v-btn-toggle>
                            </v-flex>


                        </v-layout>
                    </v-card-text>

                    <v-spacer></v-spacer>
                </v-card>


                <v-chip-group mandatory>
                    <v-chip v-if="sales.sales">
                        Total {{ sales.sales.total }}
                    </v-chip>
                    <v-chip :color="(index == 'Delivered') ? 'success' : 'primary'" v-for="(data, index) in stats_data"
                        :key="index">
                        {{ index }} {{ data }}
                    </v-chip>
                </v-chip-group>

                <!-- <Kanbanview v-show="kanban"></Kanbanview> -->

                <v-flex sm12 style="margin-top: 30px" v-if="sales.sales">
                    <v-pagination v-model="sales.sales.current_page" :length="sales.sales.last_page" total-visible="5"
                        @input="next_page(sales.sales.path, sales.sales.current_page)" circle
                        v-if="sales.sales.last_page > 1"></v-pagination>
                </v-flex>
                <Tableview v-if="report_show" :headers="headers" :report="'Report'" />
            </v-layout>
        </div>
        <form action="/report_download" method="post" ref="report_form" target="_blank">
            <input type="hidden" name="_token" :value="csrf">
            <input type="hidden" name="packages" :value="serialize_data">
        </form>

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
                label: 'Delivery Report',
            },
            {
                report: 'Returned',
                label: 'Returns Report',
            },
            {
                report: 'awaiting_returned',
                label: 'Awaiting Return',
            },
            {
                report: 'Dispatched',
                label: 'Out Scan Report',
            },
            {
                report: 'Status',
                label: 'Merchant Report',
            },
            {
                report: 'Remittance',
                label: 'Remittance',
            },
            {
                report: 'Product',
                label: 'Product Report',
            },
            {
                report: 'Zone',
                label: 'Zone Report',
            },
            {
                report: 'Custom',
                label: 'Custom Report',
            }],

            m_reports: [
                {
                    report: 'Delivered',
                    label: 'Delivery Report',
                },
                {
                    report: 'Returned',
                    label: 'Returns Report',
                },
                {
                    report: 'Dispatched',
                    label: 'Out Scan Report',
                },
                {
                    report: 'Status',
                    label: 'Merchant Report',
                },
                {
                    report: 'Remittance',
                    label: 'Remittance',
                },
                // {
                //     report: 'Product',
                //     label: 'Product Report',
                // },
                // {
                //     report: 'Zone',
                //     label: 'Zone Report',
                // },
                // {
                //     report: 'Custom',
                //     label: 'Custom Report',
                // }
            ],

            json_fields: {
                'No': 'order.st',
                'Order No': 'order_no',
                'destination Type': 'order.st',
                'Receiver Name': 'client.name',
                'Receiver Address': 'client.address',
                'Receiver Email': 'client.email',
                'Receiver Phone': 'client.phone',
                'Receiver Town': 'client.city',
                'Product': 'product_name',
                'Special Instruction': 'customer_notes',
                'Payment Type': 'payment_method',
                'Cash On Delivery': 'total_price',
                'Order Status': 'delivery_status',
                'Delivery Date': 'delivery_date',
                'Amount': 'total_price',
            },

            remittance_json: {
                'Order No': 'order_no',
                'Total Amount': 'total_price',
                'Item Quantity': 'quantity',
                'Item Description': 'product_name',
                'Client Name': 'client.name',
                'Location': 'client.address',
                'Town': 'client.city',
                'Phone': 'client.phone',
                'Upsell(Yes or No)': 'No',
                'Status': 'delivery_status',
                'Status Date': 'updated_at',
                'Delivered On': 'delivered_on',
                'Delivery Fee': 'order.st',
                'Fulfillment Fee': 'order.st',
                'COD': 'order.st',
                'Order Total': 'order.st',
                'Special Remarks': 'customer_notes',
                'Custom Reason': 'customer_notes',
            },

            merchant_json: {
                'Order No': 'order_no',
                'Product Item': 'product_name',
                'Receiver Name': 'client.name',
                'Receiver Address': 'client.address',
                'Receiver Email': 'client.email',
                'Receiver Phone': 'client.phone',
                'Receiver Gender': 'client.gender',
                'Receiver Country': 'client.country',
                'Receiver Town': 'client.city',
                'Receiver Latitude': 'client.city',
                'Receiver Longitude': 'client.city',
                'Special Instructions': 'customer_notes',
                'Payment Type': 'payment_method',
                'Cash On Delivery': 'total_price',
                'Insurance': '0',
                'Order Status': 'delivery_status',
                'Custom Reason': 'customer_notes',
                'Created At': 'created_at',
                'Status Date': 'updated_at',
                'Schedule Date': 'schedule_date',
                'Delivery Date': 'delivery_date',
                'Quantity': 'quantity',
                'Amount': 'total_price',
                'Agent': 'agent.name'
            },

            awaiting_json: {
                'Order No': 'order_no',
                'Total Amount': 'total_price',
                'Product Item': 'product_name',
                'Client Name': 'client.name',
                'Client Address': 'client.address',
                'Client Phone': 'client.phone',
                'Status': 'delivery_status'
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
        getRiders() {
            var payload = {
                model: 'rider/riders',
                update: 'updateRidersList'
            }
            this.$store.dispatch("getItems", payload);
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
                        'No': 'order.st',
                        'Order No': 'order_no',
                        'destination Type': 'order.st',
                        'Receiver Name': 'client.name',
                        'Receiver Address': 'client.address',
                        'Receiver Email': 'client.email',
                        'Receiver Phone': 'client.phone',
                        'Receiver Town': 'client.city',
                        'Product': 'product_name',
                        'Special Instruction': 'customer_notes',
                        'Payment Type': 'payment_method',
                        'Cash On Delivery': 'total_price',
                        'Order Status': 'delivery_status',
                        'Delivery Date': 'delivery_date',
                        'Amount': 'total_price',
                    }

                }
                // this.count_data();

                this.stats_data = response.data.counts;

                // this.stats_data = {
                //     total: 0,
                //     delivered: 0,
                //     returned: 0,
                //     dispatched: 0,
                //     scheduled: 0,
                //     pending: 0
                // }
                // this.stats();
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
                'No': '',
                'Order No': 'order_no',
                'destination Type': '',
                'Receiver Name': 'client.name',
                'Receiver Address': 'client.address',
                'Receiver Email': 'client.email',
                'Receiver Phone': 'client.phone',
                'Receiver Town': 'client.city',
                'Product': 'product_name',
                'Special Instruction': 'customer_notes',
                'Payment Type': 'payment_method',
                'Cash On Delivery': 'total_price',
                'Order Status': 'delivery_status',
                'Delivery Date': 'delivery_date',
                'Amount': 'total_price',
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
        report_form() {
            this.$refs.report_form.submit()
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

        getProduct(search) {
            // console.log(search);
            if (search.length > 2) {
                var payload = {
                    model: 'product_filter_search',
                    update: 'updateProductsList',
                    data: {
                        search: search,
                        vendors: this.form.client
                    }
                }
                this.$store.dispatch("filterItems", payload);
            }
        },

        getOus() {
            var payload = {
                model: 'ous',
                update: 'updateOu',
            }
            this.$store.dispatch('getItems', payload);
        },
        next_page(path, page) {
            var payload = {
                data: this.form,
                path: path,
                page: page,
                update: 'updateSaleList'
            }
            this.$store.dispatch("nextPagePost", payload);
        }
    },
    mounted() {
        this.getSellers();
        this.getStatus();
        this.getZones();
        // this.getProducts();
        this.getRiders();
        this.getOus();
    },
    computed: {
        ...mapState(['sellers', 'statuses', 'sales', 'custom_report', 'loading', 'errors', 'products', 'zones', 'files', 'riders', 'ous']),
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
                'delivery_status': this.form.delivery_status,
                'delivered_on': this.form.delivered_on,
                'delivery_start_date': this.form.delivery_start_date,
                'delivery_end_date': this.form.delivery_end_date,
                'returned_on': this.form.returned_on,
                'return_start_date': this.form.return_start_date,
                'return_end_date': this.form.return_end_date,
                'return_start_date': this.form.return_start_date,
                'return_end_date': this.form.return_end_date,
                'status_date': this.form.status_date,
                'ou_id': this.form.ou_id,
                'zone_to': this.form.zone_to,
                'product': this.form.product,
                'rider_id': this.form.rider_id
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

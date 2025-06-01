<template>
<v-card class="analytics" elevation="" style="background: #283046">
    <v-row>
      <v-col cols="12" lg="6" style="margin-left: 20px">
        <v-menu v-model="menu" :close-on-content-click="false" offset-y>
            <template v-slot:activator="{ on }">
                <v-text-field v-on="on" v-model="dateRangeText" label="Select Date Range"  color="white"></v-text-field>
            </template>
            <v-card>
            <v-card-text>
                <v-date-picker v-model="form.date_range" range  color="teal" ></v-date-picker>
            </v-card-text>
            <v-card-actions>
                <v-btn color="primary" @click="applyDateRange">Apply</v-btn>
            </v-card-actions>
            </v-card>
        </v-menu>
        </v-col>

        <v-col cols="12" lg="5"  style="margin-left: 20px">  
            <v-select color="white"  v-model="form.ou" append-outer-icon="mdi-map" :items="ous" label="Operationg unit" item-text="ou" item-value="id" outlined  @change="getDashboardData"></v-select>

        </v-col>
    </v-row>

    <v-row :align="align" no-gutters v-if="loaded">
        <v-col>
            <v-card class="mx-auto text-center" color="#283046" dark>
                <v-card-text>
                    <v-btn class="ma-2" outlined fab color="#28c76f">
                        <v-icon color="#28c76f">mdi-check-circle</v-icon>
                    </v-btn>

                    <h2 style="color: #d0d2d6;font-weight: 600!important; font-size: 1.714rem">{{ dashboard_data.week_delivered.count }}</h2>
                    <small>Delivered Last 1 week</small>

                </v-card-text>
                <v-divider></v-divider>
                <v-card-text>
                    <v-sheet color="#283046">
                        <v-sparkline :value="dashboard_data.week_delivered.data" color="#fff" height="100" padding="24" stroke-linecap="round" :smooth="16" :gradient="['#283046', '#28c76f', '#1feaea']" :line-width="3">
                            <template v-slot:label="item">
                                {{ item.value }}
                            </template>
                        </v-sparkline>
                    </v-sheet>
                </v-card-text>

            </v-card>
        </v-col>
        <v-col style="margin-left: 3px">
            <v-card class="mx-auto text-center" color="#283046" dark>
                <v-card-text>
                    <v-btn class="ma-2" outlined fab color="#3366cc">
                        <v-icon color="#3366cc">mdi-alarm-multiple</v-icon>
                    </v-btn>

                    <h2 style="color: #d0d2d6;font-weight: 600!important; font-size: 1.714rem">{{ dashboard_data.week_scheduled.count }}</h2>
                    <small>Scheduled Last 1 week</small>

                </v-card-text>
                <v-divider></v-divider>
                <v-card-text>
                    <v-sheet color="#283046">
                        <v-sparkline :value="dashboard_data.week_scheduled.data" color="#fff" height="100" padding="24" stroke-linecap="round" :smooth="16" :gradient="['#283046', '#3366cc', '#1feaea']" :line-width="3">
                            <template v-slot:label="item">
                                {{ item.value }}
                            </template>
                        </v-sparkline>
                    </v-sheet>
                </v-card-text>

            </v-card>
        </v-col>
        <v-col style="margin-left: 3px">
            <v-card class="mx-auto text-center" color="#283046" dark>
                <v-card-text>
                    <v-btn class="ma-2" outlined fab color="#1dc5bd">
                        <v-icon color="#1dc5bd">mdi-bike-fast</v-icon>
                    </v-btn>

                    <h2 style="color: #d0d2d6;font-weight: 600!important; font-size: 1.714rem">{{ dashboard_data.week_dispatched.count }}</h2>
                    <small>In Transit Last 1 week</small>

                </v-card-text>
                <v-divider></v-divider>
                <v-card-text>
                    <v-sheet color="#283046">
                        <v-sparkline :value="dashboard_data.week_dispatched.data" color="#fff" height="100" padding="24" stroke-linecap="round" :smooth="16" :gradient="['#283046', '#1dc5bd', '#1feaea']" :line-width="3">
                            <template v-slot:label="item">
                                {{ item.value }}
                            </template>
                        </v-sparkline>
                    </v-sheet>
                </v-card-text>

            </v-card>
        </v-col>
        <v-col style="margin-left: 3px">
            <v-card class="mx-auto text-center" color="#283046" dark>
                <v-card-text>
                    <v-btn class="ma-2" outlined fab color="#ff0000bf">
                        <v-icon color="#ff0000bf">mdi-keyboard-return</v-icon>
                    </v-btn>

                    <h2 style="color: #d0d2d6;font-weight: 600!important; font-size: 1.714rem">{{ dashboard_data.week_returned.count }}</h2>
                    <small>Returned Last 1 week</small>

                </v-card-text>
                <v-divider></v-divider>
                <v-card-text>
                    <v-sheet color="#283046">
                        <v-sparkline :value="dashboard_data.week_returned.data" color="#fff" height="100" padding="24" stroke-linecap="round" :smooth="16" :gradient="['#283046', '#ff0000bf', '#1feaea']" :line-width="3">
                            <template v-slot:label="item">
                                {{ item.value }}
                            </template>
                        </v-sparkline>
                    </v-sheet>
                </v-card-text>

            </v-card>
        </v-col>
    </v-row>

    <v-divider></v-divider>

    <v-row :align="align" no-gutters v-if="loaded">
        <v-col>
            <v-card class="mx-auto text-center" color="#283046" dark>
                <v-card-text>
                    <v-btn class="ma-2" outlined fab color="#28c76f">
                        <v-icon color="#28c76f">mdi-check-circle</v-icon>
                    </v-btn>

                    <h2 style="color: #d0d2d6;font-weight: 600!important; font-size: 1.714rem">{{ dashboard_data.delivered_count }}</h2>
                    <small>Delivered</small>

                </v-card-text>

            </v-card>
        </v-col>
        <v-col style="margin-left: 3px">
            <v-card class="mx-auto text-center" color="#283046" dark>
                <v-card-text>
                    <v-btn class="ma-2" outlined fab color="#3366cc">
                        <v-icon color="#3366cc">mdi-alarm-multiple</v-icon>
                    </v-btn>

                    <h2 style="color: #d0d2d6;font-weight: 600!important; font-size: 1.714rem">{{ dashboard_data.scheduled_count }}</h2>
                    <small>Scheduled</small>

                </v-card-text>
            </v-card>
        </v-col>
        <v-col style="margin-left: 3px">
            <v-card class="mx-auto text-center" color="#283046" dark>
                <v-card-text>
                    <v-btn class="ma-2" outlined fab color="#1dc5bd">
                        <v-icon color="#1dc5bd">mdi-bike-fast</v-icon>
                    </v-btn>

                    <h2 style="color: #d0d2d6;font-weight: 600!important; font-size: 1.714rem">{{ dashboard_data.dispatched_count }}</h2>
                    <small>In Transit</small>

                </v-card-text>
            </v-card>
        </v-col>
        <v-col style="margin-left: 3px">
            <v-card class="mx-auto text-center" color="#283046" dark>
                <v-card-text>
                    <v-btn class="ma-2" outlined fab color="#ff0000bf">
                        <v-icon color="#ff0000bf">mdi-keyboard-return</v-icon>
                    </v-btn>

                    <h2 style="color: #d0d2d6;font-weight: 600!important; font-size: 1.714rem">{{ dashboard_data.returns_count }}</h2>
                    <small>Returned</small>

                </v-card-text>
            </v-card>
        </v-col>
    </v-row>

    <v-row :align="align" no-gutters style="margin-top: 2px">
        <v-col>
            <v-card class="mx-auto text-center" color="#283046" dark>
                <v-card-text>
                    <v-row :align="align" no-gutters style="margin-top: 2px">
                        <v-col style="text-align: left;">
                            <v-btn class="ma-2" outlined fab color="#28c76f">
                                <v-icon color="#28c76f">mdi-cart-outline</v-icon>
                            </v-btn>

                            <h2 style="color: #d0d2d6;font-weight: 600!important; font-size: 1.714rem">{{ dashboard_data.week_delivered.count }}</h2>
                            <small>Avg Delivery this week</small>
                            <h2> <b :style="[dashboard_data.perc_delivery>0 ? {'color': '#28c76f'} : {'color': '#ff0000bf'}]">{{ dashboard_data.perc_delivery }}%</b> vs last 7 days</h2>
                            <v-btn elevation="2" color="#28c76f" to="/report">Go to reports</v-btn>
                        </v-col>
                        <v-col>
                            <myDelivered :height="255" />
                        </v-col>
                    </v-row>
                </v-card-text>
            </v-card>
        </v-col>
        <v-col style="margin-left: 2px">
            <v-card class="mx-auto text-center" color="#283046" dark>
                <v-card-text>
                    <v-row :align="align" no-gutters style="margin-top: 2px">
                        <v-col style="text-align: left;">
                            <v-btn class="ma-2" outlined fab color="#ff0000bf">
                                <v-icon color="#ff0000bf">mdi-arrow-right</v-icon>
                            </v-btn>

                            <h2 style="color: #d0d2d6;font-weight: 600!important; font-size: 1.714rem">{{ dashboard_data.week_returned.count }}</h2>
                            <small>Avg returns this week</small>
                            <h2> <b :style="[dashboard_data.perc_return>0 ? {'color': '#ff0000bf'} : {'color': '#28c76f'}]">{{ dashboard_data.perc_return }}%</b> vs last 7 days</h2>

                            <v-btn elevation="2" color="#ff0000bf" to="/report">Go to reports</v-btn>

                        </v-col>
                        <v-col>
                            <myReturn :height="255" />
                        </v-col>
                    </v-row>
                </v-card-text>
            </v-card>
        </v-col>
    </v-row>

    <v-row :align="align" no-gutters style="margin-top: 2px">
        <v-col style="text-align: center;">
            <v-card class="mx-auto text-center" color="#283046" dark>
                <v-row :align="align" no-gutters style="margin-top: 2px">
                    <v-col style="text-align: center;" cols="3">
                        <v-btn class="ma-2" outlined fab color="#6b61dea1">
                            <v-icon color="#6b61dea1">mdi-cart-outline</v-icon>
                        </v-btn>

                        <h2 style="color: #d0d2d6;font-weight: 600!important; font-size: 1.714rem">{{ dashboard_data.total_sales_count }}</h2>
                        <!-- <small>Total orders</small> -->
                        <h2 style="color: #d0d2d6;font-weight: 600!important; font-size: 1.714rem">Total orders</h2>

                        <v-btn elevation="2" color="#6b61dea1" to="/report">Go to reports</v-btn>

                    </v-col>
                    <v-col cols="9">
                        <myChart :height="255" :form="form" />
                    </v-col>
                </v-row>
            </v-card>
        </v-col>
    </v-row>
    <!-- 
    <v-row :align="align" no-gutters style="margin-top: 2px">
        <v-col>
            <v-card class="mx-auto text-center" color="#283046" dark>
                <v-card-text>
                    <myChart :height="255" :form="form" />

                </v-card-text>

            </v-card>
        </v-col>
    </v-row> -->

    <v-row :align="align" no-gutters style="margin-top: 2px" v-if="!user.is_vendor">

        <v-col>
            <v-card class="mx-auto text-center" color="#283046" dark>
                <h2>PRODUCT DETAILS</h2>
                <v-divider></v-divider>
                <ul class="list-group">
                    <v-layout wrap>

                        <v-flex sm6>
                            <v-list dense style="background: #283046;">
                                <v-list-item-group color="primary" v-model="selectedItem">
                                    <!--<v-list-item>

                                        <v-list-item-icon style="margin-top: 18px !important;margin-right: 0 !important">
                                            <v-icon>mdi-quality-low</v-icon>
                                        </v-list-item-icon>
                                        <v-list-item-content style="margin-left: 0px !important">
                                            <v-list-item-title>Low Stock Items</v-list-item-title>
                                        </v-list-item-content>

                                        <v-list-item-action>
                                            <v-btn text color="primary">
                                                2
                                            </v-btn>
                                        </v-list-item-action>
                                    </v-list-item> -->
                                    <v-list-item>
                                        <v-list-item-icon style="margin-top: 18px !important;margin-right: 0 !important">
                                            <v-icon>mdi-basket</v-icon>
                                        </v-list-item-icon>
                                        <v-list-item-content style="margin-left: 0px !important">
                                            <v-list-item-title>Products</v-list-item-title>
                                        </v-list-item-content>

                                        <v-list-item-action>
                                            <v-btn text color="primary">
                                                {{ dashboard_data.product_count }}
                                            </v-btn>
                                        </v-list-item-action>
                                    </v-list-item>
                                    <v-list-item>
                                        <v-list-item-icon style="margin-top: 18px !important;margin-right: 0 !important">
                                            <v-icon>mdi-account-circle</v-icon>
                                        </v-list-item-icon>
                                        <v-list-item-content style="margin-left: 0px !important">
                                            <v-list-item-title>Users</v-list-item-title>
                                        </v-list-item-content>

                                        <v-list-item-action>
                                            <v-btn text color="primary">
                                                {{ dashboard_data.user_count }}
                                            </v-btn>
                                        </v-list-item-action>
                                    </v-list-item>
                                </v-list-item-group>
                            </v-list>
                        </v-flex>
                        <v-flex sm6>
                            <pie-chart :data="dashboard_data.active_chart" style="height: 200px;"></pie-chart>
                        </v-flex>
                    </v-layout>
                </ul>
            </v-card>

        </v-col>

        <v-col>
            <v-card class="mx-auto text-center" color="#283046" dark>
                <div class="block">
                  
                    <el-date-picker
                    v-model="form.month"
                    type="monthrange"
                    align="right"
                    unlink-panels
                    range-separator="To"
                    start-placeholder="Start month"
                    end-placeholder="End month"
                    :picker-options="pickerOptions" style="width:100%" @change="dateRange">
                    </el-date-picker>
                </div>
                <h2>Vendor Performance</h2>
                <v-divider></v-divider>
                <v-simple-table style="text-align: left;">
                    <template v-slot:default>
                        <thead>
                            <tr>
                                <th class="text-left">
                                    Name
                                </th>
                                <th class="text-left">
                                    Orders
                                </th>
                                <th class="text-left">
                                    Delivered
                                </th>
                                <th class="text-left">
                                    Returned
                                </th>
                            </tr>
                        </thead>
                        <tbody v-if="vendor_performance">
                            <tr v-for="item in vendor_performance" :key="item.id">
                                <td>{{ item.name }}</td>
                                <td>{{ item.sales_count }}</td>
                                <td>{{ item.delivered_count }}
                                    <el-tag style="float: right;" effect="dark" size="mini" type="info">{{ parseFloat(item.delivered_count/item.sales_count * 100).toFixed(2) }}%</el-tag>
                                </td>
                                <td>{{ item.returned_count }}
                                    <el-tag style="float: right;" effect="dark" size="mini" type="danger">{{ parseFloat(item.returned_count/item.sales_count * 100).toFixed(2) }}% </el-tag>
                                </td>
                            </tr>
                        </tbody>

                        <tbody v-else>
                            <tr v-for="item in dashboard_data.vendor_performance" :key="item.id">
                                <td>{{ item.name }}</td>
                                <td>{{ item.sales_count }}</td>
                                <td>{{ item.delivered_count }}
                                    <el-tag style="float: right;" effect="dark" size="mini" type="info">{{ parseFloat(item.delivered_count/item.sales_count * 100).toFixed(2) }}%</el-tag>
                                </td>
                                <td>{{ item.returned_count }}
                                    <el-tag style="float: right;" effect="dark" size="mini" type="danger">{{ parseFloat(item.returned_count/item.sales_count * 100).toFixed(2) }}% </el-tag>
                                </td>
                            </tr>
                        </tbody>
                    </template>
                </v-simple-table>

                <!-- <el-table :data="dashboard_data.vendor_performance" style="width: 100%" tooltip-effect="dark">
                        <el-table-column prop="name" label="Name" width="180">
                        </el-table-column>
                        <el-table-column prop="sales_count" label="Orders" width="180">
                        </el-table-column>
                        <el-table-column prop="delivered_count" label="Delivered">
                        </el-table-column>
                        <el-table-column prop="returned_count" label="Returned">
                        </el-table-column>
                    </el-table>
                </v-simple-table> -->
                <!--  <ul class="list-group">
                    <el-table :data="dashboard_data.vendor_performance" style="width: 100%">
                        <el-table-column prop="name" label="Name" width="180">
                        </el-table-column>
                        <el-table-column prop="sales_count" label="Orders" width="180">
                        </el-table-column>
                        <el-table-column prop="delivered_count" label="Delivered">
                        </el-table-column>
                        <el-table-column prop="returned_count" label="Returned">
                        </el-table-column>
                    </el-table>
                </ul> -->
            </v-card>

        </v-col>
    </v-row>
</v-card>
</template>

<script>
import moment from 'moment'
import myChart from "./charts/Sales_chart.js";
import myDelivered from "./charts/Delivered.js";
import myReturn from "./charts/Returned.js";
import {
    mapState
} from "vuex";
import axios from 'axios';
export default {
    props: ["user"],
    components: {
        myChart,
        myDelivered,
        myReturn
    },
    data() {
        return {
            menu: false,
            startDate: null,
            endDate: null,
            //   dateRangeText: '',
            loaded: false,
            align: 'center',
            pickerOptions: {
                shortcuts: [{
                    text: 'Last week',
                    onClick(picker) {
                        const end = new Date();
                        const start = new Date();
                        start.setTime(start.getTime() - 3600 * 1000 * 24 * 7);
                        picker.$emit('pick', [start, end]);
                    }
                }, {
                    text: 'Last month',
                    onClick(picker) {
                        const end = new Date();
                        const start = new Date();
                        start.setTime(start.getTime() - 3600 * 1000 * 24 * 30);
                        picker.$emit('pick', [start, end]);
                    }
                }, {
                    text: 'Last 3 months',
                    onClick(picker) {
                        const end = new Date();
                        const start = new Date();
                        start.setTime(start.getTime() - 3600 * 1000 * 24 * 90);
                        picker.$emit('pick', [start, end]);
                    }
                }, {
                    text: 'Last 6 months',
                    onClick(picker) {
                        const end = new Date();
                        const start = new Date();
                        start.setTime(start.getTime() - 3600 * 1000 * 24 * 180);
                        picker.$emit('pick', [start, end]);
                    }
                }, {
                    text: 'Last year',
                    onClick(picker) {
                        const end = new Date();
                        const start = new Date();
                        start.setTime(start.getTime() - 3600 * 1000 * 24 * 365);
                        picker.$emit('pick', [start, end]);
                    }
                }]
            },
            selectedItem: 1,
            vendor_performance: null,
            active_chart: {
                "active": 154,
                "inactive": 26
            },
            monday_sale: null,
            form: {
                year: new Date().getFullYear(),
                ou_id: "",
                date_range: [],
                filter_type: 'year',
                month: ''
            },
            options: [{
                value: '2018',
                label: '2018'
            }, {
                value: '2019',
                label: '2019'
            }, {
                value: '2020',
                label: '2020'
            }],
            currency_code: 'KES',
        }
    },
    methods: {
        applyDateRange() {
            // Handle the selected date range here (e.g., emit an event or perform an action)
            this.getDashboardData();
            this.menu = false;
        },
        resetDateRange() {
            this.form.date_range = null;
            this.updateDateRangeText();
        },
        updateDateRangeText() {
            if (this.form.date_range) {
                this.dateRangeText = `${this.form.date_range.toLocaleDateString()}`;
            } else {
                this.dateRangeText = '';
            }
        },

        openOrders(data) {
            this.$router.push({
                name: "tracking",
                query: {
                    data: data
                }
            });
        },

        dateRange(event) {
            console.log(event)
            var payload = {
                model: 'vendorPerformance',
                update: 'updateEmpty',
                data: event
            }
            this.$store.dispatch('postItems', payload).then((res) => {
                // this.loaded = true;
                this.vendor_performance = res.data;
            })
        },

        getOus() {
            var payload = {
                model: 'ous',
                update: 'updateOu',
            }
            this.$store.dispatch('getItems', payload)
                .then((res) => {
                    // setTimeout(() => this.filteredData(), 2000);
                });
        },
        getDashboardData() {
            var payload = {
                model: 'dashboard_count' + '?ou=' + this.form.ou + '&year=' + this.form.year + '&daterange=' + JSON.stringify(this.form.date_range) + '&filter=' + this.form.filter_type,
                update: 'updateDashboard',
            }
            this.$store.dispatch('getItems', payload).then(() => {
                this.loaded = true;
            })
        },
        getVendorPerformance() {
            axios.get('vendor_performance' + '?ou=' + this.form.ou + '&year=' + this.form.year + '&daterange=' + JSON.stringify(this.form.date_range) + '&filter=' + this.form.filter_type).then((res) => {
                this.vendor_performance = res.data;
            }).catch((error) => {
                console.log("🚀 ~ file: analytics.vue:520 ~ axios.get ~ error:", error)
                
            });
            // var payload = {
            //     model: 'vendor_performance' + '?ou=' + this.form.ou + '&year=' + this.form.year + '&daterange=' + JSON.stringify(this.form.date_range) + '&filter=' + this.form.filter_type,
            //     update: 'UpdateEmpty',
            // }
            // this.$store.dispatch('getItems', payload).then(() => {
            //     this.vendor_performance = res.data;
            // })
        },
    },
    computed: {
        ...mapState(['dashboard_data', 'ous']),
        dateRangeText () {
            if (this.form.date_range) {
                return this.form.date_range.join(' ~ ')
            }
            return '';
        },
    },
    mounted() {
        this.getDashboardData()
        this.getVendorPerformance()
        this.getOus()

    },
    created() {
        this.form.ou_id = this.user.ou_id

        eventBus.$on('Refreshdashboard', data => {
            this.getDashboardData();
        })
    },
    beforeDestroy() {
        clearInterval(this.timer);
    },
    filters: {
        moment(date) {
            return moment(date).format('ddd, MMM Do YYYY');
        },
    },

    beforeRouteEnter(to, from, next) {
        next(vm => {
          if (vm.user.roles[0].name == 'Agent') {
            next("/agent-dashboard");
          } else if(vm.user.roles[0].name == 'Call center') {
            next("/call-center");
          } else {
            next();
          }
        });
      }
}
</script>

<style scoped>
.analytics .v-data-table {
    background: #283046;
}

.theme--dark.v-divider {
    border-color: #293147c2 !important;
}
</style>

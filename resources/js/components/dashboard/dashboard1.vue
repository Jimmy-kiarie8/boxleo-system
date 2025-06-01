<template>
<v-container fluid fill-height>
    <v-card style="padding: 20px; width: 100%;margin-top: -64px;">
        <v-layout justify-center align-center wrap style="margin-top: 0 !important;">
            <!-- <v-flex sm12> -->
            <!-- <v-layout row wrap> -->
            <v-flex sm6>
                <div class="block">
                    <el-date-picker v-model="form.date_range" type="daterange" align="right" unlink-panels range-separator="To" start-placeholder="Start date" end-placeholder="End date" :picker-options="pickerOptions" @change="date_stats('date_range')" format="yyyy/MM/dd" value-format="yyyy-MM-dd" style="width: 100%;">
                    </el-date-picker>
                </div>
            </v-flex>

            <v-flex sm5 offset-sm1>
                <div>
                    <el-select filterable v-model="form.ou" placeholder="Select OU" style="width: 100%;" @change="getDashboardData">
                        <el-option v-for="item in ous" :key="item.id" :label="item.ou" :value="item.id">
                        </el-option>
                    </el-select>
                </div>

            </v-flex>
            <v-flex sm12>
                <el-row :gutter="12" style="width: 100%">
                    <el-col :span="4">
                        <div @click="openPage('delivered')" style="cursor: pointer">
                            <el-card shadow="hover">
                                <div class="text-center">
                                    <p style="font-size: 36px;color: #1093de;">{{ dashboard_data.delivered_count }}</p>
                                    <h5>Delivered</h5>
                                </div>
                            </el-card>
                        </div>
                    </el-col>
                    <el-col :span="4">
                        <div @click="openPage('To be shipped')" style="cursor: pointer">
                            <el-card shadow="hover">
                                <div class="text-center">
                                    <p style="font-size: 36px;color: #db3f26;">{{ dashboard_data.scheduled_count }}</p>
                                    <h5>SCHEDULED</h5>
                                </div>
                            </el-card>
                        </div>
                    </el-col>
                    <el-col :span="4">
                        <div @click="openPage('To be delivered')" style="cursor: pointer">
                            <el-card shadow="hover">
                                <div class="text-center">
                                    <p style="font-size: 36px;color: #108a3b;">{{ dashboard_data.dispatched_count }}</p>
                                    <h5>DISPATCHED</h5>
                                </div>
                            </el-card>
                        </div>
                    </el-col>
                    <el-col :span="4">
                        <div @click="openPage('Returned')" style="cursor: pointer">
                            <el-card shadow="hover">
                                <div class="text-center">
                                    <p style="font-size: 36px;color: #f4a204;">{{ dashboard_data.returns_count }}</p>
                                    <h5>RETURNED</h5>
                                </div>
                            </el-card>
                        </div>
                    </el-col>
                    <!-- <v-divider vertical></v-divider> -->
                    <el-col :span="8" v-if="!user.is_vendor">
                        <el-card shadow="hover">
                            <div>
                                <strong>QUANTITY ON HAND <span style="float:right;border-left: 1px solid;padding-left: 10px;">{{ dashboard_data.onhand }}</span> </strong>
                            </div>
                        </el-card>
                        <el-card shadow="hover">
                            <div>
                                <strong>Warehouses <span style="float:right;border-left: 1px solid;padding-left: 10px;">{{ dashboard_data.warehouse_count }}</span> </strong>
                            </div>
                        </el-card>
                    </el-col>
                </el-row>
                <v-layout wrap v-if="!user.is_vendor">
                    <v-flex sm12>
                        <v-layout wrap> 
                            <v-flex sm12>
                                <el-row :gutter="12" style="width: 100%">
                                    <el-col :span="11">
                                        <el-card shadow="hover">
                                            <h2>PRODUCT DETAILS</h2>
                                            <v-divider></v-divider>
                                            <ul class="list-group">
                                                <v-layout wrap>

                                                    <v-flex sm6>
                                                        <v-list dense>
                                                            <v-list-item-group color="primary" v-model="selectedItem">
                                                                <v-list-item>

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
                                                                </v-list-item>
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
                                        </el-card>
                                    </el-col>
                                    <el-col :span="13"> 
                                        <div class="block">
                                            <span class="demonstration">months</span>
                                            <el-date-picker
                                            type="months"
                                            v-model="form.month"
                                            placeholder="Pick one or more months">
                                            </el-date-picker>
                                        </div>
                                        <el-card shadow="hover">
                                            <h2>Vendor Performance</h2>
                                            <v-divider></v-divider>
                                            <ul class="list-group">
                                                <v-layout wrap>
                                                    <v-flex sm12>
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
                                                    </v-flex>
                                                </v-layout>
                                            </ul> 
                                        </el-card>
                                    </el-col>
                                </el-row>
                            </v-flex>
                            <!-- <v-btn @click="getChartSummary">refresh</v-btn> -->
                            <v-flex sm12 style="margin-top: 30px;">

                                <el-row :gutter="12" style="width: 100%">
                                    <el-col :span="24">
                                        <el-card shadow="hover">
                                            <h2>SALES ORDER SUMMARY</h2>
                                            <v-divider></v-divider>
                                            <myChart :height="255" :form="form" />
                                            <!-- <line-chart :data="chartsummary" :download="true"></line-chart> -->
                                        </el-card>
                                    </el-col>

                                    <el-col :span="6">
                                        <!-- <el-select v-model="form.country_id" clearable placeholder="Country" style="width: 100%;" @change="filterSales">
                                                <el-option v-for="item in countries" :key="item.id" :label="item.name" :value="item.id">
                                                </el-option>
                                            </el-select> -->
                                        <el-card shadow="hover" style="border: 1px solid #8cdff9; border-left: 3px solid #0caee0; padding: 6px 5px; background-color: #edfafe;" v-for="(week_sale, key) in monday_sale" :key="key">
                                            <h5 class="over-flow bottom-space">Total Sales</h5>
                                            <div class="so-summary-others"><span class="so-circle"></span>
                                                <div class="amt-channel">
                                                    <div class="over-flow" style="font-size: 16px;"><span id="ember961" class="popovercontainer ember-view" data-original-title="" title="">
                                                            <small style="font-size: 13px;">{{ key | moment }}</small>
                                                            <b style="color: red">{{ currency_code }} {{ week_sale }}</b>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </el-card>
                                    </el-col>
                                </el-row>
                            </v-flex>
                        </v-layout>
                    </v-flex>
                </v-layout>

                <el-row :gutter="12" style="width: 100%"  v-if="!user.is_vendor">
                    <el-col :span="6">
                        <div style="cursor: pointer">
                            <el-card shadow="hover">
                                <div class="text-center">
                                    <p style="font-size: 36px;color: #1093de;">{{ dashboard_data.ou_count }}</p>
                                    <h5>Operating Units</h5>
                                </div>
                            </el-card>
                        </div>
                    </el-col>
                    <el-col :span="6">
                        <div style="cursor: pointer">
                            <el-card shadow="hover">
                                <div class="text-center">
                                    <p style="font-size: 36px;color: #db3f26;">{{ dashboard_data.product_count }}</p>
                                    <h5>Products</h5>
                                </div>
                            </el-card>
                        </div>
                    </el-col>
                    <el-col :span="6">
                        <div style="cursor: pointer">
                            <el-card shadow="hover">
                                <div class="text-center">
                                    <p style="font-size: 36px;color: #108a3b;">{{ dashboard_data.onhand }}</p>
                                    <h5>Onhand</h5>
                                </div>
                            </el-card>
                        </div>
                    </el-col>
                    <el-col :span="6">
                        <div style="cursor: pointer">
                            <el-card shadow="hover">
                                <div class="text-center">
                                    <p style="font-size: 36px;color: #f4a204;">{{ dashboard_data.commited }}</p>
                                    <h5>Commited stock</h5>
                                </div>
                            </el-card>
                        </div>
                    </el-col>
                </el-row>

                <el-row :gutter="12" style="width: 100%">
                    <el-col :span="12">
                        <div style="cursor: pointer">
                            <el-card shadow="hover">
                                <myDelivered :height="255" />
                            </el-card>
                        </div>
                    </el-col>
                    <el-col :span="12">
                        <div style="cursor: pointer">
                            <el-card shadow="hover">
                                <myReturn :height="255" />
                            </el-card>
                        </div>
                    </el-col>
                </el-row>
                <!-- <v-btn @click="getBranchCount" text color="primary">Count</v-btn> -->
            </v-flex>
        </v-layout>
    </v-card>
</v-container>
</template>

<script>
import moment from 'moment'
import myChart from "./charts/Sales_chart";
import myDelivered from "./charts/Delivered.js";
import myReturn from "./charts/Returned.js";
import {
    mapState
} from "vuex";
export default {
    props: ["user"],
    components: {
        myChart,
        myDelivered,
        myReturn
    },
    data() {
        return {
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
            active_chart: {
                "active": 154,
                "inactive": 26
            },
            monday_sale: null,
            form: {
                year: new Date().getFullYear(),
                ou_id: "",
                stat_date: new Date(),
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

            purpleLineChart: {
                extraOptions: {
                    maintainAspectRatio: false,
                    legend: {
                        display: false
                    },
                    responsive: true,
                    tooltips: {
                        backgroundColor: '#f5f5f5',
                        titleFontColor: '#333',
                        bodyFontColor: '#666',
                        bodySpacing: 4,
                        xPadding: 12,
                        mode: "nearest",
                        intersect: 0,
                        position: "nearest"
                    },
                    scales: {
                        yAxes: [{
                            barPercentage: 1.6,
                            gridLines: {
                                drawBorder: false,
                                color: 'rgba(29,140,248,0.0)',
                                zeroLineColor: "transparent",
                            },
                            ticks: {
                                suggestedMin: 50,
                                suggestedMax: 110,
                                padding: 20,
                                fontColor: "#ff8a76"
                            }
                        }],

                        xAxes: [{
                            barPercentage: 1.6,
                            gridLines: {
                                drawBorder: false,
                                color: 'rgba(220,53,69,0.1)',
                                zeroLineColor: "transparent",
                            },
                            ticks: {
                                padding: 20,
                                fontColor: "#ff8a76"
                            }
                        }]
                    }

                },
                chartData: {
                    labels: [],
                    datasets: [{
                        label: "Data",
                        fill: true,
                        borderColor: '#d048b6',
                        borderWidth: 2,
                        borderDash: [],
                        borderDashOffset: 0.0,
                        pointBackgroundColor: '#d048b6',
                        pointBorderColor: 'rgba(255,255,255,0)',
                        pointHoverBackgroundColor: '#d048b6',
                        pointBorderWidth: 20,
                        pointHoverRadius: 4,
                        pointHoverBorderWidth: 15,
                        pointRadius: 4,
                        data: [],
                    }]
                }
            }
        }
    },
    methods: {
        openOrders(data) {
            this.$router.push({
                name: "tracking",
                query: {
                    data: data
                }
            });
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
                model: 'dashboard_count' + '?ou=' + this.form.ou + '&year=' + this.form.year + '&daterange=' + this.form.date_range  + '&filter=' + this.form.filter_type,
                update: 'updateDashboard',
            }
            this.$store.dispatch('getItems', payload)
        },
    },
    computed: {
        ...mapState(['dashboard_data', 'ous'])
    },
    mounted() {
        this.getDashboardData()
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
}
</script>

<style scoped>
.justify-center {
    margin-top: -100px !important;
}

.card-stats {
    margin-top: -10px;
    padding: 20px 0;
}


.progress-Ship {
    margin-top: 100px !important;
}
.info-title {
    margin-top: 20px;
}
</style>

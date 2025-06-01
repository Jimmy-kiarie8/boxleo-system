<template>
    <v-card class="analytics" elevation="" style="background: #283046; padding-bottom: 30px;">
    
      <!--   <v-card class="mx-auto" color="#283046" dark>
            <v-card-text>
            
       <v-row :align="align" no-gutters v-if="loaded">
            <v-col>
                <el-date-picker v-model="form.date" type="daterange" align="right" start-placeholder="Start Date" end-placeholder="End Date" default-value=""></el-date-picker>
            </v-col>
    
            <v-col>
    
                <el-select v-model="form.agent_id" filterable clearable placeholder="Select" style="width: 100%;">
                    <el-option v-for="item in users" :key="item.id" :label="item.name" :value="item.id"></el-option>
                </el-select>
            </v-col>
            </v-row>
    
            </v-card-text>
        </v-card>-->
    
        <v-row :align="align" no-gutters v-if="loaded">
            <v-col>
                <v-card class="mx-auto text-center" color="#283046" dark>
                    <v-card-text>
                        <v-btn class="ma-2" outlined fab color="#3366cc">
                            <v-icon color="#3366cc">mdi-tooltip-plus-outline</v-icon>
                        </v-btn>
    
                        <h2 style="color: #d0d2d6;font-weight: 600!important; font-size: 1.714rem">{{ agent_data.orders }}</h2>
                        <small>Total Orders</small>
    
                    </v-card-text>
                    <v-divider></v-divider>
    
                </v-card>
            </v-col>
            <v-col style="margin-left: 3px">
                <v-card class="mx-auto text-center" color="#283046" dark>
                    <v-card-text>
                        <v-btn class="ma-2" outlined fab color="#28c76f">
                            <v-icon color="#28c76f">mdi-check-circle</v-icon>
                        </v-btn>
    
                        <h2 style="color: #d0d2d6;font-weight: 600!important; font-size: 1.714rem">{{ agent_data.delivered }}</h2>
                        <small>{{ currentMonth }} Deliveries</small>
    
                    </v-card-text>
                    <v-divider></v-divider>
                   
                </v-card>
            </v-col>
            <v-col style="margin-left: 3px">
                <v-card class="mx-auto text-center" color="#283046" dark>
                    <v-card-text>
                        <v-btn class="ma-2" outlined fab color="#ff0000bf">
                            <v-icon color="#ff0000bf">mdi-close-box</v-icon>
                        </v-btn>
    
                        <h2 style="color: #d0d2d6;font-weight: 600!important; font-size: 1.714rem">{{ agent_data.returned }}</h2>
                        <small>Returns</small>
    
                    </v-card-text>
                    <v-divider></v-divider>
    
                </v-card>
            </v-col>
            <v-col style="margin-left: 3px">
                <v-card class="mx-auto text-center" color="#283046" dark>
                    <v-card-text>
                        <v-btn class="ma-2" outlined fab color="#1dc5bd">
                            <v-icon color="#1dc5bd">mdi-bike-fast</v-icon>
                        </v-btn>
    
                        <h2 style="color: #d0d2d6;font-weight: 600!important; font-size: 1.714rem">{{ agent_data.in_transit }}</h2>
                        <small>In Transit</small>
    
                    </v-card-text>
                    <v-divider></v-divider>
    
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
    
                                <h2 style="color: #d0d2d6;font-weight: 600!important; font-size: 1.714rem">{{ agent_data.delivered }}</h2>
                                <small>Avg Delivery rate</small>
                                <h2> <b :style="[agent_data.delivery_rate > 40 ? {'color': '#28c76f'} : {'color': '#ff0000bf'}]">{{ agent_data.delivery_rate }}%</b></h2>
                            </v-col>
                            <v-col>
                                <myDelivery :height="255" />
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
    
                                <h2 style="color: #d0d2d6;font-weight: 600!important; font-size: 1.714rem">{{ agent_data.returned }}</h2>
                                <small>Avg Return rate</small>
                                <h2> <b :style="[agent_data.return_rate < 30 ? {'color': '#ff0000bf'} : {'color': '#28c76f'}]">{{ agent_data.return_rate }}%</b></h2>
    
                            </v-col>
                            <v-col>
                                <myReturns :height="255" />
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
    
                            <h2 style="color: #d0d2d6;font-weight: 600!important; font-size: 1.714rem">{{ agent_data.call_rate }}</h2>
                            <h2 style="color: #d0d2d6;font-weight: 600!important; font-size: 1.714rem"></h2>
                        </v-col>
                        <v-col cols="9">
                            <myStatus :height="255" :form="form" />
                        </v-col>
                    </v-row>
                </v-card>
            </v-col>
        </v-row>
    
        <v-row :align="align" no-gutters style="margin-top: 2px" v-if="!user.is_vendor">
    <!--
            <v-col>
                <v-card class="mx-auto text-center" color="#283046" dark>
                    <h2>Call Details</h2>
                    <v-divider></v-divider>
                    <ul class="list-group">
                        <v-layout wrap>
    
                            <v-flex sm6>
                                <v-list dense style="background: #283046;">
                                    <v-list-item-group color="primary" v-model="selectedItem">
                                        <v-list-item>
    
                                            <v-list-item-icon style="margin-top: 18px !important;margin-right: 0 !important">
                                                <v-icon>mdi-quality-low</v-icon>
                                            </v-list-item-icon>
                                            <v-list-item-content style="margin-left: 0px !important">
                                                <v-list-item-title>Call abandonment rate</v-list-item-title>
                                            </v-list-item-content>
    
                                            <v-list-item-action>
                                                <v-btn text color="primary">
                                                    {{ agent_data.abandonment_rate }}
                                                </v-btn>
                                            </v-list-item-action>
                                        </v-list-item>
                                        <v-list-item>
                                            <v-list-item-icon style="margin-top: 18px !important;margin-right: 0 !important">
                                                <v-icon>mdi-basket</v-icon>
                                            </v-list-item-icon>
                                            <v-list-item-content style="margin-left: 0px !important">
                                                <v-list-item-title>First call resolution rate</v-list-item-title>
                                            </v-list-item-content>
    
                                            <v-list-item-action>
                                                <v-btn text color="primary">
                                                    {{ agent_data.resolution_rate }}
                                                </v-btn>
                                            </v-list-item-action>
                                        </v-list-item>
                                        <v-list-item>
                                            <v-list-item-icon style="margin-top: 18px !important;margin-right: 0 !important">
                                                <v-icon>mdi-account-circle</v-icon>
                                            </v-list-item-icon>
                                            <v-list-item-content style="margin-left: 0px !important">
                                                <v-list-item-title>Call volume</v-list-item-title>
                                            </v-list-item-content>
    
                                            <v-list-item-action>
                                                <v-btn text color="primary">
                                                    {{ agent_data.volume }}
                                                </v-btn>
                                            </v-list-item-action>
                                        </v-list-item>
                                    </v-list-item-group>
                                </v-list>
                            </v-flex>
                            <v-flex sm6>
                                <pie-chart :data="agent_data.active_chart" style="height: 200px;"></pie-chart>
                            </v-flex>
                        </v-layout>
                    </ul>
                </v-card>
    
            </v-col>
    -->
            <v-col>
                <v-card class="mx-auto text-center" color="#283046" dark>
                    <h2>Agent Performance</h2>
                    <v-divider></v-divider>
                    <v-simple-table style="text-align: left;">
                        <template v-slot:default>
                            <thead>
                                <tr>
                                    <th class="text-left">
                                        Zone
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
                                    <th class="text-left">
                                        In Transit
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="item in agent_performance.data" :key="item.id">
                                    <td>{{ item.zone }}</td>
                                    <td>{{ item.sales_count }}</td>
                                    <td>{{ item.delivered_count }}
                                        <el-tag style="float: right;" effect="dark" size="mini" type="info">{{ parseFloat(item.delivered_count/item.sales_count * 100).toFixed(2) }}%</el-tag>
                                    </td>
                                    <td>{{ item.returned_count }}
                                        <el-tag style="float: right;" effect="dark" size="mini" type="danger">{{ parseFloat(item.returned_count/item.sales_count * 100).toFixed(2) }}% </el-tag>
                                    </td>
                                    <td>{{ item.in_transit }}
                                        <el-tag style="float: right;" effect="dark" size="mini" type="danger">{{ parseFloat(item.in_transit/item.sales_count * 100).toFixed(2) }}% </el-tag>
                                    </td>
                                </tr>
                            </tbody>
    
    
                                <el-pagination
                                v-if="agent_performance.last_page > 1"
                                @current-change="handlePageChange"
                                :current-page="agent_performance.current_page"
                                :page-size="agent_performance.per_page"
                                :total="agent_performance.total"
                                layout="prev, pager, next"
                                ></el-pagination>
                        </template>
                    </v-simple-table>
                </v-card>
    
            </v-col>
        </v-row>
    </v-card>
</template>
    
<script>
import moment from 'moment'
import myReturns from "./charts/agents/returned.js";
import myStatus from "./charts/agents/status.js";
import myDelivery from "./charts/agents/delivered.js";
import {
    mapState
} from "vuex";
export default {
    props: ["user"],
    components: {
        myStatus,
        myReturns,
        myDelivery
    },
    data() {
        return {
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
                month: '',
                date: ''
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
        handlePageChange(page) {
            var payload = {
                data: [],
                path: '/agents_dashboard_performance',
                page: page,
                update: 'updateAgentPerformance'
            }
            this.$store.dispatch("nextPagePost", payload);
            
        },

        performance() {
            var payload = {
                model: 'agents_dashboard_performance',
                update: 'updateAgentPerformance',
            }
            this.$store.dispatch('getItems', payload)
                .then((res) => {
                });
        },
        fetchData() {
            // Fetch data for the current page from your data source (e.g., API)
            // Update the `tableData` and `totalRows` properties with the fetched data
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
                this.agent_performance = res.data;
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
                model: 'agents_dashboard' + '?ou=' + this.form.ou + '&year=' + this.form.year + '&daterange=' + this.form.date_range + '&filter=' + this.form.filter_type,
                update: 'updateAgentData',
            }
            this.$store.dispatch('getItems', payload).then(() => {
                this.loaded = true;
            })
        },
        
        getUsers() {
            var payload = {
                model: 'agents',
                update: 'updateUsersList',
            }
            this.$store.dispatch("getItems", payload);
        },
    },
    computed: {
        ...mapState(['agent_data', 'ous', 'users', 'agent_performance']),
        currentMonth() {
            const monthNames = [
            'January',
            'February',
            'March',
            'April',
            'May',
            'June',
            'July',
            'August',
            'September',
            'October',
            'November',
            'December'
            ];
            
            const currentDate = new Date();
            const currentMonthIndex = currentDate.getMonth();
            
            return monthNames[currentMonthIndex];
    }
    },
    mounted() {
        this.getDashboardData()
        this.getOus()
        this.getUsers()
        this.performance()

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
.analytics .v-data-table {
    background: #283046;
}

.theme--dark.v-divider {
    border-color: #293147c2 !important;
}
</style>

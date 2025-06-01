<template>
<div>
    <v-card style="padding: 20px; width: 100%;">
        <v-layout justify-center align-center wrap style="margin-top: 0 !important;">
            <!--
            <v-flex sm6>
                <div class="block">
                    <el-date-picker v-model="form.date_range" type="daterange" align="right" unlink-panels range-separator="To" start-placeholder="Start date" end-placeholder="End date" :picker-options="pickerOptions" @change="date_stats('date_range')" format="yyyy/MM/dd" value-format="yyyy-MM-dd" style="width: 100%;">
                    </el-date-picker>
                </div>
            </v-flex>

            <v-flex sm5 offset-sm1>
                <div>
                    <el-select filterable v-model="form.ou" placeholder="Select OU" style="width: 100%;" @change="refresh">
                        <el-option v-for="item in ous" :key="item.id" :label="item.ou" :value="item.id">
                        </el-option>
                    </el-select>
                </div>

            </v-flex> -->
            <v-flex sm12>
                <el-row :gutter="12" style="width: 100%">
                    <el-col :xs="12" :sm="5" :md="5" :lg="5" :xl="5">
                        <div @click="openPage('delivered')" style="cursor: pointer">
                            <el-card shadow="hover">
                                <div class="text-center">
                                    <p style="font-size: 36px;color: #1093de;">{{ dashboard_data.warehouse_count }}</p>
                                    <h5>Warehouses</h5>
                                </div>
                            </el-card>
                        </div>
                    </el-col>
                    <el-col :xs="12" :sm="5" :md="5" :lg="5" :xl="5">
                        <div @click="openPage('To be shipped')" style="cursor: pointer">
                            <el-card shadow="hover">
                                <div class="text-center">
                                    <p style="font-size: 36px;color: #db3f26;">{{ dashboard_data.bins_count }}</p>
                                    <h5>Total Storage Bins</h5>
                                </div>
                            </el-card>
                        </div>
                    </el-col>
                    <el-col :xs="12" :sm="5" :md="5" :lg="5" :xl="5">
                        <div @click="openPage('To be delivered')" style="cursor: pointer">
                            <el-card shadow="hover">
                                <div class="text-center">
                                    <p style="font-size: 36px;color: #108a3b;">{{ dashboard_data.commited_count }}</p>
                                    <h5>Commited</h5>
                                </div>
                            </el-card>
                        </div>
                    </el-col>
                    <el-col :xs="12" :sm="5" :md="5" :lg="5" :xl="5">
                        <div @click="openPage('Returned')" style="cursor: pointer">
                            <el-card shadow="hover">
                                <div class="text-center">
                                    <p style="font-size: 36px;color: #f4a204;">{{ dashboard_data.available_count }}</p>
                                    <h5>Available for sale</h5>
                                </div>
                            </el-card>
                        </div>
                    </el-col>
                    <!-- <v-divider vertical></v-divider> -->
                    <!-- <el-col :xs="24" :sm="4" :md="4" :lg="4" :xl="4">
                        <el-card shadow="hover">
                            <div>
                                <strong>Users <span style="float:right;border-left: 1px solid;padding-left: 10px;">{{ client_count  }} </span> </strong>
                            </div>
                        </el-card>
                        <el-card shadow="hover">
                            <div>
                                <strong>Investigators <span style="float:right;border-left: 1px solid;padding-left: 10px;">{{ investigators_count }}</span> </strong>
                            </div>
                        </el-card>
                    </el-col> -->
                </el-row>
                <v-layout wrap>
                    <v-flex sm12>
                        <v-layout wrap>
                            <v-flex sm12>
                                <el-row :gutter="12" style="width: 100%">
                                    <el-col :xs="24" :sm="11" :md="11" :lg="11" :xl="11">
                                        <el-card shadow="hover">
                                            <h2>Today Analysis</h2>
                                            <v-divider></v-divider>
                                            <ul class="list-group">
                                                <v-layout wrap>

                                                    <v-flex sm6>
                                                        <v-list dense>
                                                            <v-list-item-group color="primary" v-model="selectedItem">
                                                                <v-list-item>
                                                                    <v-list-item-icon style="margin-top: 18px !important;margin-right: 0 !important">
                                                                        <v-icon small>mdi-receipt</v-icon>
                                                                    </v-list-item-icon>
                                                                    <v-list-item-content style="margin-left: 0px !important">
                                                                        <v-list-item-title>Received products</v-list-item-title>
                                                                    </v-list-item-content>

                                                                    <v-list-item-action>
                                                                        <v-btn text color="primary">
                                                                            {{ dashboard_data.received_count }}
                                                                        </v-btn>
                                                                    </v-list-item-action>
                                                                </v-list-item>
                                                                <v-list-item>
                                                                    <v-list-item-icon style="margin-top: 18px !important;margin-right: 0 !important">
                                                                        <v-icon small>mdi-check-all</v-icon>
                                                                    </v-list-item-icon>
                                                                    <v-list-item-content style="margin-left: 0px !important">
                                                                        <v-list-item-title>Dispatched products</v-list-item-title>
                                                                    </v-list-item-content>

                                                                    <v-list-item-action>
                                                                        <v-btn text color="primary">
                                                                            {{ dashboard_data.dispatched_products }}
                                                                        </v-btn>
                                                                    </v-list-item-action>
                                                                </v-list-item>
                                                            </v-list-item-group>
                                                        </v-list>
                                                    </v-flex>
                                                    <v-flex sm6>
                                                        <pie-chart :data="active_chart" style="height: 200px;"></pie-chart>
                                                    </v-flex>
                                                </v-layout>
                                            </ul>
                                        </el-card>
                                    </el-col>
                                    <el-col :xs="24" :sm="13" :md="13" :lg="13" :xl="13">
                                        <el-card shadow="hover">
                                            <h2>Incidences pending approval</h2>
                                            <v-divider></v-divider>
                                            <ul class="list-group">
                                                <v-layout wrap>
                                                    <v-flex sm12>
                                                        <el-table :data="dashboard_data.random_products" style="width: 100%">
                                                            <el-table-column prop="product_name" label="Product" width="180"></el-table-column>
                                                            <el-table-column prop="onhand" label="Onhand" width="180"></el-table-column>
                                                            <el-table-column prop="commited" label="Commited"></el-table-column>
                                                            <el-table-column prop="available_for_sale" label="Available"></el-table-column>
                                                        </el-table>
                                                    </v-flex>
                                                </v-layout>
                                            </ul>
                                        </el-card>
                                    </el-col>
                                </el-row>
                            </v-flex>
                        </v-layout>
                    </v-flex>
                </v-layout>

                <el-row :gutter="12" style="width: 100%">
                    <el-col :xs="12" :sm="6" :md="6" :lg="6" :xl="6">
                        <div style="cursor: pointer">
                            <el-card shadow="hover">
                                <div class="text-center">
                                    <p style="font-size: 36px;color: #1093de;">{{ dashboard_data.onhand_count }}</p>
                                    <h5>Total Stock onhand</h5>
                                </div>
                            </el-card>
                        </div>
                    </el-col>
                    <el-col :xs="12" :sm="6" :md="6" :lg="6" :xl="6">
                        <div style="cursor: pointer">
                            <el-card shadow="hover">
                                <div class="text-center">
                                    <p style="font-size: 36px;color: #db3f26;">{{ dashboard_data.dispatch_count }}</p>
                                    <h5>Dispatched</h5>
                                </div>
                            </el-card>
                        </div>
                    </el-col>
                    <el-col :xs="12" :sm="6" :md="6" :lg="6" :xl="6">
                        <div style="cursor: pointer">
                            <el-card shadow="hover">
                                <div class="text-center">
                                    <p style="font-size: 36px;color: #108a3b;">{{ dashboard_data.product_count }}</p>
                                    <h5>Products</h5>
                                </div>
                            </el-card>
                        </div>
                    </el-col>
                    <el-col :xs="12" :sm="6" :md="6" :lg="6" :xl="6">
                        <div style="cursor: pointer">
                            <el-card shadow="hover">
                                <div class="text-center">
                                    <p style="font-size: 36px;color: #f4a204;">{{ dashboard_data.lowStock }} </p>
                                    <h5>Low stock</h5>
                                </div>
                            </el-card>
                        </div>
                    </el-col>
                </el-row>
<!-- 
                <el-row :gutter="12" style="width: 100%">
                    <el-col :xs="12" :sm="8" :md="8" :lg="8" :xl="8">
                        <div style="cursor: pointer">
                            <h5>Incidences by month</h5>
                            <el-card shadow="hover">
                                <column-chart :data="incidence_graph"></column-chart>
                            </el-card>
                        </div>
                    </el-col>
                    <el-col :xs="12" :sm="8" :md="8" :lg="8" :xl="8">
                        <div style="cursor: pointer">
                            <h5>Open assignment by type</h5>
                            <el-card shadow="hover">
                                <column-chart :data="status_graph"></column-chart>
                            </el-card>
                        </div>
                    </el-col>
                    <el-col :xs="12" :sm="8" :md="8" :lg="8" :xl="8">
                        <div style="cursor: pointer">
                            <h5>Loss by mounth</h5>
                            <el-card shadow="hover">
                                <line-chart :data="loss_graph" />
                            </el-card>
                        </div>
                    </el-col>
                </el-row> -->
            </v-flex>
        </v-layout>
    </v-card>
</div>
</template>

<script>
import moment from 'moment'
import {
    mapState
} from "vuex";
export default {
    props: ["user"],
    components: {

    },
    data() {
        return {
            selectedItem: 0,
            form: {
                year: new Date().getFullYear(),
                ou_id: "",
                stat_date: new Date()
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
            active_chart: {
                "active": 154,
                "inactive": 26
            },

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
        
        
        getData() {
            var payload = {
                model: 'dashboard_data',
                update: 'UpdateDashboard',
                search: 'available?ou=' + this.form.ou + '&year=' + this.form.year,
            }
            this.$store.dispatch('getItems', payload)

        },
        getCountries() {
            var payload = {
                model: 'ous',
                update: 'updateOu',
            }
            this.$store.dispatch('getItems', payload)
                .then((res) => {
                    // setTimeout(() => this.filteredData(), 2000);
                });
        },
        date_stats(data) {
            this.form.year = data
            this.getData()
        },
    },
    computed: {
        ...mapState([
            'dashboard_data'
        ])
    },
    mounted() {
        this.getData()

    },
    created() {
        this.form.ou_id = this.user.ou_id
        eventBus.$on('Refreshdashboard', data => {
            this.getData();
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

/* .statistics {
    background: #f0f0f0 !important;
} */

.progress-Ship {
    margin-top: 100px !important;
}

/*
.v-icon {
    font-size: 64px !important;
}

.v-icon {
    box-shadow: 0 9px 30px -6px rgba(255, 54, 54, .5);
    padding: 5px;
    border-radius: 50%;
} */

.info-title {
    margin-top: 20px;
}
</style>

<template>
<v-content style="margin-top: 50px !important;">
    <v-container fluid fill-height>
        <v-layout justify-center align-center wrap>
            <v-flex sm6>
                <el-select v-model="form.ou_id" clearable placeholder="Ou" style="width: 100%;" @change="ou_filter">
                    <el-option v-for="item in ous" :key="item.id" :label="item.ou" :value="item.id">
                    </el-option>
                </el-select>
            </v-flex>
            <v-flex sm6>
                <el-select v-model="form.year_f" clearable placeholder="Ou" style="width: 100%;" @change="ou_filter">
                    <el-option v-for="item in options" :key="item.value" :label="item.label" :value="item.value">
                    </el-option>
                </el-select>
            </v-flex>
            <v-flex sm12>
                <el-row :gutter="12" style="width: 100%">
                    <el-col :span="4">
                        <div @click="openPage('delivered')" style="cursor: pointer">
                            <el-card shadow="hover">
                                <div class="text-center">
                                    <p style="font-size: 36px;color: #1093de;">{{ delivered_count }}</p>
                                    <h5>Delivered</h5>
                                </div>
                            </el-card>
                        </div>
                    </el-col>
                    <el-col :span="4">
                        <div @click="openPage('To be shipped')" style="cursor: pointer">
                            <el-card shadow="hover">
                                <div class="text-center">
                                    <p style="font-size: 36px;color: #db3f26;">{{ scheduled_count }}</p>
                                    <h5>SCHEDULED</h5>
                                </div>
                            </el-card>
                        </div>
                    </el-col>
                    <el-col :span="4">
                        <div @click="openPage('To be delivered')" style="cursor: pointer">
                            <el-card shadow="hover">
                                <div class="text-center">
                                    <p style="font-size: 36px;color: #388a10;">{{ dispatched_count }}</p>
                                    <h5>DISPATCHED</h5>
                                </div>
                            </el-card>
                        </div>
                    </el-col>
                    <el-col :span="4">
                        <router-link to="/returns">
                            <div style="cursor: pointer">
                                <el-card shadow="hover">
                                    <div class="text-center">
                                        <p style="font-size: 36px;color: #f4a204;">{{ returns_count }}</p>
                                        <h5>RETURNS</h5>
                                    </div>
                                </el-card>
                            </div>
                        </router-link>
                    </el-col>
                    <v-divider vertical></v-divider>
                    <el-col :span="6" v-if="!user.is_client">
                        <el-card shadow="hover">
                            <div>
                                <h6>QUANTITY ON HAND <span style="float:right;border-left: 1px solid;padding-left: 10px;">{{ onhand }}</span> </h6>
                            </div>
                        </el-card>
                        <el-card shadow="hover">
                            <div>
                                <h6>QUANTITY COMMITED <span style="float:right;border-left: 1px solid;padding-left: 10px;">{{ commited }}</span> </h6>
                            </div>
                        </el-card>
                    </el-col>
                </el-row>
                <v-layout wrap>
                    <v-card style="padding: 20px; width: 100%;">
                        <v-flex sm12>
                            <v-layout wrap>
                                <v-flex sm12>
                                    <el-row :gutter="12" style="width: 100%">
                                        <el-col :span="14">
                                            <el-card shadow="hover">
                                                <h2>PRODUCT DETAILS</h2>
                                                <v-divider></v-divider>
                                                <ul class="list-group">
                                                    <v-layout wrap>
                                                        <v-flex sm7>
                                                            <div>
                                                                <router-link to="/lowstoke" class="list-group-item row " style="color: #db3f26">
                                                                    <label class="col-md-8 col-lg-8"><b>Low Stock Items</b></label>
                                                                    <small style="float: right;">{{ low_stoke }}</small>
                                                                </router-link>
                                                                <router-link to="/products" class="list-group-item row">
                                                                    <label class="col-md-5 col-lg-5"><b>All Items </b></label>
                                                                    <small style="float: right;">{{ product_count }}</small>
                                                                </router-link>
                                                            </div>
                                                        </v-flex>
                                                        <v-flex sm5>
                                                            <pie-chart :data="active_chart" style="height: 200px;"></pie-chart>
                                                        </v-flex>
                                                    </v-layout>
                                                </ul>
                                            </el-card>
                                        </el-col>
                                        <el-col :span="10">
                                            <el-card shadow="hover">
                                                <h2>TOP SELLING ITEMS</h2>
                                                <v-divider></v-divider>
                                                <ul class="list-group">
                                                    <v-layout wrap>
                                                        <v-flex sm12>
                                                            <div>
                                                                <p>No items were invoiced in this time frame</p>
                                                            </div>
                                                        </v-flex>
                                                    </v-layout>
                                                </ul>
                                            </el-card>
                                        </el-col>
                                    </el-row>
                                </v-flex>
                                <!-- <v-btn @click="getChartSummary">refresh</v-btn> -->
                                <v-flex sm12 style="margin-top: 30px;">
                                        <v-layout row wrap>
                                            <v-flex sm6>
                                                <v-card>
                                                    <h2>SALES ORDER SUMMARY</h2>
                                                    <v-divider></v-divider>
                                                    <myChart style="height: 100%" :chart-data="purpleLineChart.chartData" :gradient-color="purpleLineChart.gradientColors" :gradient-stops="purpleLineChart.gradientStops" :extra-options="purpleLineChart.extraOptions" />
                                                    <!-- <line-chart :data="chartsummary" :download="true"></line-chart> -->
                                                </v-card>
                                            </v-flex>
                                        </v-layout>

                                        <el-col :span="6">
                                            <!-- <el-select v-model="form.ou_id" clearable placeholder="Ou" style="width: 100%;" @change="filterSales">
                                                <el-option v-for="item in ous" :key="item.id" :label="item.name" :value="item.id">
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
                    </v-card>
                </v-layout>
                <!-- <v-btn @click="getBranchCount" text color="primary">Count</v-btn> -->
            </v-flex>
        </v-layout>
    </v-container>
</v-content>
</template>

<script>
import moment from 'moment'
import myChart from "./charts/Sales";
import {
    mapState
} from "vuex";
export default {
    props: ["user"],
    components: {
        myChart,
    },
    data() {
        return {
            total_sale: null,
            group_count: null,
            tobe_packed: null,
            tobe_shipped: null,
            tobe_invoiced: null,
            delivered: null,
            returned: null,
            tobe_delivered: null,
            monday_sale: null,
            form: {
                year_f: new Date().getFullYear(),
                ou_id: "",
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
        getLowstock() {
            var payload = {
                model: 'lowStock',
                update: 'updateLowstock',
            }
            this.$store.dispatch('getItems', payload)
        },
        getGroup_count() {
            axios.get('group_count').then((response) => {
                this.group_count = response.data
            }).catch((error) => {
                if (error.response.status === 500) {
                    eventBus.$emit('errorEvent', error.response.statusText)
                    return
                } else if (error.response.status === 401 || error.response.status === 409) {
                    eventBus.$emit('reloadRequest', error.response.statusText)
                }
                this.errors = error.response.data.errors
            })
        },
        getScheduled() {
            var payload = {
                model: 'status_count',
                update: 'updateScheduled',
                search: 'Scheduled',
            }
            this.$store.dispatch('searchItems', payload)
        },
        getDispatched() {
            var payload = {
                model: 'status_count',
                update: 'updateDispatched',
                search: 'Dispatched',
            }
            this.$store.dispatch('searchItems', payload)

        },
        getDelivered() {
            var payload = {
                model: 'status_count',
                update: 'updateDelivered',
                search: 'Delivered',
            }
            this.$store.dispatch('searchItems', payload)

        },
        getReturns() {
            var payload = {
                model: 'status_count',
                update: 'updatereturns',
                search: 'Returns',
            }
            this.$store.dispatch('searchItems', payload)

        },

        getPending() {
            var payload = {
                model: 'status_count',
                update: 'updatePending',
                search: 'Pending',
            }
            this.$store.dispatch('searchItems', payload)

        },
        getCommited() {
            var payload = {
                model: 'commited',
                update: 'updateCommited',
            }
            this.$store.dispatch('getItems', payload)
        },
        getOnhand() {
            var payload = {
                model: 'onhand',
                update: 'updateOnhand',
            }
            this.$store.dispatch('getItems', payload)
        },

        getProductCount() {
            var payload = {
                model: 'product_count',
                update: 'UpdateProductsCountList',
            }
            this.$store.dispatch('getItems', payload)

        },
        total_week() {
            // alert('total_week')
            axios.get('total_week').then((response) => {
                this.monday_sale = response.data
            }).catch((error) => {
                if (error.response.status === 500) {
                    eventBus.$emit('errorEvent', error.response.statusText)
                    return
                } else if (error.response.status === 401 || error.response.status === 409) {
                    eventBus.$emit('reloadRequest', error.response.statusText)
                }
                this.errors = error.response.data.errors
            })
        },
        openPage(data) {
            // this.$router.push({
            //     name: "StockClickable",
            //     params: {
            //         data: data
            //     }
            // });
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
        filterSales() {
            // this.currency_code =
            // alert(this.form.ou_id)
            if (this.form.ou_id == null || this.form.ou_id == '') {
                this.form.ou_id = 0
            }
            axios.get(`filterSales/${this.form.ou_id}`).then((response) => {
                this.monday_sale = response.data
                this.filteredData()
            }).catch((error) => {
                if (error.response.status === 500) {
                    eventBus.$emit('errorEvent', error.response.statusText)
                    return
                } else if (error.response.status === 401 || error.response.status === 409) {
                    eventBus.$emit('reloadRequest', error.response.statusText)
                }
                this.errors = error.response.data.errors
            })
        },

        getChartSummary() {
            if (this.form.ou_id == null || this.form.ou_id == '') {
                this.form.ou_id = 0
            }
            if (this.form.year_f == null || this.form.year_f == '') {
                this.form.year_f = new Date().getFullYear()
            }
            var payload = {
                model: 'sales_chart',
                update: 'updateSaleChartList'
            }

            this.$store.dispatch('getItems', payload).then((res) => {
                console.log(this.purpleLineChart.chartData.datasets[0]);
                console.log(this.sales_chart.data);

                this.purpleLineChart.chartData.labels = this.sales_chart.data.lables
                this.purpleLineChart.chartData.datasets[0].data = this.sales_chart.data.rows
            })
        },
        refresh_dashboard() {
            this.getLowstock()
            this.delivered_pkg()
            this.getProductCount()
            this.filterSales()
            // this.$store.dispatch('getChartSummary')
            this.getChartSummary()
            // this.getPending()
        },
        filteredData() {
            if (this.form.ou_id == null || this.form.ou_id == '') {
                this.form.ou_id = this.user.ou_id
            }
            this.ous.forEach(element => {

                if (this.form.ou_id == element.id) {
                    this.currency_code = element.currency_code
                }
            });
        },
        ou_filter() {
            eventBus.$emit('progressEvent')
            this.getChartSummary()
            this.delivered_pkg()
            this.getGroup_count()
            this.getScheduled()
            this.getDispatched()
            this.getDelivered()
            this.getReturns()
            this.getPending()
            this.getProductCount()
            this.filterSales()
        },

        // getSales(data) {
        //     console.log(data);

        //     var payload = {
        //         model: '/sales_chart',
        //         update: 'updateSaleChartList',
        //         data: data,
        //     }

        //     this.$store.dispatch("filterItems", payload).then((res) => {
        //         this.setGraph()
        //     });
        // },
    },
    computed: {
        ...mapState(['product_count', 'scheduled_count', 'dispatched_count', 'delivered_count', 'returns_count', 'low_stoke', 'pending', 'onhand', 'chartsummary', 'active_chart', 'ous', 'commited', 'sales_chart'])
    },
    mounted() {
        this.getGroup_count()
        this.getScheduled()
        this.getReturns()
        this.getDelivered()
        this.getProductCount()
        this.getDispatched()
        this.getLowstock()
        this.getOnhand()
        this.getCommited()
        // Charts
        this.getChartSummary()
        this.getCountries()
        this.refresh_dashboard()

    },
    created() {
        this.form.ou_id = this.user.ou_id
        this.timer = window.setInterval(() => {
            this.refresh_dashboard();
        }, 120000);
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

.v-icon {
    font-size: 64px !important;
}

.v-icon {
    box-shadow: 0 9px 30px -6px rgba(255, 54, 54, .5);
    padding: 5px;
    border-radius: 50%;
}

.info-title {
    margin-top: 20px;
}

a {
    text-decoration: none;
}
</style>

<template>
<div style="margin-top: 20px !important;" theme="dark">
    <v-container fluid fill-height>
        <v-card style="padding: 0 20px">

            <v-layout justify-center align-center wrap>
                <v-flex sm12>

                    <v-row>
                        <v-col cols="12" sm="3">
                            <v-hover v-slot:default="{ hover }" open-delay="200">
                                <v-card style="background-color: #4e4eac;border-radius: 10px;" :elevation="hover ? 16 : 2" :class="{ 'on-hover': hover }" class="mx-auto" max-width="350">
                                    <v-card-text class="font-weight-medium mt-12 card-item subtitle-1">
                                        <v-row>
                                            <v-col sm1>
                                                <v-icon color="white">mdi-car</v-icon>
                                            </v-col>
                                            <v-col sm11>
                                                <div class="text-center" :style="[ ($vuetify.theme.dark) ? {'color': 'rgb(255, 255, 255)'} : '#333']">
                                                    <h5>Delivered</h5>
                                                    <p style="font-size: 26px;">{{ delivered_count }}</p>
                                                </div>
                                            </v-col>
                                        </v-row>

                                    </v-card-text>
                                </v-card>
                            </v-hover>
                        </v-col>

                        <v-col cols="12" sm="3">
                            <v-hover v-slot:default="{ hover }" open-delay="200">
                                <v-card style="background-color: #9799d6;border-radius: 10px;" :elevation="hover ? 16 : 2" :class="{ 'on-hover': hover }" class="mx-auto" max-width="350">
                                    <v-card-text class="font-weight-medium mt-12 card-item subtitle-1">
                                        <v-row>
                                            <v-col sm1>
                                                <v-icon color="white">mdi-car</v-icon>
                                            </v-col>
                                            <v-col sm11>
                                                <div class="text-center" :style="[ ($vuetify.theme.dark) ? {'color': 'rgb(255, 255, 255)'} : '#333']">
                                                    <h5>Dispatched</h5>
                                                    <p style="font-size: 26px;">{{ scheduled_count }}</p>
                                                </div>
                                            </v-col>
                                        </v-row>

                                    </v-card-text>
                                </v-card>
                            </v-hover>
                        </v-col>
                        <v-col cols="12" sm="3">
                            <v-hover v-slot:default="{ hover }" open-delay="200">
                                <v-card style="background-color: #eb7c8a;border-radius: 10px;" :elevation="hover ? 16 : 2" :class="{ 'on-hover': hover }" class="mx-auto" max-width="350">
                                    <v-card-text class="font-weight-medium mt-12 card-item subtitle-1">
                                        <v-row>
                                            <v-col sm1>
                                                <v-icon color="white">mdi-car</v-icon>
                                            </v-col>
                                            <v-col sm11>
                                                <div class="text-center" :style="[ ($vuetify.theme.dark) ? {'color': 'rgb(255, 255, 255)'} : '#333']">
                                                    <h5>At Origin</h5>
                                                    <p style="font-size: 26px;">{{ dispatched_count }}</p>
                                                </div>
                                            </v-col>
                                        </v-row>

                                    </v-card-text>
                                </v-card>
                            </v-hover>
                        </v-col>

                        <v-col cols="12" sm="3">
                            <v-hover v-slot:default="{ hover }" open-delay="200">
                                <v-card style="background-color: #58c4e3;border-radius: 10px;" :elevation="hover ? 16 : 2" :class="{ 'on-hover': hover }" class="mx-auto" max-width="350">
                                    <v-card-text class="font-weight-medium mt-12 card-item subtitle-1">
                                        <v-row>
                                            <v-col sm1>
                                                <v-icon color="white">mdi-car</v-icon>
                                            </v-col>
                                            <v-col sm11>
                                                <div class="text-center" :style="[ ($vuetify.theme.dark) ? {'color': 'rgb(255, 255, 255)'} : '#333']">
                                                    <h5>Returns</h5>
                                                    <p style="font-size: 26px;">{{ returns_count }}</p>
                                                </div>
                                            </v-col>
                                        </v-row>

                                    </v-card-text>
                                </v-card>
                            </v-hover>
                        </v-col>
                    </v-row>
                    <v-layout wrap>
                        <v-card style="padding: 20px; width: 100%;">
                            <v-flex sm12>
                                <v-layout wrap>
                                    <v-flex sm12>
                                        <v-row>
                                            <v-col sm="6">
                                                <v-card>
                                                    <h2>PRODUCT DETAILS</h2>
                                                    <v-divider></v-divider>
                                                    <ul class="list-group">
                                                        <v-row wrap>
                                                            <v-col sm="7">
                                                                <router-link to="/lowstoke" style="color: #db3f26">
                                                                    <label class="col-md-8 col-lg-8"><b>Low Stock Items</b></label>
                                                                    <small style="float: right;">{{ low_stoke }}</small>
                                                                </router-link>
                                                                <router-link to="/products">
                                                                    <label class="col-md-5 col-lg-5"><b>All Items </b></label>
                                                                    <small style="float: right;">{{ product_count }}</small>
                                                                </router-link>
                                                            </v-col>
                                                            <v-col sm="5">
                                                                <!-- <pie-chart :data="active_chart" style="height: 200px;"></pie-chart> -->
                                                            </v-col>
                                                        </v-row>
                                                    </ul>
                                                </v-card>
                                            </v-col>
                                            <v-col sm="6">
                                                <v-card>
                                                    <h2>Client performance</h2>
                                                    <v-divider></v-divider>
                                                    <table class="table table-hover">
                                                        <thead>
                                                            <tr>
                                                                <th scope="col">Name</th>
                                                                <th scope="col">Orders</th>
                                                                <th scope="col">Delivered</th>
                                                                <th scope="col">Returned</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr v-for="(item, index) in vendor_performance" :key="index">
                                                                <td>{{ item.name }}</td>
                                                                <td>{{ item.sales_count }}</td>
                                                                <td>{{ item.delivered_count }}</td>
                                                                <td>{{ item.returned_count }}</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </v-card>
                                            </v-col>
                                        </v-row>
                                    </v-flex>
                                    <!-- <v-btn @click="getChartSummary">refresh</v-btn> -->
                                    <v-flex sm12 style="margin-top: 30px;">
                                        <v-layout row wrap>
                                            <v-flex sm12>
                                                <v-card>
                                                    <h2>SALES ORDER SUMMARY</h2>
                                                    <v-divider></v-divider>
                                                    <myChart :height="255" :width="1200" />
                                                    <!-- <my-shipment :height="255" :width="1200"></my-shipment> -->

                                                </v-card>
                                            </v-flex>
                                        </v-layout>

                                        <el-col :span="6">
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
                                    </v-flex>

                                    <v-flex sm12 style="margin-top: 30px;">
                                        <v-layout row wrap>
                                            <v-flex sm6>
                                                <v-card>
                                                    <v-divider></v-divider>
                                                    <myDelivered />
                                                </v-card>
                                            </v-flex>

                                            <v-flex sm6>
                                                <v-card>
                                                    <v-divider></v-divider>
                                                    <myReturn />
                                                </v-card>
                                            </v-flex>
                                        </v-layout>
                                    </v-flex>
                                </v-layout>
                            </v-flex>
                        </v-card>
                    </v-layout>
                    <!-- <v-btn @click="getBranchCount" text color="primary">Count</v-btn> -->
                </v-flex>
            </v-layout>
        </v-card>
    </v-container>

</div>
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
        getCommited() {
            var payload = {
                model: 'vendor_performance',
                update: 'updateVendorPerformance',
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

        refresh_dashboard() {
            this.getLowstock()
            // this.delivered_pkg()
            this.getProductCount()
            this.filterSales()
            // this.$store.dispatch('getChartSummary')
            // this.getChartSummary()
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
            // this.getChartSummary()
            // this.delivered_pkg()
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

        refresh() {
            // this.getGroup_count()
            this.getScheduled()
            this.getReturns()
            this.getDelivered()
            this.getProductCount()
            this.getDispatched()
            this.getLowstock()
            this.getOnhand()
            this.getCommited()
            // Charts
            // this.getChartSummary()
            this.getCountries()
            this.refresh_dashboard()
        }
    },
    computed: {
        ...mapState(['product_count', 'scheduled_count', 'dispatched_count', 'delivered_count', 'returns_count', 'low_stoke', 'pending', 'onhand', 'chartsummary', 'active_chart', 'ous', 'commited', 'sales_chart', 'vendor_performance'])
    },
    mounted() {
        this.refresh()

    },
    created() {
        this.form.ou_id = this.user.ou_id
        // this.timer = window.setInterval(() => {
        //     this.refresh_dashboard();
        // }, 120000);

        eventBus.$on('Refreshdashboard', data => {
            this.refresh();
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

.v-icon {
    font-size: 64px !important;
}

.v-icon {
    box-shadow: 0 9px 30px -6px rgb(249 249 249 / 50%);
    padding: 5px;
    border-radius: 50%;
}

.info-title {
    margin-top: 20px;
}

a {
    text-decoration: none;
}

.v-application .card-item {
    color: #f0f0f0;
}
</style>

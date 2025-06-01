<template>
<div style="margin-top: 20px !important;text-align: center" theme="dark">
    <v-card>

        <v-layout justify-center align-center wrap>

            <v-flex sm12>
                <v-layout row wrap style="margin-bottom: 1px;padding: 0 10px;">
                    <v-flex sm5>
                        <el-date-picker v-model="form.stat_date" type="date" placeholder="Pick a day" format="yyyy/MM/dd" value-format="yyyy-MM-dd" style="margin-top: 80px;" @change="date_stats">
                        </el-date-picker>
                    </v-flex>
                    <v-flex sm5 offset-sm1> 
                        <div>
                            <el-select filterable v-model="form.ou" placeholder="Select OU" style="width: 100%; margin-top: 80px;" @change="refresh">
                                <el-option v-for="item in ous" :key="item.id" :label="item.ou" :value="item.id">
                                </el-option>
                            </el-select>
                        </div>

                    </v-flex>
                    <!-- <v-flex sm4>
                        <el-select allow-create filterable v-model="form.year" placeholder="Select Year" style="width: 100%; margin-top: 80px;" @change="refresh">
                            <el-option v-for="item in options" :key="item.value" :label="item.label" :value="item.value">
                            </el-option>
                        </el-select>
                    </v-flex> -->

                </v-layout>
                <v-layout wrap>
                    <v-card elevation="5" width="100%" style="background: linear-gradient(90deg,#0c2646,#204065 60%,#2a5788);padding-bottom: 20px">
                        <v-card-text>
                            <myChart :height="255" :form="form" />
                        </v-card-text>
                    </v-card>
                </v-layout>

                <v-card elevation="5" style="padding: 2px 0;width: 97%; margin: auto;margin-top: -30px; box-shadow: rgb(18 59 175 / 55%) 0px 0px 30px 0px !important">
                    <v-card-text>
                        <v-layout row wrap>
                            <v-flex sm3 style="border-right: 1px solid rgba(0,0,0,.12); cursor: pointer" @click="openOrders('Sheduled')">
                                <div class="icon icon-primary">
                                    <v-icon color="green">mdi-clock-outline</v-icon>
                                </div>
                                <h3 class="info-title"><span><b>{{ scheduled_count }} </b></span></h3>
                                <h6 class="stats-title"><strong>To be Shipped</strong></h6>
                            </v-flex>
                            <v-flex sm3 style="border-right: 1px solid rgba(0,0,0,.12); cursor: pointer" @click="openOrders('Delivered')">
                                <div class="icon icon-success">
                                    <v-icon color="purple">mdi-check-circle-outline</v-icon>
                                </div>
                                <h3 class="info-title"><span><b>{{ delivered_count }} </b></span></h3>
                                <h6 class="stats-title"><strong>Delivered</strong></h6>
                            </v-flex>
                            <v-flex sm3 style="border-right: 1px solid rgba(0,0,0,.12); cursor: pointer" @click="openOrders('Returned')">
                                <div class="icon icon-info">
                                    <v-icon color="indigo">mdi-clipboard-arrow-left</v-icon>
                                </div>
                                <h3 class="info-title"><span><b>{{ returns_count }}</b></span></h3>
                                <h6 class="stats-title"><strong>Returned</strong></h6>
                            </v-flex>
                            <v-flex sm3 style="cursor: pointer" @click="openOrders('Pedding')">
                                <div class="icon icon-danger">
                                    <v-icon color="red cursor: pointer">mdi-timelapse</v-icon>
                                </div>
                                <h3 class="info-title"><span><b>{{ pending }}</b></span></h3>
                                <h6 class="stats-title"><strong> Pending</strong></h6>
                            </v-flex>
                        </v-layout>
                    </v-card-text>
                </v-card>

                <v-divider></v-divider>
                <div class="row">
                    <div class="col-lg-6 col-md-4 col-sm-12">
                        <v-card elevation="5" width="100%">
                            <v-card-text>
                                <myDelivered :height="255" />
                            </v-card-text>
                        </v-card>
                    </div>
                    <div class="col-lg-6 col-md-4 col-sm-12">

                        <v-card elevation="5" width="100%">
                            <v-card-text>
                                <div class="chart-area">
                                    <!-- <my-cancled :height="255"></my-cancled> -->
                                    <myReturn :height="255" />
                                </div>
                            </v-card-text>
                        </v-card>
                    </div>
                </div>

                <v-card elevation="5" style="padding: 2px 0;width: 97%; margin: auto;margin-top: -20px; box-shadow: rgb(18 59 175 / 55%) 0px 0px 30px 0px !important">
                    <v-card-text>
                        <v-layout row wrap>
                            <v-flex sm3 style="border-right: 1px solid rgba(0,0,0,.12); cursor: pointer" @click="openOrders('Sheduled')">
                                <router-link :to="(user.can['Product view']) ? '/products' : '/'" style="text-decoration: none;color: #686666;">
                                    <div class="icon icon-danger">
                                        <v-icon color="danger">mdi-basket</v-icon>
                                    </div>
                                    <h3 class="info-title"><span><b>{{ product_count }}</b></span></h3>
                                    <h6 class="stats-title"><strong> Products</strong></h6>
                                </router-link>
                            </v-flex>
                            <v-flex sm3 style="border-right: 1px solid rgba(0,0,0,.12);">
                                <router-link to="/lowstoke" style="text-decoration: none;color: #686666;">
                                    <div class="icon icon-primary">
                                        <v-icon color="pink">mdi-priority-low</v-icon>
                                    </div>
                                    <h3 class="info-title"><span><b>{{ low_stoke }}</b></span></h3>
                                    <h6 class="stats-title"><strong>Low Stock</strong></h6>
                                </router-link>
                            </v-flex>
                            <v-flex sm3 style="border-right: 1px solid rgba(0,0,0,.12);">
                                <div class="">
                                    <div class="icon icon-success">
                                        <v-icon color="orange">mdi-hand</v-icon>
                                    </div>
                                    <h3 class="info-title"><span><b>{{ onhand }}</b></span></h3>
                                    <h6 class="stats-title"><strong>On hand</strong></h6>
                                </div>
                            </v-flex>
                            <v-flex sm3>
                                <div class="">
                                    <div class="icon icon-info">
                                        <v-icon color="info">mdi-source-commit-end</v-icon>
                                    </div>
                                    <h3 class="info-title"><span><b>{{ commited }}</b></span></h3>
                                    <h6 class="stats-title"><strong>Commited</strong></h6>
                                </div>
                            </v-flex>
                        </v-layout>
                    </v-card-text>
                </v-card> 

                <v-divider></v-divider>

                <v-flex sm12 style="margin-top: 40px;" v-if="!user.is_vendor">
                    <v-card elevation="5" tile>
                        <v-row>
                            <v-col sm="6" style="border-right: 1px solid rgba(0,0,0,.12);">
                                <v-list dense>
                                    <v-subheader>PRODUCT DETAILS</v-subheader>
                                    <router-link to="/products" style="text-decoration: none">

                                        <v-list-item>
                                            <v-list-item-icon>
                                                <v-icon small>mdi-basket</v-icon>
                                            </v-list-item-icon>

                                            <v-list-item-content>
                                                <v-list-item-title>Products</v-list-item-title>
                                                <v-list-item-subtitle>{{ product_count }}</v-list-item-subtitle>
                                            </v-list-item-content>

                                        </v-list-item>
                                    </router-link>
                                    <v-divider class=""></v-divider>
                                    <router-link to="/lowstoke" style="text-decoration: none">
                                        <v-list-item>
                                            <v-list-item-icon>
                                                <v-icon>mdi-priority-low</v-icon>
                                            </v-list-item-icon>

                                            <v-list-item-content>
                                                <v-list-item-title>Low Stock</v-list-item-title>
                                                <v-list-item-subtitle>{{ low_stoke }}</v-list-item-subtitle>
                                            </v-list-item-content>

                                        </v-list-item>
                                    </router-link>
                                </v-list>
                            </v-col>

                            <v-col sm="6">
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
                            </v-col>
                        </v-row>
                    </v-card>
                </v-flex>

                <v-card elevation="5" style="padding: 2px 0;width: 97%; margin: auto;margin-top: -20px; box-shadow: rgb(18 59 175 / 55%) 0px 0px 30px 0px !important" v-if="!user.is_vendor">
                    <v-card-text>
                        <v-layout row wrap>
                            <v-flex sm3 style="border-right: 1px solid rgba(0,0,0,.12); cursor: pointer">
                                <router-link :to="(user.can['User view']) ? '/users' : '/'" style="text-decoration: none;color: #686666;">
                                    <div class="icon icon-danger">
                                        <v-icon color="danger">mdi-account-circle</v-icon>
                                    </div>
                                    <h3 class="info-title"><span><b>{{ user_count }}</b></span></h3>
                                    <h6 class="stats-title"><strong> Users</strong></h6>
                                </router-link>
                            </v-flex>
                            <v-flex sm3 style="border-right: 1px solid rgba(0,0,0,.12); cursor: pointer">
                                <router-link :to="(user.can['Client view']) ? '/clients' : '/'" style="text-decoration: none;color: #686666;">

                                    <div class="icon icon-primary">
                                        <v-icon color="pink">mdi-account-multiple</v-icon>
                                    </div>
                                    <h3 class="info-title"><span><b>{{ sellers_count }}</b></span></h3>
                                    <h6 class="stats-title"><strong>Clients</strong></h6>
                                </router-link>
                            </v-flex>
                            <v-flex sm3 style="border-right: 1px solid rgba(0,0,0,.12); cursor: pointer">
                                <router-link :to="(user.can['OU view']) ? '/clients' : '/'" style="text-decoration: none;color: #686666;">
                                    <div class="icon icon-success">
                                        <v-icon color="orange">mdi-map</v-icon>
                                    </div>
                                    <h3 class="info-title"><span><b>{{ countries_count }}</b></span></h3>
                                    <h6 class="stats-title"><strong>OU</strong></h6>
                                </router-link>
                            </v-flex>
                            <v-flex sm3 @click="openOrders('Pedding')" style="cursor: pointer">
                                <router-link :to="(user.can['Zone view']) ? '/zones' : '/'" style="text-decoration: none;color: #686666;">
                                    <div class="icon icon-info">
                                        <v-icon color="info">mdi-city</v-icon>
                                    </div>
                                    <h3 class="info-title"><span><b>{{ branches_count }}</b></span></h3>
                                    <h6 class="stats-title"><strong>Zones</strong></h6>
                                </router-link>
                            </v-flex>
                        </v-layout>
                    </v-card-text>
                </v-card>

            </v-flex>
        </v-layout>
    </v-card>
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
        openOrders(data) {
            this.$router.push({
                name: "tracking",
                query: {
                    data: data
                }
            });
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
            // var search = 'Scheduled?ou=' + this.form.ou + '&year=' + this.form.year
            // if (this.form.ou) {
            //     var search = '?ou=' + this.form.ou
            // }if (this.form.year) {
            //     var search = search + '&year=' + this.form.year
            // }
            var payload = {
                model: 'status_count',
                update: 'updateScheduled',
                search: 'Scheduled?ou=' + this.form.ou + '&year=' + this.form.year,
            }
            this.$store.dispatch('searchItems', payload)
        },
        getDispatched() {
            var payload = {
                model: 'status_count',
                update: 'updateDispatched',
                search: 'Dispatched?ou=' + this.form.ou + '&year=' + this.form.year,
            }
            this.$store.dispatch('searchItems', payload)

        },
        getDelivered() {
            var payload = {
                model: 'status_count',
                update: 'updateDelivered',
                search: 'Delivered?ou=' + this.form.ou + '&year=' + this.form.year,
            }
            this.$store.dispatch('searchItems', payload)

        },
        getReturns() {
            var payload = {
                model: 'status_count',
                update: 'updatereturns',
                search: 'Returned?ou=' + this.form.ou + '&year=' + this.form.year,
            }
            this.$store.dispatch('searchItems', payload)

        },

        getPending() {
            var payload = {
                model: 'pending?ou=' + this.form.ou + '&year=' + this.form.year,
                update: 'updatePending',
            }
            this.$store.dispatch('getItems', payload)

        },
        getCommited() {
            var payload = {
                model: 'commited',
                update: 'updateCommited',
            }
            this.$store.dispatch('getItems', payload)
        },
        vedor_data() {
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

        getVendorount() {
            var payload = {
                model: 'sellers_count',
                update: 'updateSellerCountList',
            }
            this.$store.dispatch('getItems', payload)

        },
        getBranchCount() {
            var payload = {
                model: 'branch_count',
                update: 'updateBranchCount',
            }
            this.$store.dispatch('getItems', payload)

        },
        getOuCount() {
            var payload = {
                model: 'ou_count',
                update: 'updateOuCount',
            }
            this.$store.dispatch('getItems', payload)

        },
        getUserCount() {
            var payload = {
                model: 'user_count',
                update: 'updateUserCountList',
            }
            this.$store.dispatch('getItems', payload)

        },
        refresh_dashboard() {
            this.getLowstock()
            // this.delivered_pkg()
            this.getProductCount()
            this.filterSales()
            // this.$store.dispatch('getChartSummary')
            // this.getChartSummary()
            this.getPending()
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
        date_stats(data) {

            console.log(data);
            this.form.year = data
            // this.refresh()

            this.getDispatched()
            this.getDelivered()
            this.getReturns()
            this.getPending()

        },
        ou_filter() {
            this.refresh()
            eventBus.$emit('progressEvent')
            // this.getChartSummary()
            // this.vedor_data()
            // // this.getGroup_count()
            // this.getScheduled()
            // this.getDispatched()
            // this.getDelivered()
            // this.getReturns()
            // this.getPending()
            // this.getProductCount()
            // this.filterSales()
            // this.getVendorount()
            // this.getBranchCount()
            // this.getOuCount()
            // this.getUserCount()
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
            this.getPending()

            this.getDelivered()
            this.getProductCount()
            this.getDispatched()
            this.getLowstock()
            this.getOnhand()
            this.getCommited()
            // Charts
            this.vedor_data()
            this.getCountries()
            // this.refresh_dashboard()
            this.getVendorount()
            this.getBranchCount()
            this.getOuCount()
            this.getUserCount()

            eventBus.$emit('SaleRefreshEvent')
        }
    },
    computed: {
        ...mapState(['product_count', 'scheduled_count', 'dispatched_count', 'delivered_count', 'returns_count', 'low_stoke', 'pending', 'onhand', 'chartsummary', 'active_chart', 'ous', 'commited', 'sales_chart', 'vendor_performance', 'user_count', 'sellers_count', 'countries_count', 'branches_count'])
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
    box-shadow: 0 9px 30px -6px rgba(255, 54, 54, .5);
    padding: 5px;
    border-radius: 50%;
}

.info-title {
    margin-top: 20px;
}
</style>

<template>
<div style="margin-top: 20px !important;text-align: center" theme="dark">
    <v-card style="padding: 0 20px">

        <v-layout justify-center align-center wrap>

            <v-flex sm12>
                <v-layout row wrap style="margin-bottom: 1px;padding: 0 10px;">
                    <v-flex sm6>
                        <div>
                            <!-- <v-select :items="AllCount" v-model="form.ou" hint="COUNTRY" label="Filter By Ou" single-line item-text="ou_name" item-value="id" persistent-hint @change="ouDash()"></v-select> -->
                            <el-select filterable v-model="form.ou" placeholder="Select Ou" style="width: 100%; margin-top: 80px;" @change="refresh">
                                <el-option v-for="item in ous" :key="item.id" :label="item.ou" :value="item.id">
                                </el-option>
                            </el-select>
                        </div>

                    </v-flex>
                    <v-flex sm6>
                        <el-select allow-create filterable v-model="form.year" placeholder="Select Year" style="width: 100%; margin-top: 80px;" @change="refresh">
                            <el-option v-for="item in options" :key="item.value" :label="item.label" :value="item.value">
                            </el-option>
                        </el-select>
                    </v-flex>

                </v-layout>
                <v-layout wrap>

                    <div class="col-lg-12 col-md-4 col-sm-12">
                        <div class="card card-chart">
                            <div class="card-body">
                                <div class="chart-area">
                                    <myChart :height="255" :form="form" />
                                    <!-- <h3 class="text-center" style="color: #fff;">Orders Chart</h3> -->
                                    <!-- <myDelivered :height="255" /> -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="panel-header panel-header-lg row" style="width: 120% !important">
                            <div class="column">
                                <h3 class="text-center" style="color: #fff;">Shipments Chart</h3>
                                <myChart :height="255" :width="1300" />
                            </div>

                        </div> -->
                </v-layout>
                <div class="col-md-12">
                    <div class="card card-stats card-raised">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3" style="cursor: pointer">
                                    <v-hover v-slot="{ hover }">
                                        <v-card :elevation="hover ? 12 : 2" :class="{ 'on-hover': hover }" @click="openOrders('Scheduled')">
                                            <div class="statistics">
                                                <div class="">
                                                    <div class="icon icon-primary">
                                                        <v-icon color="green">mdi-truck</v-icon>
                                                    </div>
                                                    <h3 class="info-title"><span><b>{{ scheduled_count }} </b></span></h3>
                                                    <h6 class="stats-title"><strong>To be Shipped</strong></h6>
                                                </div>
                                            </div>
                                        </v-card>
                                    </v-hover>
                                </div>
                                <v-divider vertical></v-divider>
                                <div class="col-md-3">
                                    <div class="statistics">
                                        <div class="">
                                            <div class="icon icon-success">
                                                <v-icon color="purple">mdi-check-circle-outline</v-icon>
                                            </div>
                                            <h3 class="info-title"><span><b>{{ delivered_count }} </b></span></h3>
                                            <h6 class="stats-title"><strong>Delivered</strong></h6>
                                        </div>
                                    </div>
                                </div>
                                <v-divider vertical></v-divider>
                                <div class="col-md-3">
                                    <div class="statistics">
                                        <div class="">
                                            <div class="icon icon-info">
                                                <v-icon color="indigo">mdi-map</v-icon>
                                            </div>
                                            <h3 class="info-title"><span><b>{{ returns_count }}</b></span></h3>
                                            <h6 class="stats-title"><strong>Returned</strong></h6>
                                        </div>
                                    </div>
                                </div>
                                <v-divider vertical></v-divider>
                                <div class="col-md-2">
                                    <div class="statistics">
                                        <div class="">
                                            <div class="icon icon-danger">
                                                <v-icon color="red">mdi-call-split</v-icon>
                                            </div>
                                            <h3 class="info-title"><span><b>{{ pending }}</b></span></h3>
                                            <h6 class="stats-title"><strong> Pending</strong></h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- </div> -->

                <v-divider></v-divider>
                <div class="row">
                    <!-- <div class="col-lg-4 col-md-4 col-sm-12">
                            <div class="card card-chart">
                                <div class="card-body">
                                    <div class="chart-area">
                                        <my-schedule :height="255"></my-schedule>
                                    </div>
                                </div>
                            </div>
                        </div> -->
                    <div class="col-lg-6 col-md-4 col-sm-12">
                        <div class="card card-chart">
                            <div class="card-body">
                                <div class="chart-area">
                                    <myDelivered :height="255" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-4 col-sm-12">
                        <div class="card card-chart">
                            <div class="card-body">
                                <div class="chart-area">
                                    <!-- <my-cancled :height="255"></my-cancled> -->
                                    <myReturn :height="255" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12" style="margin-top: 40px;">
                    <div class="card card-stats card-raised">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="statistics">
                                        <div class="">
                                            <div class="icon icon-danger">
                                                <v-icon color="danger">mdi-select-place</v-icon>
                                            </div>
                                            <h3 class="info-title"><span><b>{{ product_count }}</b></span></h3>
                                            <h6 class="stats-title"><strong> Products</strong></h6>
                                        </div>
                                    </div>
                                </div>
                                <v-divider vertical></v-divider>
                                <div class="col-md-3">
                                    <div class="statistics">
                                        <div class="">
                                            <div class="icon icon-primary">
                                                <v-icon color="pink">mdi-priority-low</v-icon>
                                            </div>
                                            <h3 class="info-title"><span><b>{{ low_stoke }}</b></span></h3>
                                            <h6 class="stats-title"><strong>Low Stock</strong></h6>
                                        </div>
                                    </div>
                                </div>
                                <v-divider vertical></v-divider>
                                <div class="col-md-3">
                                    <div class="statistics">
                                        <div class="">
                                            <div class="icon icon-success">
                                                <v-icon color="orange">mdi-hand</v-icon>
                                            </div>
                                            <h3 class="info-title"><span><b>{{ onhand }}</b></span></h3>
                                            <h6 class="stats-title"><strong>On hand</strong></h6>
                                        </div>
                                    </div>
                                </div>
                                <v-divider vertical></v-divider>
                                <div class="col-md-2">
                                    <div class="statistics">
                                        <div class="">
                                            <div class="icon icon-info">
                                                <v-icon color="info">mdi-source-commit-end</v-icon>
                                            </div>
                                            <h3 class="info-title"><span><b>{{ commited }}</b></span></h3>
                                            <h6 class="stats-title"><strong>Commited</strong></h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <v-divider></v-divider>

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

                <div class="col-md-12" style="margin-top: 40px;">
                    <div class="card card-stats card-raised">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="statistics">
                                        <div class="">
                                            <div class="icon icon-danger">
                                                <v-icon color="danger">mdi-account-circle</v-icon>
                                            </div>
                                            <h3 class="info-title"><span><b>{{ user_count }}</b></span></h3>
                                            <h6 class="stats-title"><strong> Users</strong></h6>
                                        </div>
                                    </div>
                                </div>
                                <v-divider vertical></v-divider>
                                <div class="col-md-3">
                                    <div class="statistics">
                                        <div class="">
                                            <div class="icon icon-primary">
                                                <v-icon color="pink">mdi-account-multiple</v-icon>
                                            </div>
                                            <h3 class="info-title"><span><b>{{ sellers_count }}</b></span></h3>
                                            <h6 class="stats-title"><strong>Clients</strong></h6>
                                        </div>
                                    </div>
                                </div>
                                <v-divider vertical></v-divider>
                                <div class="col-md-3">
                                    <div class="statistics">
                                        <div class="">
                                            <div class="icon icon-success">
                                                <v-icon color="orange">mdi-hand</v-icon>
                                            </div>
                                            <h3 class="info-title"><span><b>{{ countries_count }}</b></span></h3>
                                            <h6 class="stats-title"><strong>Ous</strong></h6>
                                        </div>
                                    </div>
                                </div>
                                <v-divider vertical></v-divider>
                                <div class="col-md-2">
                                    <div class="statistics">
                                        <div class="">
                                            <div class="icon icon-info">
                                                <v-icon color="info">mdi-source-commit-end</v-icon>
                                            </div>
                                            <h3 class="info-title"><span><b>{{ branches_count }}</b></span></h3>
                                            <h6 class="stats-title"><strong>Zones</strong></h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="card card-chart">
                            <div class="card-body">
                                <div class="chart-area">
                                    <!-- <my-ou :height="255"></my-ou> -->
                                </div>
                                <!-- <div class="progress-Ship">
                                    <img src="storage/ou/ke.png" alt="">
                                    Kenya: {{ parseInt(ouC.Kenya/Allshipments*100) }} %
                                    <v-progress-linear color="secondary" height="2" :value="ouC.Kenya / Allshipments * 100"></v-progress-linear>
                                    <img src="storage/ou/tz.png" alt="">
                                    Tanzania: {{ parseInt(ouC.Tanzania/Allshipments*100) }} %
                                    <v-progress-linear color="success" height="2" :value="ouC.Tanzania / Allshipments * 100"></v-progress-linear>
                                    <img src="storage/ou/ug.png" alt="">
                                    Uganda: {{ parseInt(ouC.Uganda/Allshipments*100) }} %
                                    <v-progress-linear color="info" height="2" :value="ouC.Uganda / Allshipments * 100"></v-progress-linear>
                                </div> -->
                                <!-- <div class="progress-Ship">
                                    <div v-for="ou in ouC" :key="ou.id">
                                        {{ ou.name }}: {{ parseInt(ou.count / Allshipments * 100) }} %
                                        <v-progress-linear color="red" height="2" :value="ou.count / Allshipments * 100"></v-progress-linear>
                                    </div>
                                </div> -->
                            </div>
                        </div>
                    </div>
                    <!-- <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="card card-chart">
                            <div class="card-body">
                                <div class="chart-area">
                                    <my-branch :height="255"></my-branch>
                                </div>
                                <div class="progress-Ship">
                                    <div v-for="branch in branchC" :key="branch.id">
                                        {{ branch.name }}: {{ parseInt(branch.count / Allshipments * 100) }} %
                                        <v-progress-linear color="indigo" height="2" :value="branch.count / Allshipments * 100"></v-progress-linear>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> -->
                </div>
                <!-- <v-btn @click="getBranchCount" flat color="primary">Count</v-btn> -->
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
                model: 'pending',
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

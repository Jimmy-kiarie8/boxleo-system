<template>
<div style="margin-top: 20px !important;text-align: center" theme="dark">
    <v-card>

        <v-layout justify-center align-center wrap>

            <v-flex sm12>
                <v-layout row wrap style="margin-bottom: 1px;padding: 0 10px;">
                    <v-flex sm3>
                        <!-- <v-btn color="primary" @click="date_stats" style="margin-top: 80px;" text>
                            <v-icon style="font-size: 20px !important;">mdi-calendar</v-icon>
                            Today Statistics
                        </v-btn> -->
                        <el-date-picker v-model="form.stat_date" type="date" placeholder="Pick a day" format="yyyy/MM/dd" value-format="yyyy-MM-dd" style="margin-top: 80px;" @change="date_stats">
                        </el-date-picker>
                    </v-flex>
                    <v-flex sm5>
                        <div>
                            <!-- <v-select :items="AllCount" v-model="form.ou" hint="COUNTRY" label="Filter By Ou" single-line item-text="ou_name" item-value="id" persistent-hint @change="ouDash()"></v-select> -->
                            <el-select filterable v-model="form.ou" placeholder="Select OU" style="width: 100%; margin-top: 80px;" @change="refresh">
                                <el-option v-for="item in ous" :key="item.id" :label="item.ou" :value="item.id">
                                </el-option>
                            </el-select>
                        </div>

                    </v-flex>
                    <v-flex sm4>
                        <el-select allow-create filterable v-model="form.year" placeholder="Select Year" style="width: 100%; margin-top: 80px;" @change="refresh">
                            <el-option v-for="item in options" :key="item.value" :label="item.label" :value="item.value">
                            </el-option>
                        </el-select>
                    </v-flex>

                </v-layout>

                <v-card elevation="5" style="padding: 2px 0;width: 97%; margin: auto;margin-top: 10px; box-shadow: rgb(18 59 175 / 55%) 0px 0px 30px 0px !important">
                    <v-card-text>
                        <v-layout row wrap>
                            <v-flex sm3 style="border-right: 1px solid rgba(0,0,0,.12); cursor: pointer" @click="openOrders('Sheduled')">
                                <div class="icon icon-primary">
                                    <v-icon color="green">mdi-warehouse</v-icon>
                                </div>
                                <h3 class="info-title"><span><b>{{ warehouse_count }} </b></span></h3>
                                <h6 class="stats-title"><strong>Warehouses</strong></h6>
                            </v-flex>
                            <v-flex sm3 @click="openOrders('Pedding')" style="border-right: 1px solid rgba(0,0,0,.12);">
                                <div class="">
                                    <div class="icon icon-info">
                                        <v-icon color="info">mdi-source-commit-end</v-icon>
                                    </div>
                                    <h3 class="info-title"><span><b>{{ bins_count }}</b></span></h3>
                                    <h6 class="stats-title"><strong>Total Storage Bins</strong></h6>
                                </div>
                            </v-flex>
                            <v-flex sm3 style="border-right: 1px solid rgba(0,0,0,.12); cursor: pointer" @click="openOrders('Returned')">
                                <div class="icon icon-info">
                                    <v-icon color="indigo">mdi-clipboard-arrow-left</v-icon>
                                </div>
                                <h3 class="info-title"><span><b>{{ commited_count }}</b></span></h3>
                                <h6 class="stats-title"><strong>Commited</strong></h6>
                            </v-flex>
                            <v-flex sm3 style="cursor: pointer" @click="openOrders('Pedding')">
                                <div class="icon icon-danger">
                                    <v-icon color="red cursor: pointer">mdi-timelapse</v-icon>
                                </div>
                                <h3 class="info-title"><span><b>{{ available_count }}</b></span></h3>
                                <h6 class="stats-title"><strong> Available for sale</strong></h6>
                            </v-flex>
                        </v-layout>
                    </v-card-text>
                </v-card>

                <v-divider></v-divider>

                <v-card elevation="5" style="padding: 2px 0;width: 97%; margin: auto;margin-top: -20px; box-shadow: rgb(18 59 175 / 55%) 0px 0px 30px 0px !important">
                    <v-card-text>
                        <v-layout row wrap>
                            <v-flex sm3 style="border-right: 1px solid rgba(0,0,0,.12);">
                                <div class="">
                                    <div class="icon icon-success">
                                        <v-icon color="orange">mdi-hand</v-icon>
                                    </div>
                                    <h3 class="info-title"><span><b>{{ onhand }}</b></span></h3>
                                <h6 class="stats-title"><strong>Total Stock onhand</strong></h6>
                                </div>
                            </v-flex>
                            <v-flex sm3 style="border-right: 1px solid rgba(0,0,0,.12); cursor: pointer" @click="openOrders('Delivered')">
                                <div class="icon icon-success">
                                    <v-icon color="purple">mdi-check-circle-outline</v-icon>
                                </div>
                                <h3 class="info-title"><span><b>{{ dispatch_count }} </b></span></h3>
                                <h6 class="stats-title"><strong>Dispatched</strong></h6>
                            </v-flex>
                            <v-flex sm3 style="border-right: 1px solid rgba(0,0,0,.12); cursor: pointer" @click="openOrders('Sheduled')">
                                <router-link :to="(user.can['Product view']) ? '/products' : '/'" style="text-decoration: none;color: #686666;">
                                    <div class="icon icon-danger">
                                        <v-icon color="danger">mdi-basket</v-icon>
                                    </div>
                                    <h3 class="info-title"><span><b>{{ product_count }}</b></span></h3>
                                    <h6 class="stats-title"><strong> Products</strong></h6>
                                </router-link>
                            </v-flex>
                            <v-flex sm3>
                                <router-link to="/lowstoke" style="text-decoration: none;color: #686666;">
                                    <div class="icon icon-primary">
                                        <v-icon color="pink">mdi-priority-low</v-icon>
                                    </div>
                                    <h3 class="info-title"><span><b>{{ lowStock }}</b></span></h3>
                                    <h6 class="stats-title"><strong>Low Stock</strong></h6>
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
import {
    mapState
} from "vuex";
export default {
    props: ["user"],
    components: {

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
            // this.$router.push({
            //     name: "tracking",
            //     query: {
            //         data: data
            //     }
            // });
        },
        getWarehouse() {
            var payload = {
                model: 'warehouse_count',
                update: 'updateWarehouseCount',
            }
            this.$store.dispatch('getItems', payload)
        },
        getBins() {
            var payload = {
                model: 'bins_count',
                update: 'updateBinCount',
                search: '?ou=' + this.form.ou + '&year=' + this.form.year,
            }
            this.$store.dispatch('searchItems', payload)

        },
        getCommited() {
            var payload = {
                model: 'commited_count',
                update: 'updateCommitedCount',
                search: '?ou=' + this.form.ou + '&year=' + this.form.year,
            }
            this.$store.dispatch('searchItems', payload)

        },
        getAvailable() {
            var payload = {
                model: 'available_count',
                update: 'updateAvailable',
                search: '?ou=' + this.form.ou + '&year=' + this.form.year,
            }
            this.$store.dispatch('searchItems', payload)

        },

        getDispatched() {
            var payload = {
                model: 'dispatch_count',
                update: 'updateDispatchCount',
                search: '?ou=' + this.form.ou + '&year=' + this.form.year,
            }
            this.$store.dispatch('getItems', payload)

        },
        getOnhand() {
            var payload = {
                model: 'onhand_count',
                update: 'updateOnhand',
                search: '?ou=' + this.form.ou + '&year=' + this.form.year,
            }
            this.$store.dispatch('getItems', payload)
        },

        getProductCount() {
            var payload = {
                model: 'product_count',
                update: 'UpdateProductsCountList',
                search: 'available?ou=' + this.form.ou + '&year=' + this.form.year,
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
            this.refresh()
        },

        refresh() {
            this.getWarehouse()
            this.getBins()
            this.getCommited()
            this.getAvailable()
            this.getDispatched()
            this.getOnhand()
            this.getLowstock()
            this.getProductCount()
        }
    },
    computed: {
        ...mapState([
            'lowStock', 'warehouse_count', 'bins_count', 'commited_count', 'available_count', 'dispatch_count', 'product_count', 'ous', 'onhand'
        ])
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

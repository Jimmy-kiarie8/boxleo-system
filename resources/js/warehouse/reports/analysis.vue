<template>
    <v-card>
        <v-card-text>


            <v-row no-gutters>
                <v-col cols="12" sm="3">
                    <!-- <v-autocomplete clearable chips label="Report" :items="reports" variant="outlined" item-title="label"
                    item-value="value" v-model="form.report"></v-autocomplete> -->
                    <v-autocomplete v-model="form.report" :items="reports" item-text="label" prepend-icon="mdi-book"
                        item-value="value" label="Report" return-object></v-autocomplete>
                </v-col>

                <v-col cols="12" sm="3" v-if="form.report.value === 'generateAgentPerformanceReport' || form.report.value === 'perfomance'">
                    <v-autocomplete v-model="form.agent_id" :items="users" item-text="name" prepend-icon="mdi-account-circle"
                        item-value="id" label="Agent" multiple chips clearable></v-autocomplete>
                </v-col>


                <v-col cols="12" sm="3" v-if="form.report.value === 'generateMerchantPerformanceReport' || form.report.value === 'generateRiderPerformanceReport'">
                    <v-autocomplete v-model="form.seller_id" :items="sellers.data" item-text="name" prepend-icon="mdi-account-circle"
                        item-value="id" label="Merchant" multiple chips clearable></v-autocomplete>
                </v-col>

                <v-col cols="12" sm="3" v-if="form.report.value === 'generateRiderPerformanceReport'">
                    <v-autocomplete v-model="form.rider_id" :items="riders" item-text="name" prepend-icon="mdi-account-circle"
                        item-value="id" label="Riders" multiple chips clearable>
                    </v-autocomplete>
                </v-col>


                <v-col cols="12" sm="3">
                    <v-text-field clearable v-model="form.start_date" label="Start Date" variant="outlined"
                        type="date"></v-text-field>
                </v-col>
                <v-col cols="12" sm="3">
                    <v-text-field clearable v-model="form.end_date" label="End Date" variant="outlined"
                        type="date"></v-text-field>
                </v-col>

                <v-col cols="1" sm="1">
                    <v-btn @click="getReport" color="primary" depressed elevation="2">
                        <v-icon>mdi-filter</v-icon> Filter
                    </v-btn>
                </v-col>


                <v-col cols="1" sm="1">
                    <download-excel :data="data" :fields="header_data" :name="today + ' report.xls'">
                        <v-tooltip bottom>
                            <template v-slot:activator="{ on }">
                                <v-btn icon v-on="on" slot="activator" class="mx-0">
                                    <v-icon>mdi-file-excel</v-icon>
                                </v-btn>
                            </template>
                            <span>Download report</span>
                        </v-tooltip>

                    </download-excel>
                </v-col>
            </v-row>

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <v-data-table :headers="headers" :items="data" :sort-by="[{ key: 'id', order: 'asc' }]"
                    class="elevation-2" :search="search" :loading="loading">
                    <template v-slot:top>
                        <v-toolbar flat>
                            <v-toolbar-title>Report Management</v-toolbar-title>
                            <v-divider class="mx-4" inset vertical></v-divider>
                            <v-spacer></v-spacer>

                        </v-toolbar>
                        <v-text-field v-model="search" append-icon="mdi-magnify" label="Search" single-line
                            hide-details></v-text-field>
                    </template>
                    <template v-slot:item.actions="{ item }">
                        <v-icon size="small" class="me-2" @click="editItem(item)">
                            mdi-pencil
                        </v-icon>
                        <v-icon size="small" @click="deleteItem(item)">
                            mdi-delete
                        </v-icon>
                    </template>

                    <template v-slot:item.profile_photo_url="{ value }">
                        <v-avatar :image="value"></v-avatar>
                    </template>

                </v-data-table>
            </div>
        </v-card-text>

        <v-row no-gutters>
            <v-col cols="12" sm="6">
                <v-card class="pa-2" elevation="2">
                    <myChart :height="400" :chartData="delivery_chart" :title="'Delivered'" :color="'#54b867'" />
                </v-card>
            </v-col>
            <v-col cols="12" sm="6">
                <v-card class="pa-2" elevation="2">
                    <myChart :height="400" :chartData="return_chart" :title="'Returned'" :color="'#f00'" />
                </v-card>
            </v-col>
        </v-row>
        <hr>

    </v-card>
</template>

<script>
import axios from 'axios';
import moment from 'moment'
import {
    mapState
} from "vuex";
import myChart from './chart.js'
export default {
    props: {
    },
    components: {
        myChart
    },
    data() {
        return {
            rows: [],
            label: [],
            loading: false,
            form: {
                start_date: null,
                end_date: null,
                agent_id: null,
                report: {
                    label: 'generateAgentPerformanceReport',
                }
            },
            search: '',
            page: 1,
            itemsPerPage: 5,
            data: [],
            headers: [],
            delivery_chart: [],
            return_chart: [],
            header_data: {},
            reports: [
                { label: 'Agent Performance Report', value: 'generateAgentPerformanceReport' },
                { label: 'Merchant Performance Report', value: 'generateMerchantPerformanceReport' },
                { label: 'Rider Performance Report', value: 'generateRiderPerformanceReport' },
                { label: 'Perfomance', value: 'perfomance' },
                // { label: 'Lead Status Report', value: 'generateLeadStatusReport' },
                // { label: 'System Calls Trend Report', value: 'generateSystemCallsTrendReport' },
                // { label: 'Leads Conversion Comparison Report', value: 'generateLeadsConversionComparisonReport' },
                // { label: 'First Call Resolution Rate Report', value: 'generateFirstCallResolutionRateReport' },
                // { label: 'Average Call Time Report', value: 'generateAverageCallTimeReport' },
                // { label: 'Call Abandonment Rate Report', value: 'generateCallAbandonmentRateReport' },
                // { label: 'Agent Activity Over Time Report', value: 'generateAgentActivityOverTimeReport' },
                // { label: 'Total Amount Spent Report', value: 'generateTotalAmountSpentReport' },
                // { label: 'Overall SystemSummary Report', value: 'generateOverallSystemSummaryReport' },
            ]
        }
    },
    methods: {
        getReport() {
            if (!this.form.report) {
                return;
            }
            this.loading = true;
            axios.post(`analysis/${this.form.report.value}`, this.form).then((res) => {
                this.loading = false;
                // console.log("🚀 ~ file: index.vue:58 ~ axios.get ~ res:", res.data)
                this.data = res.data.data
                this.headers = res.data.headers

                this.delivery_chart = {
                    rows: res.data.delivery_chart.rows,
                    labels: res.data.delivery_chart.labels
                }

                this.return_chart = {
                    rows: res.data.return_chart.rows,
                    labels: res.data.return_chart.labels
                }
                eventBus.$emit('reportEvent')

                setTimeout(() => {
                    this.convertedData(res.data.data)
                }, 100);

            }).catch((error) => {
                console.log("🚀 ~ axios.post ~ error:", error)
                this.loading = false;
            })
        },

        getSellers() {
            var payload= {
                model: '/seller/sellers',
                update: 'updateSellerList'
            };
            this.$store.dispatch("getItems", payload);
        },

        getAgent() {
            var payload = {
                model: 'agents',
                update: 'updateUsersList',
            }
            this.$store.dispatch("getItems", payload);
        },

        getRiders() {
            var payload = {
                model: 'rider/riders',
                update: 'updateRidersList'
            }
            this.$store.dispatch("getItems", payload);
        },


        convertedData(data) {
            const converted = {};
            this.headers.forEach(item => {
                converted[item.text] = item.value;
            });
            console.log("🚀 ~ convertedData ~ converted:", converted)
            this.header_data = converted;
        }
    },

    computed: {
        pageCount() {
            return Math.ceil(this.data.data.length / this.itemsPerPage)
        },
        today() {
            return moment().format('yyyy-MM-DD');
        },
        ...mapState(['users', 'riders', 'sellers'])
    },
    mounted() {
        this.getAgent();
        this.getRiders()
        this.getSellers()
    }
}
</script>


<style scoped>
#tooltip {
    font-style: inherit;
    font-weight: inherit;
    white-space: nowrap;
    text-overflow: ellipsis;
    max-width: 180px;
    overflow: hidden;
    display: block;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
}

.v-card--variant-elevated {
    padding: 30px
}

.col-sm-3 {
    padding: 5px !important;
}


</style>

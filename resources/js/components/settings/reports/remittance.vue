<template>
<div>
    <div>
        <v-layout justify-center align-center wrap>
            <v-progress-linear :active="loading" indeterminate rounded height="3"></v-progress-linear>
            <v-card style="width: 100%; padding: 30px 0 40px 20px">

                <v-layout row wrap>
                    <v-flex sm2 style="margin-left: 10px; margin-bottom: 20px">
                        <label for="">Client</label>
                        <el-select v-model="form.client" placeholder="Client" clearable filterable>
                            <el-option v-for="item in sellers.data" :key="item.id" :label="item.name" :value="item.id">
                            </el-option>
                        </el-select>
                    </v-flex>

                    <v-flex sm2 style="margin-left: 10px">
                        <div class="block">
                            <label style="float: left">Start date</label>
                            <el-date-picker format="yyyy/MM/dd" value-format="yyyy-MM-dd" v-model="form.start_date" type="date" placeholder="Pick a day">
                            </el-date-picker>
                        </div>
                        <small class="has-text-danger" v-if="errors.start_date">{{ errors.start_date[0] }}</small>
                    </v-flex>
                    <v-flex sm2 style="margin-left: 10px">
                        <div class="block">
                            <label style="float: left">End date</label>
                            <el-date-picker format="yyyy/MM/dd" value-format="yyyy-MM-dd" v-model="form.end_date" type="date" placeholder="Pick a day">
                            </el-date-picker>
                        </div>
                        <small class="has-text-danger" v-if="errors.end_date">{{ errors.end_date[0] }}</small>
                    </v-flex>

                    <v-flex sm2 style="margin-left: 10px">
                        <v-btn-toggle dense dark style="margin-top: 22px">
                            <v-btn @click="get_report">
                                <v-icon>mdi-magnify</v-icon>
                            </v-btn>
                            <v-tooltip bottom>
                                <template v-slot:activator="{ on }">
                                    <v-btn icon v-on="on" class="mx-0" slot="activator" @click="remit_charges">
                                        <v-icon color="white">mdi-check-circle-outline</v-icon>
                                    </v-btn>
                                </template>
                                <span>Remit. We will send you an email of the report</span>
                            </v-tooltip>
                            <!--
                            <v-btn @click="downloadPackage">
                                <v-icon>mdi-adobe-acrobat</v-icon>
                            </v-btn> -->
                        </v-btn-toggle>
                    </v-flex>
                </v-layout>
                <v-spacer></v-spacer>
            </v-card>
            <Tableview v-if="report_show" :headers="headers" :form="form" :report="'Remitance'" />
        </v-layout>
    </div>

    <form action="/remittance_download" method="post" ref="remit_form" target="_blank">
        <input type="hidden" name="_token" :value="csrf">
        <input type="hidden" name="packages" :value="form_data">
    </form>
</div>
</template>

<script>
// import Kanbanview from './Kanbanview'
import Tableview from "./Tableview";
import {
    mapState
} from "vuex";
export default {
    components: {
        // Kanbanview,
        Tableview,
    },
    data() {
        return {
            csrf: document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content"),
            kanban: false,
            toggle_exclusive: 2,
            form: {},
            report_show: false,
            reports: [{
                    report: "Delivered",
                },
                {
                    report: "Returned",
                },
                // {
                //     report: 'Reshipped',
                // },
                {
                    report: "Dispatched",
                },
                {
                    report: "Status",
                },
                {
                    report: "Product",
                },
                {
                    report: "Custom",
                },
            ],
        form_data: null,
            headers: [],
        };
    },
    created() {
        // this.ResetItem();
    },
    methods: {
        remit_charges() {
            // this.form.remit
            this.$refs.remit_form.submit()
            /* this.form.report = "Remittance";
            var payload = {
                model: "remittance_download",
                update: "updateSaleList",
                data: this.form,
            };
            this.$store.dispatch("filterItems", payload).then((response) => {

            }); */
        },
        get_report() {
            this.form.report = "Remittance";
            var payload = {
                model: "remit",
                update: "updateSaleList",
                data: this.form,
            };
            this.$store.dispatch("filterItems", payload).then((response) => {
                this.getRemit()
                // console.log(response.data);
                this.headers = response.data.table_columns;
                // eventBus.$emit('changeHeaderEvent', response.data.columns)
                this.report_show = true;

            });
        },
        get_custom_report(report) {
            if (report == "Custom") {
                var payload = {
                    model: "custom_reports",
                    update: "updateCustomReport",
                    // data: this.form
                };
                this.$store.dispatch("getItems", payload);
            }
        },
        getStatus() {
            var payload = {
                model: "statuses",
                update: "updateStatusList",
            };
            this.$store.dispatch("getItems", payload);
        },
        downloadPackage() {
            eventBus.$emit('downloadEvent')
            // this.$refs.form.submit();
        },
        picking_list() {
            this.$refs.picking_form.submit();
        },
        export_dispatch() {
            this.$refs.dispatch_form.submit();
        },
        getSellers() {
            var payload = {
                model: "/seller/sellers",
                update: "updateSellerList",
            };
            this.$store.dispatch("getItems", payload);
        },
        ResetItem() {

            var payload = {
                update: "updateSaleList",
            };
            this.$store.dispatch("resetItems", payload);
        },

        getRemit() {
            // setTimeout(() => {
                var amount = 0;
                var charge = 0;

                if (this.sales.sales) {
                    this.sales.sales.forEach((element) => {
                        amount += parseInt(element.total_price);
                        charge += parseInt(element.shipping_charges);
                    });
                }
                this.form.remit = {
                    amount: amount,
                    charge: charge,
                };

                this.serialize_data()

            // }, 2000);
        },


        serialize_data() {
            var start_date = this.form.start_date
            var end_date = this.form.end_date
            var client = this.form.client
            // var remit = this.form.remit
            var dates_ = {
                'start_date': start_date,
                'end_date': end_date,
                'client': client,
                'remit': this.form.remit
                // 'sales': this.sales.sales
            }
            this.form_data = JSON.stringify(dates_)
        },
    },
    mounted() {
        this.getSellers();
        this.getStatus();
    },
    computed: {
        ...mapState([
            "sellers",
            "statuses",
            "sales",
            "custom_report",
            "loading",
            "errors",
        ]),
    },
};
</script>

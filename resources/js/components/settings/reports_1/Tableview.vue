<template>
<v-card style="width: 100%; padding: 0 20px">
    <v-card-title>
        Report
        <v-spacer></v-spacer>
        <v-text-field v-model="search" append-icon="mdi-magnify" label="Search" single-line hide-details></v-text-field>
    </v-card-title>

    <v-chip-group mandatory>
        <v-chip color="primary">
            Total COD: {{ remmit.amount }}
        </v-chip>
        <v-chip color="error">
            Total Charges: {{ remmit.charge }}
        </v-chip>
        <v-chip color="success">
            Total Remmit: {{ remmit.amount - remmit.charge }}
        </v-chip>
    </v-chip-group>
    <!-- <v-data-table :headers="headers" :items="sales.sales" :search="search" :loading="loading"></v-data-table> -->

    <v-data-table :headers="headers" :items="sales.sales" :search="search" :loading="loading">

        <!-- <template v-slot:item.prepaid="{ item }">
            <el-tooltip :content="(item.prepaid) ? 'Paid' : 'Not paid'" placement="top">
                <v-avatar style="cursor: pointer" :color="(item.prepaid) ? 'green' : 'red'" small></v-avatar>
            </el-tooltip>
        </template> -->
    </v-data-table>
    <!-- 
    <form action="/remittance_download" method="post" ref="form" target="_blank">
        <input type="hidden" name="_token" :value="csrf" />
        <input type="hidden" name="packages" :value="serialize_data" />
    </form> -->
</v-card>
</template>

<script>
import {
    mapState
} from "vuex";
export default {
    props: ["headers", 'form'],
    data() {
        return {
            csrf: document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content"),
            search: "",
        };
    },
    created() {
        eventBus.$on("downloadEvent", data => {
            this.downloadPackage()
        });

        eventBus.$on("defaultHeaderEvent", (data) => {});
    },
    methods: {
        downloadPackage() {
            this.$refs.form.submit();
        },
    },
    computed: {
        ...mapState(["sales", "loading"]),
        /*  serialize_data() {
             var start_date = this.form.start_date;
             var end_date = this.form.end_date;
             var client = this.form.client;
             var dates_ = {
                 start_date: start_date,
                 end_date: end_date,
                 client: client,
                 sales: this.sales.sales,
                 remmit: this.remmit,
             };
             return JSON.stringify(dates_);
         }, */
        remmit() {
            var amount = 0;
            var charge = 0;

            this.sales.sales.forEach((element) => {
                amount += parseInt(element.total_price);
                charge += parseInt(element.shipping_charges);
            });
            return {
                amount: amount,
                charge: charge,
            };
        },
    },
};
</script>

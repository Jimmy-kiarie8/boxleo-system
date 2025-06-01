<template>
<v-row>
    <v-col sm="5">
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

        <el-card shadow="hover">
            <h2>TOP SELLING ITEMS</h2>
            <v-divider></v-divider>
            <ul class="list-group">
                <v-layout wrap>
                    <v-flex sm12>
                        <table class="table table-hover" v-if="top_sales.length > 0">
                            <thead>
                                <th>Product name</th>
                                <th>Product price</th>
                                <th>Sold Quantity</th>
                            </thead>
                            <tbody>
                                <tr v-for="top_sale in top_sales" :key="top_sale.id">
                                    <td>{{ top_sale.product_name }}</td>
                                    <td>{{ top_sale.price }}</td>
                                    <td>{{ top_sale.quantity }}</td>
                                </tr>
                            </tbody>
                        </table>
                        <div v-else>
                            <p>No available data</p>
                        </div>
                    </v-flex>
                </v-layout>
            </ul>
        </el-card>
    </v-col>
    <v-col sm="6" class="offset-sm1">
        <el-card shadow="hover" style="border: 1px solid #8cdff9; border-left: 3px solid #0caee0; padding: 6px 5px; background-color: #edfafe;" v-for="(weekly_sale, key) in weekly_sale" :key="key">
            <h5 class="over-flow bottom-space">Total Sales</h5>
            <div class="so-summary-others"><span class="so-circle"></span>
                <div class="amt-channel">
                    <div class="over-flow" style="font-size: 16px;"><span id="ember961" class="popovercontainer ember-view" data-original-title="" title="">
                            <!-- <small style="font-size: 13px;">{{ key | moment }}</small> -->
                            <b style="color: red">KES {{ weekly_sale }}</b>
                        </span>
                    </div>
                </div>
            </div>
        </el-card>
    </v-col>
</v-row>
</template>

<script>
import {
    mapState
} from 'vuex';

export default {
    computed: {
        ...mapState(['weekly_sale', 'low_stoke', 'product_count', 'top_sales']),
    },
    methods: {

        total_week() {
            var payload = {
                model: "/weekly_sale",
                update: "updateWeeklySalesList"
            };
            this.$store.dispatch("getItems", payload);
        },
        lowStock() {
            var payload = {
                model: "/lowStock",
                update: "updateLowstokeList"
            };
            this.$store.dispatch("getItems", payload);
        },
        topSales() {
            var payload = {
                model: "/top_sales",
                update: "updateTopSaleList"
            };
            this.$store.dispatch("getItems", payload);
        },
    },
    mounted() {
        this.lowStock();
        this.total_week();
        this.topSales();
    },
};
</script>

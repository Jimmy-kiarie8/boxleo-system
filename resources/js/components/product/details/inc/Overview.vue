<template>

        <v-layout row wrap>

            <v-flex xs12 sm3 style="margin: 30px 0 0 30px">

                <p><label>SKU</label> <span style="float: right">{{ single_product.sku_no }}</span></p>
                <h3>Purchase Information</h3>
                <v-divider></v-divider>
                <p><label>Cost Price</label> <span style="float: right">{{ single_product.price }}</span></p>
                <p><label>Vendor</label> <span style="float: right" v-if="single_product.seller">{{ single_product.seller.name }}</span></p>
                <h3>Sales Information</h3>
                <p><label>Selling Price</label> <span style="float: right">{{ single_product.price }}</span></p>
                <v-divider></v-divider>

                <h3>Stock Locations</h3>
                <v-divider></v-divider>

            </v-flex>
            <v-flex xs12 sm4 offset-sm3>
                <div style="float: right;">
                    <img :src="single_product.image" style="height: 150px;border-radius: 50%;" />
                </div>
                <div style="width: 100%;float: right;margin-top:30px">
                    <h3>Stock </h3>
                    <v-divider></v-divider>
                    <p><label>Stock on Hand</label> <span style="float: right">{{ single_product.onhand }}</span></p>
                    <p><label>Committed Stock</label> <span style="float: right">{{ single_product.commited }}</span></p>
                    <p><label>Available for Sale</label> <span style="float: right">{{ single_product.available_for_sale }}</span></p>
                    <p><label>Reorder Point</label> <span style="float: right" v-if="single_product.skus">{{ single_product.skus.reorder_point }}</span></p>
                </div>

                <div style="width: 100%;float: right;margin-top:30px">
                    <h3>Stock Summary</h3>
                    <v-divider></v-divider>
                    <p><label>Quantity Delivered</label> <span style="float: right">{{ product_stat.quantity_delivered }}</span></p>
                    <p><label>Quantity Returned</label> <span style="float: right">{{ product_stat.quantity_returned }}</span></p>
                    <p><label>Quantity Intransit</label> <span style="float: right">{{ product_stat.quantity_intransit }}</span></p>
                </div>
            </v-flex>

            <v-flex sm11 style="padding: 10px 5px 0 20px">

                <table class="table table-hover table-striped table-bordered">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">BIN</th>
                            <th scope="col">STOCK ON HAND</th>
                            <th scope="col">COMMITTED STOCK </th>
                            <th scope="col">AVAILABLE FOR SALE</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(warehouse, index) in single_product.bins" :key="index">
                            <th scope="row">{{ index+1 }}</th>
                            <td>
                                {{ warehouse.code }}
                            </td>
                            <td>
                                {{ warehouse.pivot.onhand }}
                            </td>
                            <td>
                                {{ warehouse.pivot.commited }}
                            </td>

                            <td>
                                {{ warehouse.pivot.available_for_sale }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </v-flex>

            <v-flex sm11>
                <v-card class="mx-auto" outlined>
                    <v-card-text>
                        <div>Logixsaas</div>
                        <p class="text-h5 text--primary">
                            Product Statistics
                        </p>
                        <div class="text--primary">
                            <v-row>
                                <v-flex sm4 v-for="stats in single_product.daily_stats" :key="stats.id" style="margin-top: 30px">
                                    <v-card class="mx-auto" max-width="300" tile>
                                        <v-list dense>
                                            <v-subheader>
                                                <p class="text-h6 text--primary">
                                                    {{stats.created_at}}
                                                </p>
                                            </v-subheader>
                                            <v-subheader>{{single_product.product_name}}</v-subheader>
                                            <v-list-item-group v-model="selectedItem" color="primary">
                                                <v-list-item>
                                                    <v-list-item-icon>
                                                        <v-icon>mdi-check-circle</v-icon>
                                                    </v-list-item-icon>
                                                    <v-list-item-content>
                                                        <v-list-item-title> Delivered </v-list-item-title>
                                                    </v-list-item-content>

                                                    <v-list-item-action>
                                                        <v-list-item-action-text>{{ stats.delivered }}</v-list-item-action-text>
                                                    </v-list-item-action>
                                                </v-list-item>
                                                <v-list-item>
                                                    <v-list-item-icon>
                                                        <v-icon>mdi-cancel</v-icon>
                                                    </v-list-item-icon>
                                                    <v-list-item-content>
                                                        <v-list-item-title> Returned </v-list-item-title>
                                                    </v-list-item-content>

                                                    <v-list-item-action>
                                                        <v-list-item-action-text>{{ stats.returned }}</v-list-item-action-text>
                                                    </v-list-item-action>
                                                </v-list-item>
                                                <v-list-item>
                                                    <v-list-item-icon>
                                                        <v-icon>mdi-progress-check</v-icon>
                                                    </v-list-item-icon>
                                                    <v-list-item-content>
                                                        <v-list-item-title> Dispatched </v-list-item-title>
                                                    </v-list-item-content>

                                                    <v-list-item-action>
                                                        <v-list-item-action-text>{{ stats.dispatched }}</v-list-item-action-text>
                                                    </v-list-item-action>
                                                </v-list-item>
                                                <v-list-item>
                                                    <v-list-item-icon>
                                                        <v-icon>mdi-update</v-icon>
                                                    </v-list-item-icon>
                                                    <v-list-item-content>
                                                        <v-list-item-title> Scheduled </v-list-item-title>
                                                    </v-list-item-content>

                                                    <v-list-item-action>
                                                        <v-list-item-action-text>{{ stats.scheduled }}</v-list-item-action-text>
                                                    </v-list-item-action>
                                                </v-list-item>
                                            </v-list-item-group>
                                        </v-list>
                                    </v-card>

                                </v-flex>

                            </v-row>
                        </div>
                    </v-card-text>
                </v-card>
            </v-flex>
        </v-layout>
</template>

<script>
import {
    mapState
} from 'vuex'
export default {
    props: ['stats_data'],
    data() {
        return {
            selectedItem: 0
        }
    },

    computed: {
        ...mapState(['products', 'single_product', 'product_stat'])
    },
    mounted () {
        this.getStats();
    },
    methods: {
        image_error(e) {
            console.log(e);

            event.target.src = "https://img2.pngio.com/ikmf-krav-maga-default-png-720_405.png"

            // e.target.src = 'https://img2.pngio.com/ikmf-krav-maga-default-png-720_405.png'
        },
        getStats() {
            var payload = {
                model: '/product_analysis',
                id: this.$route.params.id,
                update: 'updateProductStat'
            }
            this.$store.dispatch("showItem", payload);
        },
    },
}
</script>

<style scoped>
.v-application p {
    color: #333 !important;
}

label {
    font-weight: 300;
    color: rgb(119, 119, 119);
}

h3 {
    font-size: 20px !important;
}
</style>

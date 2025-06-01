<template>
<v-card>
    <v-card-title primary-title>

    </v-card-title>
    <v-card-text>
        <el-collapse accordion>

            <el-collapse-item name="1" v-if="woocommerce_orders.exist_orders.length > 0">
                <template slot="title" style="color: red">
                    <span><b style="color: red">{{ woocommerce_orders.exist_orders.length }} Orders Exists</b><i class="header-icon el-icon-info"></i></span>
                </template>
                <div>
                    <v-list dense two-line>
                        <v-list-item-group color="primary">
                            <v-list-item v-for="(item, i) in woocommerce_orders.exist_orders" :key="i">
                                <v-list-item-content>
                                    <v-list-item-title>{{ item.order_no }} </v-list-item-title>
                                </v-list-item-content>
                            </v-list-item>
                        </v-list-item-group>
                    </v-list>
                </div>
            </el-collapse-item>
        </el-collapse>
        <v-data-table :headers="headers" :items="woocommerce_orders.sales" :search="search" :loading="loading">
            <!-- <v-data-table :headers="headers" :items="woocommerce_orders" :search="search" :item-class="itemRowBackground"> -->
            <!-- <div :style="[ (!order.product.id || !order.entry.orderid || !order.entry.quantity || !order.entry.address || !order.entry.clientname || !order.entry.phone) ? {'background': '#ff00008a'} : '']"> -->
            <template v-slot:item.product_name="{ item }">
                <v-layout row v-for="(product, index) in item.products" :key="index" style="padding: 10px 0;">
                    <v-flex sm8>
                        <el-select v-model="product.id" placeholder="Select" filterable clearable>
                            <el-option v-for="item in products.data" :key="item.id" :label="item.product_name" :value="item.id">
                            </el-option>
                        </el-select>
                    </v-flex>
                    <v-flex sm4>
                        <el-input placeholder="Please input" v-model="product.quantity"></el-input>
                    </v-flex>
                </v-layout>

            </template>

            <template v-slot:item.actions="{ item }">
                <v-tooltip bottom>
                    <template v-slot:activator="{ on }">
                        <v-btn v-on="on" icon class="mx-0" @click="remove(item)" slot="activator">
                            <v-icon small color="pink darken-2">mdi-delete</v-icon>
                        </v-btn>
                    </template>
                    <span>Remove</span>
                </v-tooltip>
            </template>

            <template v-slot:item.address="{ item }">
                <el-input type="textarea" autosize placeholder="Please input" v-model="item.address"></el-input>
            </template>
            <template v-slot:item.client_name="{ item }">
                <el-input placeholder="Please input" v-model="item.client_name"></el-input>
            </template>
            <template v-slot:item.phone="{ item }">
                <el-input placeholder="Please input" v-model="item.phone"></el-input>
            </template>
            <template v-slot:item.note="{ item }">
                <el-input type="textarea" autosize placeholder="Please input" v-model="item.note"></el-input>
            </template>
            <!-- </div> -->
        </v-data-table>

        <!-- </div> -->

    </v-card-text>
</v-card>
</template>

<script>
import {
    mapState
} from 'vuex';
export default {
    props: ['woocommerce_orders'],
    data() {
        return {
            search: '',
            valid: false,
            invalid_row: [],
            headers: [{
                    text: 'Order id',
                    value: 'order_no'
                },
                {
                    text: 'Product name',
                    value: 'product_name'
                },
                {
                    text: 'Client name',
                    value: 'client_name'
                },
                {
                    text: 'Address',
                    value: 'address'
                },
                {
                    text: 'Phone',
                    value: 'phone'
                },
                {
                    text: 'Special instructions',
                    value: 'note'
                },
                {
                    text: 'Total price',
                    value: 'cod_amount'
                },
                {
                    text: 'Actions',
                    value: 'actions'
                },
            ]
        }
    },
    methods: {

        getProducts() {
            var payload = {
                model: 'products',
                update: 'updateProductsList'
            }
            this.$store.dispatch("getItems", payload);

        },
        getStatus() {
            var payload = {
                model: 'api_status',
                update: 'updateApiStatusItemList',
            }
            this.$store.dispatch("getItems", payload);
        },
        getApiStatuses() {
            this.$store.dispatch('getApiStatuses')
        },
        remove(item) {
            const index = this.woocommerce_orders.sales.indexOf(item)
            this.woocommerce_orders.sales.splice(index, 1)
        },
    },
    computed: {
        ...mapState(['products', 'api_status', 'loading']),
        // validate() {
        //     var valid = true
        //     for (let index = 0; index < this.woocommerce_orders.sales.length; index++) {
        //         const element = this.woocommerce_orders.sales[index];

        //         if (!element.product.id || !element.entry.orderid || !element.entry.quantity || !element.entry.address || !element.entry.clientname || !element.entry.phone) {
        //             valid = false
        //             break;
        //         } else {
        //             valid = true
        //             // return true
        //         }
        //     }
        //     return valid;
        // }
    },
    mounted() {
        // this.getApiStatuses();
        this.getProducts();
    },
}
</script>

<style scoped>
.el-collapse-item__header {
    color: #f00 !important;
}
</style>

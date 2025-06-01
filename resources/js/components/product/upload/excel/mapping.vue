<template>
<div>
    <!-- <el-collapse accordion>
        <el-collapse-item name="1" v-if="products.exist_products.length > 0">
            <template slot="title" style="color: red">
                <span><b style="color: red">{{ products.exist_products.length }} Products Exists</b><i class="header-icon el-icon-info"></i></span>
            </template>
            <div>
                <v-list dense two-line>
                    <v-list-item-group color="primary">
                        <v-list-item v-for="(item, i) in products.exist_products" :key="i">
                            <v-list-item-content>
                                <v-list-item-title>{{ item.product_no }} </v-list-item-title>
                            </v-list-item-content>
                        </v-list-item>
                    </v-list-item-group>
                </v-list>
            </div>
        </el-collapse-item>
    </el-collapse> -->
    <v-data-table :headers="headers" :items="products.Products" :single-expand="singleExpand" :expanded.sync="expanded" item-key="id" show-expand class="elevation-1">
        <template v-slot:top>
            <v-toolbar flat>
                <v-toolbar-title>Expandable Table</v-toolbar-title>
                <v-spacer></v-spacer>
                <v-switch v-model="singleExpand" label="Single expand" class="mt-2"></v-switch>
            </v-toolbar>
        </template>
        <template v-slot:expanded-item="{ item }">
            <!-- <tr v-for="(product, index) in products.Products" :key="index">
        </tr> -->

            <table class="table table-responsive table-hover" style="width: 400px;">
                <tbody>
                    <tr v-for="(product, index) in products.Products" :key="index" v-if="product.product_id == item.product_id">
                        <td colspan="1">
                            <el-select v-model="product.product_name" placeholder="Select" filterable clearable>
                                <el-option v-for="(item, prod) in products.data" :key="prod" :label="item.product_name" :value="item.product_name">
                                </el-option>
                            </el-select>
                        </td>
                        <td colspan="1">
                            <el-input-number v-model="product.quantity" :min="1"></el-input-number>
                        </td>
                    </tr>
                </tbody>
            </table>
        </template>
    </v-data-table>
</div>
</template>

<script>
import {
    mapState
} from 'vuex';
export default {
    props: ['products'],
    data() {
        return {
            expanded: [],
            singleExpand: false,
            headers: [{
                    text: 'Product id',
                    value: 'product_id'
                },
                {
                    text: 'Quantity',
                    value: 'Products.quantity'
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
                    value: 'special_instructions'
                },
                {
                    text: 'Total price',
                    value: 'total_amount'
                },
                {
                    text: 'Actions',
                    value: 'actions'
                },
            ]
        }
    },

    methods: {
        getStatus() {
            var payload = {
                model: 'statuses',
                update: 'updateStatusList',
            }
            this.$store.dispatch("getItems", payload);
        },
        remove(item) {
            const index = this.products.products.indexOf(item)

            this.products.products.splice(index, 1)
        },
        // itemRowBackground: function (product) {
        //     console.log(product);

        //     console.log(!product.product.id || !product.entry.product_id || !product.entry.quantity || !product.entry.address || !product.entry.client_name || !product.entry.phone);

        //     return (!product.product.id || !product.entry.product_id || !product.entry.quantity || !product.entry.address || !product.entry.client_name || !product.entry.phone) ? 'style-1' : 'style-2'
        // }
    },
    computed: {
        ...mapState(['statuses']),
        // validate() {
        //     var valid = true
        //     for (let index = 0; index < this.products.products.length; index++) {
        //         const element = this.products.products[index];

        //         if (!element.product.id || !element.entry.product_id || !element.entry.quantity || !element.entry.address || !element.entry.client_name || !element.entry.phone) {
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
        this.getStatus();
    },
}
</script>

<template>
<v-card>
    <v-card-title primary-title>

    </v-card-title>
    <v-card-text v-if="e1 == 2">
        <el-collapse accordion>

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
        </el-collapse>
        <v-data-table :headers="headers" :items="products.products" :search="search" :loading="loading">
            <!-- <v-data-table :headers="headers" :items="products" :search="search" :item-class="itemRowBackground"> -->
            <!-- <div :style="[ (!product.product.id || !product.entry.productid || !product.entry.quantity || !product.entry.address || !product.entry.clientname || !product.entry.phone) ? {'background': '#ff00008a'} : '']"> -->

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

            <template v-slot:item.vendor_id="{ item }">
                <el-select v-model="item.vendor_id" placeholder="Select" filterable clearable>
                    <el-option v-for="data in sellers.data" :key="data.id" :label="data.name" :value="data.id">
                    </el-option>
                </el-select>
            </template>
            <template v-slot:item.warehouse_id="{ item }">
                <el-select v-model="item.warehouse_id" placeholder="Select" filterable clearable>
                    <el-option v-for="data in warehouses" :key="data.id" :label="data.name" :value="data.id">
                    </el-option>
                </el-select>
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
    props: ['products', 'e1'],
    data() {
        return {
            search: '',
            valid: false,
            invalid_row: [],
            headers: [{
                    text: 'Product',
                    value: 'product_name'
                },
                {
                    text: 'Price',
                    value: 'price'
                },
                {
                    text: 'Quantity',
                    value: 'quantity'
                },
                {
                    text: 'Warehouse',
                    value: 'warehouse_id'
                },
                {
                    text: 'Vendor',
                    value: 'vendor_id'
                },
                {
                    text: 'Actions',
                    value: 'actions'
                },
            ]
        }
    },
    methods: {
        remove(item) {
            const index = this.products.products.indexOf(item)
            this.products.products.splice(index, 1)
        },

    },
    computed: {
        ...mapState(['loading', 'statuses', 'warehouses', 'sellers']),
        // validate() {
        //     var valid = true
        //     for (let index = 0; index < this.products.products.length; index++) {
        //         const element = this.products.products[index];

        //         if (!element.product.id || !element.entry.productid || !element.entry.quantity || !element.entry.address || !element.entry.clientname || !element.entry.phone) {
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



}
</script>

<style scoped>
.el-collapse-item__header {
    color: #f00 !important;
}
</style>

<template>
<div style="margin-bottom: 30px;" v-if="product.stock_management == '1'">
    <h3 class="text-center">Inventory tracking</h3>
    <v-divider></v-divider>
    <v-layout row wrap>
        <v-flex xs12 sm12 style="padding: 20px;margin-left: 16px;">
            <label for="">Vendor</label>
            <el-select v-model="product.vendor_id" filterable remote reserve-keyword style="width: 60%" placeholder="type at least 3 characters" :remote-method="getSellers" :loading="loading">
                <el-option v-for="item in sellers.data" :key="item.id" :label="item.name" :value="item.id">
                </el-option>
            </el-select>
        </v-flex>
        <v-flex xs12 sm12 style="padding: 20px;margin-left: 16px;">
            <table class="table table-hover table-striped table-bordered">

                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Warehouse Name</th>
                        <!-- <th scope="col">Special Instructions</th> -->
                        <th scope="col">Quantity</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody v-if="product.stock_management == '2'">
                    <tr v-for="(item, index) in warehouse_arr" :key="index">
                        <th scope="row">{{ index+1 }}</th>
                        <td>
                            <el-select v-model="item.warehouse_id" filterable clearable placeholder="Warehouse" style="width: 100%;">
                                <el-option v-for="item in warehouses" :key="item.id" :label="item.name" :value="item.id">
                                </el-option>
                            </el-select>
                        </td>
                        <td>
                            <el-input v-model="item.onhand"></el-input>
                        </td>
                        <td>
                            <v-tooltip right>
                                <template v-slot:activator="{ on }">
                                    <v-btn icon v-on="on" slot="activator" class="mx-0" @click="remove(index)">
                                        <v-icon color="pink darken-2" small>mdi-delete</v-icon>
                                    </v-btn>
                                </template>
                                <span>Remove</span>
                            </v-tooltip>
                        </td>
                    </tr>
                </tbody>
                <tbody v-else-if="product.stock_management == '1'">
                    <tr v-for="(item, index) in product.inventory" :key="index">
                        <th scope="row">{{ index+1 }}</th>
                        <td>
                            <el-select v-model="item.warehouse_id" filterable clearable placeholder="Warehouse" style="width: 100%;">
                                <el-option v-for="item in warehouses" :key="item.id" :label="item.name" :value="item.id">
                                </el-option>
                            </el-select>
                        </td>
                        <td>
                            <el-input v-model="item.onhand"></el-input>
                        </td>
                        <td>
                            <v-tooltip right>
                                <template v-slot:activator="{ on }">
                                    <v-btn icon v-on="on" slot="activator" class="mx-0" @click="remove(index)">
                                        <v-icon color="pink darken-2" small>mdi-delete</v-icon>
                                    </v-btn>
                                </template>
                                <span>Remove</span>
                            </v-tooltip>
                        </td>
                    </tr>
                </tbody>
            </table>

            <v-btn small color="primary" style="text-transform: none;" rounded @click="add_another">
                <v-icon>mdi-plus</v-icon>
                <span>Add another</span>
            </v-btn>

        </v-flex>
    </v-layout>
</div>
</template>

<script>
import {
    VueEditor
} from "vue2-editor";
import {
    mapState
} from 'vuex';

export default {
    props: ['product', 'warehouse_arr', 'tenant'],
    data() {
        return {}
    },
    methods: {
        add_another() {

            if (this.product.stock_management == '1') {
                this.product.inventory.push({
                    warehouse_id: '',
                    onhand: 0,
                    price: 0,
                })
            } else {
                this.warehouse_arr.push({
                    warehouse_id: '',
                    onhand: 0,
                    price: 0,
                })
            }


        },
        remove(index) {
            this.warehouse_arr.splice(index, 1)

        },
        getSellers(search) {
            // console.log(search);
            if (search.length > 2) {
                var payload = {
                    update: "updateSellerList",
                    model: '/seller/seller_search',
                    search: search
                }
                this.$store.dispatch("searchItems", payload);
            }
        }
    },
    mounted() {},
    computed: {
        ...mapState(['warehouses', 'sellers', 'loading'])
    },
}
</script>

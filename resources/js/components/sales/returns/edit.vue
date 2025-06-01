<template>
<div>
    <v-layout justify-center align-center wrap>
        <v-card class="mx-auto overflow-hidden">
            <!-- <v-app-bar color="#1564c0" dark>
                <v-toolbar-title>New Sales Return</v-toolbar-title>
                <v-spacer></v-spacer>
                <v-btn text icon small color="white" @click="close">
                    <v-icon>mdi-close</v-icon>
                </v-btn>
            </v-app-bar> -->

            <v-card-text>
                <v-layout row wrap>
                    <v-flex sm4 offset-sm1>
                        <label for="">RMA</label>
                        <el-input placeholder="Please input" v-model="sale_return.rma"></el-input>
                    </v-flex>
                    <v-flex sm4 offset-sm1>
                        <label for="">Date</label>
                        <el-date-picker format="yyyy/MM/dd" value-format="yyyy-MM-dd" v-model="sale_return.return_date" type="date" placeholder="Pick a day" style="width: 100%">
                        </el-date-picker>
                    </v-flex>

                    <v-flex sm4 offset-sm1>
                        <label for="">Warehouse Name</label>
                        <el-select v-model="saleorder.warehouse_id" filterable clearable placeholder="Warehouse" style="width: 100%;">
                            <el-option v-for="item in warehouses" :key="item.id" :label="item.name" :value="item.id">
                            </el-option>
                        </el-select>
                    </v-flex>
                    <v-flex xs4 sm4 offset-sm1>
                        <label for="">Reason</label>
                        <el-input type="textarea" autosize placeholder="Please input" v-model="sale_return.comment">
                        </el-input>
                    </v-flex>

                    <v-flex sm10 offset-sm1 style="margin-top: 30px">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Items & Description</th>
                                    <th>Shipped</th>
                                    <th>Returned</th>
                                    <th>Return Quantity </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="item in saleorder.products" :key="item.id">
                                    <td>{{ item.product_name }}</td>
                                    <td>{{ item.pivot.quantity_sent }}</td>
                                    <td>{{ item.pivot.quantity_returned }}</td>
                                    <td>
                                        <el-input placeholder="Please input" v-model="sale_return.returned"></el-input>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </v-flex>
                </v-layout>
            </v-card-text>

            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="primary" rounded @click="save">
                    <v-icon>mdi-content-save</v-icon>
                    <span>Save</span>
                </v-btn>
                <v-btn color="primary" rounded>
                    <v-icon>mdi-close-circle</v-icon>
                    <span>Cancel</span>
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-layout>
</div>
</template>

<script>
import {
    mapState
} from 'vuex'
export default {
    data: () => ({
        // form: {
        //     returned: 0
        // },
        form: {}
    }),

    methods: {
        close() {
            eventBus.$emit('closeReturnSaleEvent')
        },
        save() {
            this.form.sale_id = this.saleorder.id
            this.form.warehouse_id = this.saleorder.warehouse_id
            var payload = {
                data: this.sale_return,
                id: this.sale_return.id,
                model: 'returns',
            }
            this.$store.dispatch('patchItems', payload)
                .then(response => {});
        },
        getSaleReturn() {
            var payload = {
                model: 'returns',
                update: 'updateSaleReturn',
                id: this.$route.params.id
            }
            this.$store.dispatch('showItem', payload).then((res) => {
                this.getOrder(res.data.sales.id)
                // this.products_arr = this.saleorder.products
            })
        },
        getOrder(id) {
            var payload = {
                model: 'sales',
                update: 'updateSale',
                id: id
            }
            this.$store.dispatch('showItem', payload).then((res) => {
                // this.products_arr = this.saleorder.products
            })
        },
        getWarehouses() {
            var payload = {
                model: '/warehouses',
                update: 'updateWarehouseList'
            }
            this.$store.dispatch("getItems", payload);
        },
    },

    mounted() {
        this.getSaleReturn()
        this.getWarehouses()
    },

    computed: {
        ...mapState(['warehouses', 'saleorder', 'sale_return']),
    }
}
</script>

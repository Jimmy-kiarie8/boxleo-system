<template>
        <v-dialog v-model="dialog" width="800">
            <v-card class="mb-12" elevation="3">
                <v-card-title primary-title>
                    Shipment
                </v-card-title>
                <v-card-text>
                    <br>
                    <v-layout row wrap>
                        <v-flex sm6>
                            <label for="">Waybill No.</label>
                            <el-input placeholder="Waybill No" v-model="form.waybill_no"></el-input>
                        </v-flex>
                        <v-flex sm6>
                            <label for="">Weight</label>
                            <el-input placeholder="Weight" v-model="form.weight"></el-input>
                        </v-flex>
                        <v-flex sm6>
                            <label for="">Amount</label>
                            <el-input placeholder="Amount" v-model="form.amount"></el-input>
                        </v-flex>
                        <v-flex sm6>
                            <label for="">Arrival date</label>
                            <el-date-picker v-model="form.arrival_date" type="date" placeholder="Pick a Date"
                                format="yyyy/MM/dd" value-format="yyyy-MM-dd" style="width:100%">
                            </el-date-picker>
                        </v-flex>

                        <v-flex sm6>
                            <label for="">Warehouse</label>
                            <el-select v-model="form.warehouse_id" placeholder="Select" style="width: 100%">
                                <el-option v-for="item in warehouses" :key="item.id" :label="item.name"
                                    :value="item.id">
                                </el-option>
                            </el-select>
                        </v-flex>

                        <v-flex sm6 v-if="!user.is_vendor">
                            <label for="">Vendor</label>
                            <el-select v-model="form.seller_id" placeholder="Select" @change="getProducts"
                                style="width:100%" clearable filterable>
                                <el-option v-for="item in sellers.data" :key="item.id" :label="item.name"
                                    :value="item.id"></el-option>
                            </el-select>
                        </v-flex>

                        <v-flex sm12>
                            <label for="">Product</label>
                            <el-input placeholder="Description" v-model="form.description"></el-input>

                            <!-- <el-select v-model="form.products" placeholder="Select" multiple clearable filterable style="width:100%" value-key="id">
                        <el-option v-for="item in products" :key="item.id" :label="item.product_name" :value="item"></el-option>
                    </el-select> -->
                        </v-flex>
                    </v-layout>
                </v-card-text>
                <v-card-actions>
                    <v-btn color="primary" @click="submit">
                        Submit
                    </v-btn>

                </v-card-actions>
            </v-card>

        </v-dialog>
</template>

<script>
import {
    mapState
} from 'vuex';
export default {
    props: ['user'],
    data() {
        return {
            e6: 1,
            search: '',
            item: {
                value: null,
            },
            form: {},
            form_id: null,
            copy_val: '',
            dialog: false,
            headers: [{
                text: 'Product name',
                value: 'product_name',
            },
            {
                text: 'Quantity',
                value: 'quantity',
            },
            {
                text: 'Price/Kg',
                value: 'price',
            },
            {
                text: 'Weight(KGS)',
                value: 'weight',
            },
            {
                text: 'Clearing company',
                value: 'clearing_company',
            },
            {
                text: 'Actions',
                value: 'actions',
            },
            ],
            columns: [{
                text: 'Product name',
                value: 'product_name',
            },
            {
                text: 'Quantity',
                value: 'quantity',
            },
            {
                text: 'Position',
                value: 'position',
            },
            {
                text: 'Actions',
                value: 'actions',
            },
            ]
        }
    },

    methods: {
        submit() {
            var payload = {
                model: 'shipment-request',
                data: this.form
            }
            this.$store.dispatch('postItems', payload)
                .then(response => {
                    console.log("🚀 ~ file: create.vue:159 ~ submit ~ response:", response)
                    this.form = {}
                    this.e6 = 1
                    // eventBus.$emit("warehouseEvent")
                });
        },
        getWarehouses() {
            var payload = {
                model: 'warehouses',
                update: 'updateWarehouseList',
            }
            this.$store.dispatch('getItems', payload);
        },
        getVendors() {
            var payload = {
                model: 'sellers',
                update: 'updateSellerList',
            }
            this.$store.dispatch('getItems', payload);
        },
        getProducts(id) {
            // var payload = {
            //     model: 'vendor_product',
            //     update: 'updateProductsList',
            //     id: id
            // }
            // this.$store.dispatch('showItem', payload);
        },


        goTo2() {
            this.e6 = 2
        },
        goTo3() {
            this.e6 = 3
        },

        bin_check() {
            var payload = {
                model: 'bins_check',
                update: 'updateBin',
                data: this.form
            }
            this.$store.dispatch('postItems', payload);
        }
    },

    computed: {
        ...mapState(['sellers', 'products', 'warehouses'])
    },

    mounted() {
        this.getVendors();
        this.getWarehouses();
    },
    created() {
        eventBus.$on('CreateShipmentEvent', () => {
            this.dialog = true;
            setTimeout(() => {
                if (this.user.is_vendor) {
                    this.getProducts(this.user.id);
                }
            }, 500);
        });
    },
}
</script>
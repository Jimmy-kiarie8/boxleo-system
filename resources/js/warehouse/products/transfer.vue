<template>
<v-row justify="center">
    <v-dialog v-model="dialog" persistent max-width="400px">
        <v-card>
            <v-card-title>
                <span class="headline">Product Transfer</span>
            </v-card-title>
            <v-card-text>
                <v-container>
                    <el-select v-model="form.transfer_type" placeholder="Select" style="width: 100%;">
                        <el-option v-for="item in transfers" :key="item.value" :label="item.label" :value="item.value"></el-option>
                    </el-select>
                    <v-row>
                        <v-col cols="12" sm="12" md="12">
                            <label for="">Product</label>
                            <el-input placeholder="Please input" v-model="form.product.product_name" disabled></el-input>
                        </v-col>
                        <v-col cols="12" sm="6" md="6">
                            <label for="">Bin from</label>
                            <el-select v-model="form.bin_id" placeholder="Select" style="width: 100%" @change="getBinQty" clearable filterable>
                                <el-option v-for="item in bin" :key="item.id" :label="item.code" :value="item.id"></el-option>
                            </el-select>
                        </v-col>
                        <v-col cols="12" sm="6" md="6">
                            <label for="">Bin to</label>
                            <el-select v-model="form.bin_to" filterable placeholder="Please enter a keyword" :loading="loading">
                                <el-option v-for="item in bins" :key="item.value" :label="item.code" :value="item.id"></el-option>
                            </el-select>
                        </v-col>


                        <v-col cols="12" sm="6" md="6" v-if="form.transfer_type == 'merchant'">
                            <label for="">Merchant To</label>
                            <el-select v-model="form.merchantTo" filterable placeholder="type at least 3 characters" :loading="loading" style="width: 100%;" @change="getProduct($event)">
                                <el-option v-for="item in sellers.data" :key="item.id" :label="item.name" :value="item.id">
                                </el-option>
                            </el-select>
                        </v-col>


                        <v-col cols="12" sm="6" md="6" v-if="form.transfer_type == 'merchant'">
                            <label for="">Products</label>
                            <el-select v-model="form.productId" filterable placeholder="type at least 3 characters" :loading="loading" style="width: 100%;">
                                <el-option v-for="item in products" :key="item.id" :label="item.product_name" :value="item.id">
                                </el-option>
                            </el-select>
                        </v-col>
                        <v-col cols="12" sm="6" md="6">
                            <label for="">Quantity Available</label>
                            <el-input placeholder="Please input" v-model="form.onhand" disabled></el-input>
                        </v-col>
                        <v-col cols="12" sm="6" md="6">
                            <label for="">Quantity to transfer</label>
                            <el-input placeholder="Please input" v-model="form.qty_transfer"></el-input>
                        </v-col>
                    </v-row>
                </v-container>
            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="blue darken-1" text @click="dialog = false">
                    Close
                </v-btn>
                <v-btn color="blue darken-1" text @click="save">
                    Save
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</v-row>
</template>

<script>
import {
    mapState
} from 'vuex'
export default {
    data: () => ({
        dialog: false,
        loading: false,
        products: null,
        form: {
            onhand: null,
            product: {},
            transfer_type: 'Bin To Bin',
            merchantTo: null,
            productId: null
        },

        transfers: [
            {
                label: 'Bin To Bin',
                value: 'bin'
            }, 
            {
                label: 'Merchant To Merchant',
                value: 'merchant'
            } 
        ]
    }),
    methods: {
        getBins(id) {
            var payload = {
                model: 'product_bins',
                update: 'updateBin',
                id: id
            }
            this.$store.dispatch('showItem', payload);
        },
        getAllBins() {
            var payload = {
                model: 'transfer_bin',
                update: 'updateBins'
            }
            this.$store.dispatch('getItems', payload);
        },

        getBinQty(id) {
            var data = {
                'product_id': this.form.product_id,
                'bin_id': id
            }
            axios.post('product_quantity', data).then((response) => {
                this.form.onhand = response.data.onhand
            })
        },

        getProduct(id) {
            this.form.productId = null;

            axios.get(`vendor_product/${id}`).then((response) => {
                this.products = response.data
            })
        },

        save() {
            // this.form.product_id = this.product_id
            var payload = {
                model: 'stock_transfer',
                data: this.form,
            }
            this.$store.dispatch('postItems', payload)
                .then(response => {
                    // eventBus.$emit("productEvent")
                });
        },

        searchSeller(search) {
            // console.log(search);
            if (search.length > 2) {
                var payload = {
                    model: 'seller_search',
                    update: 'updateSellerList',
                    search: search
                }
                this.$store.dispatch("searchItems", payload);
            }
        },
    },
    created() {
        eventBus.$on('productTransferEvent', data => {
            this.dialog = true
            this.form.product_id = data.id
            this.form.product = data
            this.getBins(data.id)
            this.getAllBins();
        });
    },

    computed: {
        ...mapState(['bins', 'bin', 'sellers'])
    },
}
</script>

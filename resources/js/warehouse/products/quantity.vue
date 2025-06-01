<template>
<v-layout row justify-center>
    <v-dialog v-model="dialog" persistent max-width="600px">
        <v-card>
            <v-card>
                <!-- <v-card v-for="(warehouse, index) in form.warehouses" :key="index"> -->
                <v-card-title>
                    <!-- <span class="headline text-center" style="margin: auto;">{{ warehouse.name }}</span> -->
                </v-card-title>
                <v-divider></v-divider>
                <v-card-text>
                    <v-container grid-list-md>
                        <v-layout row wrap>
                            <!-- <v-flex sm12>
                                <label for="">Warehouse</label>
                                <el-input placeholder="Quantity" :value="warehouse.name" disabled></el-input>
                            </v-flex> -->

                            <v-flex sm12>
                                <el-select v-model="form.warehouse_id" filterable clearable placeholder="Warehouse" style="width: 100%;" @change="getBins">
                                    <el-option v-for="item in warehouses" :key="item.id" :label="item.name" :value="item.id">
                                    </el-option>
                                </el-select>
                            </v-flex>

                            <v-flex sm12 v-if="form.warehouse_id">
                                <el-select v-model="form.code" filterable clearable placeholder="Bin" style="width: 100%;" @change="getBinQty">
                                    <el-option v-for="item in bins" :key="item.id" :label="item.code" :value="item.id">
                                    </el-option>
                                </el-select>
                            </v-flex>
                            <v-flex sm12>
                                <label for="">Product name</label>
                                <el-input placeholder="Product name" v-model="form.name" disabled></el-input>
                            </v-flex>
                            <v-flex sm12>
                                <label for="">Available Quantity</label>
                                <el-input placeholder="Quantity" v-model="form.pivot.available_for_sale" disabled></el-input>
                            </v-flex>
                            <v-flex sm12>
                                <label for="">Add Quantity</label>
                                <el-input placeholder="Quantity" v-model="form.add_qty"></el-input>
                            </v-flex>
                            <v-flex sm12>
                                <label for="">New Quantity</label>
                                <el-input placeholder="Quantity" :value="parseInt(form.pivot.available_for_sale) + parseInt(form.add_qty)" disabled></el-input>
                            </v-flex>
                            <v-flex sm12>
                                <label for="">Quantity Onhand</label>
                                <el-input placeholder="Quantity" v-model="form.pivot.onhand"></el-input>
                            </v-flex>
                            <v-flex sm12>
                                <label for="">Quantity commited(Dispatched)</label>
                                <el-input placeholder="Quantity" v-model="form.pivot.commited"></el-input>
                            </v-flex>
                        </v-layout>
                    </v-container>
                </v-card-text>
            </v-card>
            <v-card-actions>
                <v-btn color="blue darken-1" text @click="close">Close</v-btn>
                <v-spacer></v-spacer>
                <v-btn color="blue darken-1" text @click="save" :loading="loading" :disabled="loading">Update</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</v-layout>
</template>

<script>
import {
    mapState
} from 'vuex';
export default {
    props: ['product_id'],
    name: 'stock_quantity',
    data: () => ({
        dialog: false,
        form: {
            // onhand: null
        },
        items: {},
    }),
    created() {
        eventBus.$on("updateQtyEvent", data => {
            this.dialog = true;
            this.form = data
            this.form.add_qty = 0
            this.getBins(data.warehouse_id);
            if (this.warehouses.length < 1) {
                this.getWarehouses()
            }
        });
    },

    methods: {
        save() {
            this.form.product_id = this.product_id

            this.form.update = 'single';
            var payload = {
                model: 'stock_update',
                data: this.form,
            }
            this.$store.dispatch('postItems', payload)
                .then(response => {
                    eventBus.$emit("productEvent")
                });
        },
        close() {
            this.dialog = false;
        },
        getWarehouses() {
            var payload = {
                model: '/warehouses',
                update: 'updateWarehouseList',
            }
            this.$store.dispatch("getItems", payload);
        },
        getBins(id) {
            var payload = {
                model: '/warehouse_bin',
                update: 'updateBins',
                id: id
            }
            this.$store.dispatch("showItem", payload);
        },
        getBinQty(id) {
            var data = {
                'product_id': this.product_id,
                'bin_id': id
            }
            axios.post('product_quantity', data).then((response) => {
                this.form.onhand = response.data.onhand
            })
        }
    },
    computed: {
        ...mapState(['loading', 'warehouses', 'bins']),
        new_qty() {
            return 0
        },
    },
};
</script>

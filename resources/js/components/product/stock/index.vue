<template>
<v-layout row justify-center>
    <v-dialog v-model="dialog" persistent max-width="600px">
        <v-card v-if="form.bins.length > 0 && form.stock_management==2">
            <v-card v-for="(bin, index) in form.bins" :key="index">
                <v-card-title>
                    <span class="headline text-center" style="margin: auto;">{{ bin.name }}</span>
                </v-card-title>
                <v-divider></v-divider>
                <v-card-text>
                    <v-container grid-list-md>
                        <v-layout row wrap>
                            <v-flex sm12>
                                <label for="">Bin</label>
                                <el-input placeholder="Quantity" :value="bin.code" disabled></el-input>
                            </v-flex>

                            <!-- <v-flex sm12>
                                <el-select v-model="bin.id" filterable clearable placeholder="Warehouse" style="width: 100%;">
                                    <el-option v-for="item in warehouses" :key="item.id" :label="item.name" :value="item.id">
                                    </el-option>
                                </el-select>
                            </v-flex> -->
                            <v-flex sm12>
                                <label for="">Product name</label>
                                <el-input placeholder="Product name" v-model="form.product_name"></el-input>
                            </v-flex>
                            <v-flex sm12>
                                <label for="">Products Available in the bin</label>
                                <el-input placeholder="Quantity" v-model="bin.pivot.available_for_sale"></el-input>
                            </v-flex>
                            <v-flex sm12>
                                <label for="">Add New Quantity</label>
                                <el-input placeholder="Quantity" v-model="bin.add_qty"></el-input>
                            </v-flex>
                            <v-flex sm12>
                                <label for="">New Quantity</label>
                                <el-input placeholder="Quantity" :value="parseInt(bin.pivot.available_for_sale) + parseInt(bin.add_qty)" disabled></el-input>
                            </v-flex>

                            <v-flex sm12>
                                <label for="">Quantity Onhand</label>
                                <el-input placeholder="Onhand" v-model="bin.pivot.onhand"></el-input>
                            </v-flex>
                            <v-flex sm12>
                                <label for="">Quantity Commited(Dispatched)</label>
                                <el-input placeholder="Commited" v-model="bin.pivot.commited"></el-input>
                            </v-flex>
                            <v-flex sm12>
                                <label for="">Quantity Delivered</label>
                                <el-input placeholder="Delivered" v-model="bin.pivot.delivered"></el-input>
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


        <v-card v-else-if="form.inventory.length > 0 && form.stock_management==1">
            <v-card v-for="(inv, index) in form.inventory" :key="index">
                <v-card-title>
                    <span class="headline text-center" style="margin: auto;">{{ inv.name }}</span>
                </v-card-title>
                <v-divider></v-divider>
                <v-card-text>
                    <v-container grid-list-md>
                        <v-layout row wrap>
                            <v-flex sm12>
                                <label for="">Warehouse</label>
                                <el-input placeholder="Quantity" :value="inv.warehouse_id" disabled></el-input>
                            </v-flex>

                            <!-- <v-flex sm12>
                                <el-select v-model="inv.id" filterable clearable placeholder="Warehouse" style="width: 100%;">
                                    <el-option v-for="item in warehouses" :key="item.id" :label="item.name" :value="item.id">
                                    </el-option>
                                </el-select>
                            </v-flex> -->
                            <v-flex sm12>
                                <label for="">Product name</label>
                                <el-input placeholder="Product name" v-model="form.product_name"></el-input>
                            </v-flex>
                            <v-flex sm12>
                                <label for="">Add New Quantity</label>
                                <el-input placeholder="Quantity" v-model="inv.add_qty"></el-input>
                            </v-flex>
                            <v-flex sm12>
                                <label for="">New Available Quantity</label>
                                <el-input placeholder="Quantity" :value="parseInt(inv.available_for_sale) + parseInt(inv.add_qty)" disabled></el-input>
                            </v-flex>

                            <v-flex sm12>
                                <label for="">Quantity Onhand</label>
                                <el-input placeholder="Onhand"  :value="parseInt(inv.onhand)" disabled></el-input>
                            </v-flex>
                            <v-flex sm12>
                                <label for="">Quantity Commited(Dispatched)</label>
                                <el-input placeholder="Commited" v-model="inv.commited"></el-input>
                            </v-flex>
                            <v-flex sm12>
                                <label for="">Quantity Delivered</label>
                                <el-input placeholder="Delivered" v-model="inv.delivered"></el-input>
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



        <v-hover style="padding-top: 30px" v-else>
            <v-card slot-scope="{ hover }" :class="`elevation-${hover ? 12 : 2}`" class="mx-auto" width="100%">
                <div class="text-center">
                    <h3>Products not available in the warehouse</h3>
                    <v-divider></v-divider>
                    <p style="color: #000">Want to start tracking this product in the warehouse?</p>
                    <small>Go to warehouse module and update the item position</small>
                </div>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="blue darken-1" text @click="close">Close</v-btn>
                </v-card-actions>
            </v-card>
        </v-hover>

    </v-dialog>
</v-layout>
</template>

<script>
import {
    mapState
} from 'vuex';
export default {
    name: 'stock',
    data: () => ({
        dialog: false,
        form: {},
    }),
    created() {
        eventBus.$on("updateQtyEvent", data => {
            this.dialog = true;
            this.form = data
            this.getWarehouses()
        });
    },

    methods: {
        save() {
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
                update: 'updateWarehouseList'
            }
            this.$store.dispatch("getItems", payload);
        },
    },
    computed: {
        ...mapState(['loading', 'warehouses']),
        new_qty() {
            return 0
        }
    },
};
</script>

<template>
<div>
    <div>

        <!-- <v-container fluid fill-height> -->
        <v-layout justify-center wrap align-center>
            <v-layout wrap>
                <v-flex xs12 sm7 style="margin: 0 10px">
                    <v-layout wrap>
                        <v-flex xs12 sm12>

                            <v-hover style="padding: 20px 20px 20px 0">
                                <v-card slot-scope="{ hover }" :class="`elevation-${hover ? 12 : 2}`" class="mx-auto" width="100%">
                                    <v-layout wrap>
                                        <v-flex xs12 sm11 offset-sm1 style="padding: 7px 0;">
                                            <h4>New Adjustment</h4>
                                        </v-flex>
                                        <v-divider></v-divider>
                                        <v-flex xs12 sm5 offset-sm1 style="padding: 7px 0;">
                                            <label for="" style="color: #52627d;">Reference Number</label>
                                            <el-input placeholder="John doe" v-model="form.reference_no"></el-input>
                                        </v-flex>

                                        <v-flex xs4 sm5 offset-sm1 style="padding: 7px 0;">
                                            <label for="" style="color: #52627d;">Reason*</label>
                                            <el-select v-model="form.reason" filterable remote reserve-keyword placeholder="type at least 3 characters" :remote-method="getSellers" :loading="loading" style="width: 100%;">
                                                <el-option v-for="item in sellers.data" :key="item.id" :label="item.name" :value="item.id">
                                                </el-option>
                                            </el-select>
                                        </v-flex>
                                        <v-flex xs4 sm5 offset-sm1 style="padding: 7px 0;">
                                            <label for="">Warehouse*</label>
                                            <el-select v-model="form.warehouse_id" filterable clearable placeholder="Warehouse" style="width: 100%;">
                                                <el-option v-for="item in warehouses" :key="item.id" :label="item.name" :value="item.id">
                                                </el-option>
                                            </el-select>
                                        </v-flex>
                                        <v-flex sm11 offset-sm1 >
                                            <label for="">Comment*</label>
                                            <el-input type="textarea" placeholder="Please input" v-model="form.customer_notes" maxlength="200" show-word-limit></el-input>
                                        </v-flex>
                                    </v-layout>
                                </v-card>
                            </v-hover>
                        </v-flex>
                    </v-layout>
                </v-flex>
                <v-flex xs12 sm3 offset-sm1>
                    <v-card :color="color">
                        <v-card-title>
                            Heads up!
                        </v-card-title>
                        <v-card-text>
                            <p>Tell us about your product. The unique barcode will help us scan and identify the product.</p>
                            <br>
                            <p>Once you set the re-order point, we will notify you when the on-hand level matches the re-order point, so you can send us more inventory!</p>
                        </v-card-text>
                    </v-card>
                </v-flex>
            </v-layout>
            <div style="margin: 50px 0 0 0"></div>

            <v-hover style="padding: 20px 20px 20px 0; margin: 0 10px" v-if="form.warehouse_id">
                <v-card slot-scope="{ hover }" :class="`elevation-${hover ? 12 : 2}`" class="mx-auto" width="100%">
                    <h4 style="text-align: center"> Product Details</h4>
                    <v-layout wrap>
                        <v-flex xs12 sm11 style="margin-left: 75px">
                            <table class="table table-hover table-striped table-bordered">

                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Item Details</th>
                                        <!-- <th scope="col">Special Instructions</th> -->
                                        <th scope="col">Available Quantity</th>
                                        <th scope="col">New Available Quantity</th>
                                        <th scope="col">Quantity adjustment</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(product, index) in form.products_arr" :key="index">
                                        <th scope="row">{{ index+1 }}</th>
                                        <td>
                                            {{ product.product_name }}
                                        </td>
                                        <td>
                                            <el-input v-model="product.onhand"></el-input>
                                        </td>

                                        <td>
                                            <el-input v-model="product.new_onhand"></el-input>
                                        </td>
                                        <td>
                                            {{ parseInt(product.new_onhand) - parseInt(product.onhand) }}

                                            <!-- <el-input v-model="product.adjustment"></el-input> -->
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

                            <v-btn small color="primary" style="text-transform: none;" rounded @click="openDialog">
                                <v-icon>mdi-plus</v-icon>
                                <span>Add another</span>
                            </v-btn>
                        </v-flex>
                        <v-flex xs12 sm6 style="margin: 30px 0 0 75px;">

                            <label for="">Comment</label>
                            <el-input type="textarea" placeholder="Please input" v-model="form.comment" maxlength="200" show-word-limit>
                            </el-input>
                        </v-flex>
                    </v-layout>

                </v-card>

            </v-hover>

            <v-footer style="background: #e2e0e0 !important;" app>
                <v-spacer></v-spacer>
                <v-btn color="primary" style="text-transform: none;" rounded @click="update">
                    <v-icon>mdi-update</v-icon>
                    <span>Update</span>
                </v-btn>

                <v-btn color="primary" style="text-transform: none;" rounded @click="goToSales">
                    <v-icon>mdi-close-circle</v-icon>
                    <span>Close</span>
                </v-btn>
            </v-footer>
        </v-layout>
        <!-- </v-container> -->
    </div>

    <el-dialog title="Items" :visible.sync="dialog" width="30%" :before-close="close">
        <el-select v-model="product_item" filterable remote reserve-keyword placeholder="type at least 3 characters" :remote-method="getProduct" :loading="loading" style="width: 100%;">
            <el-option v-for="item in products.data" :key="item.id" :label="item.product_name" :value-key="item.id" :value="item">
            </el-option>
        </el-select>
        <div v-if="Object.keys(this.product_item).length > 0">
            <label for="" style="color: #52627d;">Quantity</label>
            <el-input placeholder="1" v-model="product_item.quantity"></el-input>
        </div>
        <span slot="footer" class="dialog-footer">
            <el-button @click="dialog = false">Close</el-button>
            <el-button type="primary" @click="add_another('continue')">Save & continue adding</el-button>
            <el-button type="primary" @click="add_another('close')">Save & close</el-button>
        </span>
    </el-dialog>
</div>
</template>

<script>
import {
    mapState
} from 'vuex'
export default {
    name: 'adjustment',
    components: {},
    data: () => ({
        color: '#f0f0f0',
        hasvariant: 'no_variant',
        loading: false,
        dialog: false,
        form: {
            products_arr: [],
            shipping_charges: 0,
            adjustment: 0
        },
        dialog: false,
        product_item: [],
    }),

    computed: {
        ...mapState(['warehouses', 'statuses', 'sellers', 'products']),
    },
    methods: {
        goToSales() {
            this.$router.push({
                name: "sales",
            });
        },
        update() {
            this.form.sub_total = this.sub_total
            this.form.total = this.total
            var payload = {
                data: this.form,
                model: 'sales',
            }
            this.$store.dispatch('postItems', payload)
                .then(response => {
                    // this.goToSales()
                });
        },
        getSellers() {
            var payload = {
                model: "/seller/sellers",
                update: "updateSellerList"
            };
            this.$store.dispatch("getItems", payload);
        },

        getProduct(search) {
            // console.log(search);
            if (search.length > 2) {
                var payload = {
                    model: 'product_search',
                    update: 'updateProductsList',
                    search: search
                }
                this.$store.dispatch("searchItems", payload);
            }
        },
        openDialog() {
            this.dialog = true
        },
        add_another(option) {
            // console.log(Object.keys(this.product_item).length);

            if (Object.keys(this.product_item).length < 1) {
                this.$message({
                    type: 'error',
                    message: 'Please select an item'
                });
                return
            }
            var exists = false
            this.form.products_arr.forEach(element => {
                if (this.product_item.id == element.id) {
                    exists = true
                }
            });
            if (!exists) {
                // console.log(this.product_item);

                for (let i = 0; i < this.product_item.warehouses.length; i++) {
                    const element = this.product_item.warehouses[i];
                    console.log(element.pivot.onhand, this.form.warehouse_id,  element.id);

                    if (this.form.warehouse_id == element.id) {
                        this.product_item.onhand = element.pivot.onhand
                    }

                }

                this.form.products_arr.push(this.product_item)
                this.product_item = {}
                if (option == 'close') {
                    this.close()
                }

            } else {
                this.$message({
                    type: 'error',
                    message: 'Item already exists in the list'
                });
            }
        },
        remove(index) {
            this.form.products_arr.splice(index, 1)

        },
        close() {
            this.dialog = false
        },
        getWarehouses() {
            var payload = {
                model: '/warehouses',
                update: 'updateWarehouseList'
            }
            this.$store.dispatch("getItems", payload);
        },
    },
    created() {},
    mounted() {
        this.getSellers()
        this.getWarehouses()
    },

};
</script>

<style scoped>
.icon-image[data-v-1bd51565]:before {
    content: "\E920";
    font-size: 100px;
}

.image-flex-wrapper[data-v-1bd51565] {
    position: relative;
    margin-right: 1em;
    text-align: center;
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-orient: vertical;
    -webkit-box-direction: normal;
    -ms-flex-direction: column;
    flex-direction: column;
}

.image-flex-wrapper .image-wrapper[data-v-1bd51565] {
    padding: 0.5em;
    border-radius: 6px;
    -webkit-box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, 0.25);
    box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, 0.25);
    border: none;
    height: 10em;
    width: 10em;
}

.icon-image[data-v-1bd51565] {
    background-color: #f9fafb;
    font-size: 5em;
    color: rgba(0, 0, 0, 0.21);
    height: 100%;
    width: 100%;
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-pack: center;
    -ms-flex-pack: center;
    justify-content: center;
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
    border-radius: 6px;
}

[class^="icon-"],
[class*=" icon-"] {
    font-family: "Speedball-App" !important;
    font-style: normal;
    font-weight: normal;
    font-variant: normal;
    text-transform: none;
    line-height: 1;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}

[class^="icon-"],
[class*=" icon-"] {
    font-family: "icomoon" !important;
    font-style: normal;
    font-weight: normal;
    font-variant: normal;
    text-transform: none;
    line-height: 1;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}

[class*=" icon-"],
[class^="icon-"] {
    font-family: icomoon !important;
    font-style: normal;
    font-weight: 400;
    font-variant: normal;
    text-transform: none;
    line-height: 1;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}
</style>

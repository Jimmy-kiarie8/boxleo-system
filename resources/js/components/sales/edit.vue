<template>
<div>
    <div>

        <!-- <v-container fluid fill-height> -->
        <v-layout justify-center wrap align-center style="margin-bottom: 80px;">
            <v-layout wrap>
                <v-flex xs12 sm7 style="margin: 0 10px">
                    <v-layout wrap>
                        <v-flex xs12 sm12>

                            <v-hover style="padding: 20px 20px 20px 0">
                                <v-card slot-scope="{ hover }" :class="`elevation-${hover ? 12 : 2}`" class="mx-auto" width="100%">
                                    <v-layout wrap>

                                        <el-select v-model="saleorder.client" filterable remote reserve-keyword placeholder="type at least 3 characters" :remote-method="getProduct" :loading="loading" style="width: 100%;" value-key="id">
                                            <el-option v-for="item in clients.data" :key="item.id" :label="item.name" :value="item">
                                            </el-option>

                                            <div slot="empty">
                                                <el-button type="text" @click="openCreate">Add new</el-button>
                                            </div>
                                        </el-select>

                                        <v-flex xs12 sm11 offset-sm1 style="padding: 7px 0;">
                                            <h4>Customer Details</h4>
                                        </v-flex>
                                        <v-divider></v-divider>
                                        <v-flex xs12 sm5 offset-sm1 style="padding: 7px 0;">
                                            <label for="" style="color: #52627d;">Customer name</label>
                                            <el-input placeholder="John doe" v-model="saleorder.client.name"></el-input>
                                        </v-flex>
                                        <v-flex xs12 sm5 offset-sm1 style="padding: 7px 0;">
                                            <label for="" style="color: #52627d;">Customer email</label>
                                            <el-input placeholder="doe@gmail.com" v-model="saleorder.client.email"></el-input>
                                        </v-flex>
                                        <v-flex xs12 sm5 offset-sm1 style="padding: 7px 0;">
                                            <label for="" style="color: #52627d;">Customer phone</label>
                                            <el-input placeholder="+1..." v-model="saleorder.client.phone"></el-input>
                                        </v-flex>
                                        <v-flex xs12 sm5 offset-sm1 style="padding: 7px 0;">
                                            <label for="" style="color: #52627d;">Customer address</label>
                                            <el-input placeholder="123 main st" v-model="saleorder.client.address"></el-input>
                                        </v-flex>
                                    </v-layout>
                                </v-card>
                            </v-hover>
                        </v-flex>

                        <v-flex xs12 sm12>
                            <v-hover style="padding: 20px 20px 20px 0">
                                <v-card slot-scope="{ hover }" :class="`elevation-${hover ? 12 : 2}`" class="mx-auto" width="100%">
                                    <v-layout wrap>
                                        <v-flex xs12 sm11 offset-sm1 style="padding: 7px 0;">
                                            <h4>Order Details</h4>
                                        </v-flex>
                                        <v-flex xs12 sm5 offset-sm1 style="padding: 7px 0;">
                                            <label for="" style="color: #52627d;">Sale Order</label>
                                            <el-input placeholder="Order Number" min="0" v-model="saleorder.order_no"></el-input>
                                        </v-flex>
                                        <v-flex xs12 sm5 offset-sm1 style="padding: 7px 0;">
                                            <label for="" style="color: #52627d;">Sales Order Date</label>
                                            <el-date-picker format="yyyy/MM/dd" value-format="yyyy-MM-dd" style="width: 100%" v-model="saleorder.created_at" type="date" placeholder="Pick a day"></el-date-picker>
                                        </v-flex>
                                        <v-flex xs12 sm5 offset-sm1 style="padding: 7px 0;">
                                            <label for="" style="color: #52627d;">Expected Shipment Date</label>
                                            <el-date-picker format="yyyy/MM/dd" value-format="yyyy-MM-dd" style="width: 100%" v-model="saleorder.delivery_date" type="date" placeholder="Pick a day"></el-date-picker>
                                        </v-flex>
                                        <v-flex xs4 sm5 offset-sm1 style="padding: 7px 0;">
                                            <label for="">Warehouse Name</label>
                                            <el-select v-model="saleorder.warehouse_id" filterable clearable placeholder="Warehouse" style="width: 100%;">
                                                <el-option v-for="item in warehouses" :key="item.id" :label="item.name" :value="item.id">
                                                </el-option>
                                            </el-select>
                                        </v-flex>
                                        <v-flex xs4 sm5 offset-sm1 style="padding: 7px 0;">
                                            <label for="" style="color: #52627d;">Merchant/Vendor Name*</label>
                                            <el-select v-model="saleorder.seller_id" filterable placeholder="type at least 3 characters" :loading="loading" style="width: 100%;" @change="getProduct('')">
                                                <el-option v-for="item in sellers.data" :key="item.id" :label="item.name" :value="item.id">
                                                </el-option>
                                            </el-select>
                                        </v-flex>
                                        <v-flex xs4 sm5 offset-sm1 style="padding: 7px 0;">
                                            <label for="">Delivery Status*</label>
                                            <el-select v-model="saleorder.delivery_status" filterable clearable placeholder="Status" style="width: 100%;">
                                                <el-option v-for="item in statuses" :key="item.id" :label="item.status" :value="item.status">
                                                </el-option>
                                            </el-select>
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
                            <p>Tell us about your order. The unique Sale Order will help us scan and identify the order.</p>
                            <br>
                            <!-- <p>Once you set the re-order point, we will notify you when the on-hand level matches the re-order point, so you can send us more inventory!</p> -->
                        </v-card-text>
                    </v-card>
                </v-flex>
            </v-layout>
            <div style="margin: 50px 0 0 0"></div>

            <v-hover style="padding: 20px 20px 20px 0; margin: 0 10px">
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
                                        <th scope="col">Price</th>
                                        <th scope="col">Quantity</th>
                                        <th scope="col">Amount</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(product, index) in products_arr" :key="index">
                                        <th scope="row">{{ index+1 }}</th>
                                        <td>
                                            {{ product.product_name }}
                                            <!-- <el-select v-model="product.product.id" filterable remote reserve-keyword placeholder="type at least 3 characters" :remote-method="getProduct" :loading="loading" style="width: 100%;">
                                                <el-option v-for="item in products.data" :key="item.id" :label="item.product_name" :value-key="item.id" :value="item">
                                                </el-option>
                                            </el-select> -->
                                        </td>
                                        <!-- <td>
                                            <el-input v-model="product.special_instructions"></el-input>
                                        </td> -->
                                        <td>
                                            <el-tag v-if="product.skus[0]" type="success">
                                                {{ product.skus[0]['price'] }}
                                            </el-tag>
                                            <!-- <el-input v-if="product.product.skus" v-model="product.product.skus[0]['price']"></el-input> -->
                                        </td>
                                        <td>
                                            <el-input v-model="product.quantity"></el-input>
                                        </td>

                                        <td>
                                            <el-tag v-if="product.skus[0]" type="success">
                                                {{ parseInt(product.quantity) * parseInt(product.skus[0]['price']) }}
                                            </el-tag>
                                            <el-tag v-else type="warning">0</el-tag>
                                            <!-- <el-input v-if="product.product.skus" v-model="product.product.skus[0]['price']"></el-input> -->
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
                        <v-flex xs12 sm4 offset-sm7>
                            <ul class="list-group">
                                <li class="list-group-item"><b>Sub Total</b> <span style="float: right">{{ saleorder.sub_total }}</span></li>
                                <li class="list-group-item"><b>Shipping Charges</b>
                                    <span style="float: right;width: 100px !important">
                                        <el-input placeholder="charges" v-model="saleorder.shipping_charges" ></el-input>
                                    </span>
                                </li>
                                <li class="list-group-item"><b>COD Amount</b>
                                    <span style="float: right;width: 100px !important">
                                        <el-input placeholder="cod amount" v-model="saleorder.total_price" ></el-input>
                                    </span>
                                </li>
                                <li class="list-group-item"><b>Adjustment</b> <span style="float: right;width: 100px !important">
                                        <el-input placeholder="charges" v-model="saleorder.adjustment" ></el-input>
                                    </span></li>

                                <li class="list-group-item"><b>Total ( KES )</b> <span style="float: right">{{ total }}</span></li>
                            </ul>
                        </v-flex>
                        <v-flex xs12 sm6 style="margin-left: 75px">

                            <label for="">Customer Notes</label>
                            <el-input type="textarea" placeholder="Please input" v-model="saleorder.customer_notes">
                            </el-input>
                        </v-flex>

                        <v-flex xs12 sm6 style="margin-left: 75px">
                            <label for="">Additional Notes</label>
                            <el-input type="textarea" placeholder="Please input" v-model="saleorder.more_comments">
                            </el-input>
                        </v-flex>
                    </v-layout>

                </v-card>
            </v-hover>
        </v-layout>
        
            <v-footer style="background: #e2e0e0 !important;" app>
                <span style="color: #000; margin: auto;">
                    <v-spacer></v-spacer>
                    <v-btn text color="primary" :loading="loading" :disabled="loading" style="border: 1px solid; border-radius: 20px;" @click="update">
                <v-icon>mdi-update</v-icon>
                        Update</v-btn>
            <v-btn color="primary" style="text-transform: none;" rounded text @click="goToSales">
                <v-icon>mdi-close-circle</v-icon>
                <span>Cancel</span>
            </v-btn>
                </span>
            </v-footer>

        <!-- </v-container> -->
    </div>

    <el-dialog title="Items" :visible.sync="dialog" width="30%" :before-close="close">
        <el-select v-model="product_item" filterable remote reserve-keyword placeholder="type at least 3 characters" :remote-method="getProduct" :loading="loading" style="width: 100%;" value-key="id">
            <el-option v-for="item in products.data" :key="item.id" :label="item.product_name" :value="item">
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
    <myClient />
</div>
</template>

<script>
import {
    mapState
} from 'vuex'

import myClient from '../users/clients/create'
export default {
    name: 'editOrder',
    components: {myClient},
    data: () => ({
        color: '#f0f0f0',
        hasvariant: 'no_variant',
        loading: false,
        products_arr: [],
        form: {
            shipping_charges: 0,
            adjustment: 0
        },
        dialog: false,
        product_item: {},
        client_id: ''
    }),

    computed: {
        ...mapState(['warehouses', 'statuses', 'sellers', 'products', 'sales', 'saleorder', 'clients']),
        sub_total() {
            var total = 0
            for (let index = 0; index < this.products_arr.length; index++) {
                const element = this.products_arr[index];
                if (element.skus[0]) {

                    let cleanedValue = element.skus[0]['price'].replace(/[^0-9.]/g, '');
                    const price = parseFloat(cleanedValue);
                    console.log("🚀 ~ file: edit.vue:285 ~ sub_total ~ price:", element.quantity)

                    total += price * parseInt(element.quantity)
                }
            }
            // total += parseInt(this.saleorder.adjustment)
            return total
        },

        total() {
            return parseInt(this.sub_total) + parseInt(this.form.shipping_charges) + parseInt(this.form.adjustment)
        }
    },
    methods: {
        numericTotalPrice() {
            let cleanedValue = this.saleorder.total_price.replace(/[^0-9.]/g, '');
            this.saleorder.total_price = parseFloat(cleanedValue);
        },
        getWarehouses() {
            var payload = {
                model: '/warehouses',
                update: 'updateWarehouseList'
            }
            this.$store.dispatch("getItems", payload);
        },
        goToSales() {
            this.$router.push({
                name: "sales",
            });
        },
        update() {
            var payload = {
                data: this.saleorder,
                model: 'sales',
                id: this.saleorder.id
            }
            this.$store.dispatch('patchItems', payload)
                .then(response => {
                    // this.goToSales()
                });
        },
        getSellers() {
            var payload = {
                model: "/seller/sellers",
                update: "updateSellerList"
            };
            this.$store.dispatch("getItems", payload).then((res) => {
                
            });
        },
        // getClients() {
        //     var payload = {
        //         model: "/seller/sellers",
        //         update: "updateSellerList"
        //     };
        //     this.$store.dispatch("getItems", payload);
        // },

        getProduct(search) {
            // console.log(search);
            // if (search.length > 2) {
                var payload = {
                    model: 'product_filter_search',
                    update: 'updateProductsList',
                    data: {
                        search: search,
                        vendor_id: this.saleorder.seller_id
                    }
                }
                this.$store.dispatch("filterItems", payload);
            // }
        },
        vendorProduct() {
            // console.log(search);
            // if (search.length > 2) {
                var payload = {
                    model: 'product_filter_search',
                    update: 'updateProductsList',
                    data: {
                        search: search,
                        vendor_id: this.user.id
                    }
                }
                this.$store.dispatch("filterItems", payload);
            // }
        },
        openDialog() {
            this.dialog = true
        },

        getOrder() {
            var payload = {
                model: 'sales',
                update: 'updateSale',
                id: this.$route.params.id
            }
            this.$store.dispatch('showItem', payload).then((res) => {
                this.products_arr = this.saleorder.products
                this.numericTotalPrice()
                this.getProduct('')
            })
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
            this.products_arr.forEach(element => {
                if (this.product_item.id == element.id) {
                    exists = true
                }
            });
            if (!exists) {
                this.products_arr.push(this.product_item)
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
            this.products_arr.splice(index, 1)

        },
        close() {
            this.dialog = false
        },
        openCreate() {
            eventBus.$emit("openCreateClient");
        },
        getStatus() {
            var payload = {
                model: 'statuses',
                update: 'updateStatusList'
            };
            this.$store.dispatch("getItems", payload);
        },
    },
    mounted() {
        this.getSellers()
        this.getOrder();
        this.getWarehouses();
        this.getStatus();

        if(this.user.is_vendor) {
            this.vendorProduct()
        }
        // setTimeout(() => {
        //     console.log(111)
        //     this.numericTotalPrice()
        // }, 300);
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

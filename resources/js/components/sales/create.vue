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
                                    <v-card slot-scope="{ hover }" :class="`elevation-${hover ? 12 : 2}`" class="mx-auto"
                                        width="100%">
                                        <v-layout wrap>

                                            <v-flex sm12>
                                                <el-select v-model="form.client" filterable remote reserve-keyword
                                                    placeholder="type at least 3 characters" :remote-method="searchClient"
                                                    :loading="loading" style="width: 100%;" value-key="id">
                                                    <el-option v-for="item in clients.data" :key="item.id"
                                                        :label="item.name" :value="item">
                                                    </el-option>

                                                    <div slot="empty">
                                                        <el-button type="text" @click="add_client">Add new</el-button>
                                                    </div>
                                                </el-select>
                                            </v-flex>

                                            <v-flex xs12 sm11 offset-sm1 style="padding: 7px 0;">
                                                <h4>Customer Details</h4>
                                            </v-flex>
                                            <v-divider></v-divider>
                                            <v-flex xs12 sm5 offset-sm1 style="padding: 7px 0;">
                                                <label for="" style="color: #52627d;">Customer name</label>
                                                <el-input placeholder="John doe" v-model="form.client.name"></el-input>
                                            </v-flex>
                                            <v-flex xs12 sm5 offset-sm1 style="padding: 7px 0;">
                                                <label for="" style="color: #52627d;">Customer email</label>
                                                <el-input placeholder="doe@gmail.com"
                                                    v-model="form.client.email"></el-input>
                                            </v-flex>
                                            <v-flex xs12 sm5 offset-sm1 style="padding: 7px 0;">
                                                <label for="" style="color: #52627d;">Customer phone</label>
                                                <el-input placeholder="+1..." v-model="form.client.phone"></el-input>
                                            </v-flex>
                                            <v-flex xs12 sm5 offset-sm1 style="padding: 7px 0;">
                                                <label for="" style="color: #52627d;">Customer address</label>
                                                <el-input placeholder="123 main st"
                                                    v-model="form.client.address"></el-input>
                                            </v-flex>

                                            <v-flex xs12 sm5 offset-sm1 style="padding: 7px 0;">
                                                <label for="" style="color: #52627d;">City</label>
                                                <el-input placeholder="" v-model="form.client.city"></el-input>
                                            </v-flex>
                                            <v-flex xs12 sm5 offset-sm1 style="padding: 7px 0;">
                                                <label for="" style="color: #52627d;">Country</label>
                                                <el-input placeholder="UAE" v-model="form.client.country"></el-input>
                                            </v-flex>
                                            <v-flex xs12 sm5 offset-sm1 style="padding: 7px 0;">
                                                <label for="" style="color: #52627d;">Postal code</label>
                                                <el-input placeholder="00100" v-model="form.client.postal_code"></el-input>
                                            </v-flex>
                                        </v-layout>
                                    </v-card>
                                </v-hover>
                            </v-flex>

                            <v-flex xs12 sm12>
                                <v-hover style="padding: 20px 20px 20px 0">
                                    <v-card slot-scope="{ hover }" :class="`elevation-${hover ? 12 : 2}`" class="mx-auto"
                                        width="100%">
                                        <v-layout wrap>
                                            <v-flex xs12 sm11 offset-sm1 style="padding: 7px 0;">
                                                <h4>Order Details</h4>
                                            </v-flex>
                                            <v-flex xs12 sm5 offset-sm1 style="padding: 7px 0;">
                                                <label for="" style="color: #52627d;">Order number</label>
                                                <el-input placeholder="Order Number" min="0"
                                                    v-model="form.order_no"></el-input>
                                            </v-flex>
                                            <v-flex xs12 sm5 offset-sm1 style="padding: 7px 0;">
                                                <label for="" style="color: #52627d;">Sales Order Date</label>
                                                <el-date-picker format="yyyy/MM/dd" value-format="yyyy-MM-dd"
                                                    style="width: 100%" v-model="form.order_date" type="date"
                                                    placeholder="Pick a day"></el-date-picker>
                                            </v-flex>
                                            <v-flex xs12 sm5 offset-sm1 style="padding: 7px 0;">
                                                <label for="" style="color: #52627d;">Expected Shipment Date</label>
                                                <el-date-picker format="yyyy/MM/dd" value-format="yyyy-MM-dd"
                                                    style="width: 100%" v-model="form.delivery_date" type="date"
                                                    placeholder="Pick a day"></el-date-picker>
                                            </v-flex>
                                            <v-flex xs4 sm5 offset-sm1 style="padding: 7px 0;">
                                                <label for="">Warehouse Name</label>
                                                <el-select v-model="form.warehouse_id" filterable clearable
                                                    placeholder="Warehouse" style="width: 100%;">
                                                    <el-option v-for="item in warehouses" :key="item.id" :label="item.name"
                                                        :value="item.id">
                                                    </el-option>
                                                    <div slot="empty">
                                                        <el-button type="text" style="margin-left: 10px;"
                                                            @click="add_new('warehouse')">Add new</el-button>
                                                    </div>
                                                </el-select>
                                            </v-flex>
                                            <v-flex xs4 sm5 offset-sm1 style="padding: 7px 0;" v-if="!user.is_vendor">
                                                <label for="" style="color: #52627d;">Merchant/Vendor Name*</label>
                                                <el-select v-model="form.vendor_id" filterable
                                                    placeholder="type at least 3 characters" style="width: 100%;"
                                                    @change="getProduct('')">
                                                    <el-option v-for="item in sellers.data" :key="item.id"
                                                        :label="item.name" :value="item.id">
                                                    </el-option>
                                                    <div slot="empty">
                                                        <el-button type="text" style="margin-left: 10px;"
                                                            @click="add_new('vendor')">Add new</el-button>
                                                    </div>
                                                </el-select>
                                            </v-flex>
                                            <v-flex xs4 sm5 offset-sm1 style="padding: 7px 0;" v-if="!user.is_vendor">
                                                <label for="">Delivery Status*</label>
                                                <el-select v-model="form.delivery_status" filterable clearable
                                                    placeholder="Status" style="width: 100%;">
                                                    <el-option v-for="item in statuses" :key="item.id" :label="item.status"
                                                        :value="item.status">
                                                    </el-option>
                                                    <div slot="empty">
                                                        <el-button type="text" style="margin-left: 10px;"
                                                            @click="add_new('status')">Add new</el-button>
                                                    </div>
                                                </el-select>
                                            </v-flex>
                                            <v-flex xs12 sm5 offset-sm1 style="padding: 7px 0;">
                                                <v-checkbox v-model="form.upsell"
                                                    label="Upsell?"></v-checkbox>
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
                                <p>Tell us about your order. The unique order number will help us scan and identify the
                                    order.</p>
                                <br>
                                <p>Please ensure that you have enough inventory on hand to ship the order on the reserve
                                    inventory date!</p>
                            </v-card-text>
                        </v-card>

                        <v-card :color="color">
                            <v-card-title>
                                Documents
                            </v-card-title>
                            <v-card-text>
                                <v-divider></v-divider>

                                <el-upload class="upload-demo" drag :action="`/sale_docs/${sale_id}`" :auto-upload="false"
                                    :on-preview="handlePreview" :on-remove="handleRemove" :file-list="fileList" multiple
                                    ref="upload" :headers="headers" :on-success="handleSuccess" :on-error="handleError">
                                    <i class="el-icon-upload"></i>
                                    <div class="el-upload__text">Drop file here or <em>click to upload</em></div>
                                    <div class="el-upload__tip" slot="tip">Upload you files here!</div>
                                </el-upload>
                            </v-card-text>
                        </v-card>

                    </v-flex>
                </v-layout>
                <div style="margin: 50px 0 0 0"></div>

                <v-hover style="padding: 20px 20px 20px 0; margin: 0 10px" v-if="form.vendor_id || user.is_vendor">
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
                                        <tr v-for="(product, index) in form.products_arr" :key="index">
                                            <th scope="row">{{ index + 1 }}</th>
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
                                                    <!-- {{ product.skus[0]['price'] }} -->
                                                    <el-input v-model="product.skus[0]['price']"></el-input>
                                                </el-tag>
                                            </td>
                                            <td>
                                                <el-input v-model="product.quantity"></el-input>
                                            </td>

                                            <td>
                                                <el-tag v-if="product.skus[0]" type="success">
                                                    {{ parseInt(product.quantity) * parseInt(product.skus[0]['price']) }}
                                                </el-tag>
                                                <el-tag v-else type="warning">0</el-tag>
                                                <!-- <el-input v-if="product.product.skus" v-model="product.skus[0]['price']"></el-input> -->
                                            </td>
                                            <td>

                                                <v-tooltip right>
                                                    <template v-slot:activator="{ on }">
                                                        <v-btn icon v-on="on" slot="activator" class="mx-0"
                                                            @click="remove(index)">
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
                                    <li class="list-group-item"><b>Sub Total</b> <span style="float: right">{{ sub_total
                                    }}</span></li>
                                    <li class="list-group-item"><b>Shipping Charges</b>
                                        <span style="float: right">
                                            <el-input placeholder="charges" v-model="form.shipping_charges"></el-input>
                                        </span>
                                    </li>
                                    <li class="list-group-item"><b>Adjustment</b> <span style="float: right">
                                            <el-input placeholder="charges" v-model="form.adjustment"></el-input>
                                        </span></li>

                                    <li class="list-group-item"><b>Total ( KES )</b> <span style="float: right">{{ total
                                    }}</span></li>
                                </ul>
                            </v-flex>
                            <v-flex xs12 sm6 style="margin-left: 75px">

                                <label for="">Customer Notes</label>
                                <el-input type="textarea" placeholder="Please input" v-model="form.customer_notes"
                                    maxlength="200" show-word-limit>
                                </el-input>
                            </v-flex>
                        </v-layout>

                    </v-card>

                </v-hover>

                <v-footer style="background: #e2e0e0 !important;" app>
                    <v-spacer></v-spacer>
                    <v-btn color="primary" style="text-transform: none;" rounded @click="update">
                        <v-icon>mdi-plus-box</v-icon>
                        <span>Create</span>
                    </v-btn>

                    <v-btn color="primary" style="text-transform: none;" rounded @click="goToSales">
                        <v-icon>mdi-close-circle</v-icon>
                        <span>Cancel</span>
                    </v-btn>
                </v-footer>
            </v-layout>
            <!-- </v-container> -->
        </div>

        <el-dialog title="Items" :visible.sync="dialog" width="30%" :before-close="close">
            <el-select v-model="product_item" filterable remote reserve-keyword placeholder="type at least 3 characters"
                :remote-method="getProduct" :loading="loading" style="width: 100%;" value-key="id">
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
        <clientCreate />
        <myWarehouse />
        <myStatus />
        <myVendor />
    </div>
</template>

<script>
import {
    mapState
} from 'vuex'
import clientCreate from '../users/clients/create'

import myWarehouse from "../warehouse/Create";
import myStatus from "../settings/status/create";

import myVendor from "../users/sellers/create";
export default {
    props: ['user'],
    components: {
        clientCreate,
        myWarehouse,
        myVendor,
        myStatus
    },
    name: 'create_order',
    data: () => ({
        sale_id: 0,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'accept': 'application/json'
        },
        color: '#f0f0f0',
        hasvariant: 'no_variant',
        loading: false,
        dialog: false,
        form: {
            products_arr: [],
            client: {},
            shipping_charges: 0,
            adjustment: 0,
            upsell: false
        },
        dialog: false,
        product_item: {},
        fileList: []
    }),

    computed: {
        ...mapState(['warehouses', 'statuses', 'sellers', 'products', 'clients']),
        sub_total() {
            var total = 0
            for (let index = 0; index < this.form.products_arr.length; index++) {
                const element = this.form.products_arr[index];
                if (element.skus[0]) {
                    total += parseInt(element.skus[0]['price']) * parseInt(element.quantity)
                }
            }
            return total
        },

        total() {
            return parseInt(this.sub_total) + parseInt(this.form.shipping_charges) + parseInt(this.form.adjustment)
        }
    },
    methods: {
        handlePreview() { },
        handleRemove() { },
        handleError(err, file) {
            // console.log(err.message.errors);
            // this.errors = err.response.data.errors
            this.$store.dispatch('page_loader', false)
            this.$message.error("Something went wrong. Please check your file!");
        },

        handleSuccess(res, file) {
            // this.$store.dispatch('page_loader', false)
            // eventBus.$emit('orderResponseEvent', res)
            // this.getProducts()
            // this.orders_upload = res
            this.goToSales()
        },
        beforeUpload(file) {
            // this.$store.dispatch('page_loader', true)
            // console.log(file);
            // const isJPG = file.type === 'image/jpeg';

        },
        upload() {
            this.$refs.upload.submit();
        },
        add_new(item) {
            if (item == 'warehouse') {
                eventBus.$emit('openCreateWarehouse')
            } else if (item == 'vendor') {
                eventBus.$emit('openCreateSeller')
            } else {
                eventBus.$emit('openCreateStatus')
            }
        },
        add_client() {
            eventBus.$emit("openCreateClient");
        },
        goToSales() {
            this.$router.push({
                name: "sales",
            });
        },
        update() {
            this.form.sub_total = this.sub_total
            this.form.total = this.total
            this.form.app = 'INV'

            var payload = {
                data: this.form,
                model: 'sales',
            }
            this.$store.dispatch('postItems', payload)
                .then(response => {
                    console.log("🚀 ~ file: create.vue:394 ~ update ~ response:", response)
                    this.sale_id = response.data.id
                    // this.goToSales()
                    setTimeout(() => {
                        this.upload()
                    }, 500);
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

        getStatus() {
            var payload = {
                model: 'statuses',
                update: 'updateStatusList'
            };
            this.$store.dispatch("getItems", payload);
        },
        getProduct(search) {
            // console.log(search);
            // if (search.length > 2) {
            var payload = {
                model: 'product_filter_search',
                update: 'updateProductsList',
                data: {
                    search: search,
                    vendor_id: this.form.vendor_id
                }
            }
            this.$store.dispatch("filterItems", payload);
            // }
        },
        openDialog() {
            this.dialog = true
            this.getProduct('')
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

        searchClient(search) {

            // console.log(search);
            if (search.length > 2) {
                var payload = {
                    model: 'client_search',
                    update: 'updateClientList',
                    search: search
                }
                this.$store.dispatch("searchItems", payload);
            }
        },
    },
    created() {
        eventBus.$on("statusEvent", data => {
            this.getStatus();
        });

        eventBus.$on("warehouseEvent", data => {
            this.getWarehouses();
        });

        eventBus.$on("sellerEvent", data => {
            this.getSellers();
        });
    },
    mounted() {
        this.getSellers()
        this.getWarehouses()
        this.getStatus();

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
}</style>

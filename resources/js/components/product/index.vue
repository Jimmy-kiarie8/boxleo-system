<template>
    <div>
        <v-card>
            <v-container fluid fill-height>
                <v-layout justify-center align-center wrap>
                    <!-- <v-card style="padding: 20px 20px;width:100%"> -->
                    <v-flex sm12>
                        <el-breadcrumb separator-class="el-icon-arrow-right">
                            <el-breadcrumb-item :to="{ path: '/' }">Dashboard</el-breadcrumb-item>
                            <el-breadcrumb-item>Products</el-breadcrumb-item>
                        </el-breadcrumb>
                    </v-flex>

                    <v-flex sm12>
                        <v-text-field v-model="product_search.search" label="Search (Product name or sku)" required
                            prepend-icon="mdi-magnify" @keyup.enter="search_product"></v-text-field>
                    </v-flex>
                    <v-flex sm12>
                        <v-pagination v-model="products.current_page" :length="products.last_page" total-visible="5"
                            @input="next_page(products.path, products.current_page)" circle
                            v-if="products.last_page > 1"></v-pagination>
                    </v-flex>

                    <v-flex sm12 style="text-align: center;padding: 20px 0;">
                        <h3 style="margin-left: 30px !important;margin-top: 10px;">Products</h3>
                    </v-flex>
                    <v-flex sm12>
                        <v-layout row>
                            <v-flex sm1 style="margin-left: 10px;">
                                <v-tooltip right>
                                    <template v-slot:activator="{ on }">
                                        <v-btn icon v-on="on" slot="activator" class="mx-0" @click="filter_products">
                                            <v-icon color="blue darken-2" small>mdi-refresh</v-icon>
                                        </v-btn>
                                    </template>
                                    <span>Refresh</span>
                                </v-tooltip>
                            </v-flex>
                            <v-flex sm2 style="margin-left: 10px; margin-bottom: 20px;" v-if="!user.is_vendor">
                                <label for="">Vendor</label>
                                <el-select v-model="form.client" placeholder="Client" clearable filterable
                                    @change="filter_products">
                                    <el-option v-for="item in sellers.data" :key="item.id" :label="item.name"
                                        :value="item.id">
                                    </el-option>
                                </el-select>
                            </v-flex>
                            <!-- <VSpacer/> -->
                            <v-flex sm5 offset-sm3>
                                <v-btn-toggle>
                                    <v-btn color="info" @click="openCreate" text outlined
                                        v-if="user.can['Product create']">New Product</v-btn>
                                    <v-btn color="info" @click="openExcel" text outlined
                                        v-if="user.can['Product create']">Upload Product</v-btn>
                                    <v-btn color="primary" @click="openShopify" text outlined
                                        v-if="api_connect.shopify && user.can['Order shopify upload']">Upload from
                                        Shopify</v-btn>
                                    <v-btn color="primary" @click="openApi('Shopify')" text outlined
                                        v-else-if="user.can['Order shopify upload']">Connect Shopify</v-btn>
                                    <v-btn color="primary" @click="openWoocommerce" text outlined
                                        v-if="api_connect.woocommerce && user.can['Product woocommerce upload']">Upload
                                        from Woocommerce</v-btn>
                                    <v-btn color="primary" @click="openApi('Woocommerce')" text outlined
                                        v-else-if="user.can['Order woocommerce upload']">Connect Woocommerce</v-btn>
                                </v-btn-toggle>
                            </v-flex>
                        </v-layout>
                        <v-divider></v-divider>

                        <v-card-title>
                            <v-spacer></v-spacer>
                            <v-text-field v-model="search" append-icon="mdi-magnify" label="Search" single-line
                                hide-details></v-text-field>
                        </v-card-title>

                        <el-tabs v-model="activeName" @tab-click="handleClick">
                            <!--<el-tab-pane label="All" name="all"></el-tab-pane>-->
                            <el-tab-pane label="Active" name="active"></el-tab-pane>
                            <el-tab-pane label="In Active" name="inactive"></el-tab-pane>
                            <el-tab-pane label="Lowstock" name="lowstock"></el-tab-pane>
                        </el-tabs>
                        <v-data-table :headers="headers" :items="products.data" :search="search" :loading="loading"
                            show-select class="elevation-1" v-model="selected" :single-select="singleSelect"
                            item-key="id">
                            <template v-slot:top>
                                <v-tooltip right v-if="selected.length > 0 && user.can['Product delete']">
                                    <template v-slot:activator="{ on }">
                                        <v-btn icon v-on="on" slot="activator" class="mx-0" @click="deleteItems">
                                            <v-icon color="red darken-2" small>mdi-delete</v-icon>
                                        </v-btn>
                                    </template>
                                    <span>Delete selected products</span>
                                </v-tooltip>
                                <v-tooltip right v-if="selected.length > 0">
                                    <template v-slot:activator="{ on }">
                                        <v-btn icon v-on="on" slot="activator" class="mx-0"
                                            @click="status_update('deactivate')">
                                            <v-icon color="red darken-2" small>mdi-close-circle</v-icon>
                                        </v-btn>
                                    </template>
                                    <span>Deactivate selected products</span>
                                </v-tooltip>
                                <v-tooltip right v-if="selected.length > 0">
                                    <template v-slot:activator="{ on }">
                                        <v-btn icon v-on="on" slot="activator" class="mx-0"
                                            @click="status_update('activate')">
                                            <v-icon color="green darken-2" small>mdi-check-circle</v-icon>
                                        </v-btn>
                                    </template>
                                    <span>Activate selected products</span>
                                </v-tooltip>
                                <v-tooltip right v-if="selected.length > 0">
                                    <template v-slot:activator="{ on }">
                                        <v-btn icon v-on="on" slot="activator" class="mx-0" @click="inventoryUpdate()">
                                            <v-icon color="green darken-2" small>mdi-warehouse</v-icon>
                                        </v-btn>
                                    </template>
                                    <span>Activate Inventory</span>
                                </v-tooltip>
                            </template>

                            <template v-slot:item.active="{ item }">
                                <el-tooltip :content="(item.active) ? 'active' : 'Not active'" placement="top">
                                    <v-avatar style="cursor: pointer" :color="(item.active) ? 'green' : 'red'"
                                        small></v-avatar>
                                </el-tooltip>
                            </template>
                            <template v-slot:item.virtual="{ item }">
                                <el-tooltip
                                    :content="(item.virtual) ? 'Inventory is not being tracked' : 'Inventory is being tracked'"
                                    placement="top">
                                    <v-avatar style="cursor: pointer" :color="(item.virtual) ? 'red' : 'green'"
                                        small></v-avatar>
                                </el-tooltip>
                            </template>
                            <template v-slot:item.images="{ item }">
                                <div v-if="item.images">
                                    <img :src="item.images.image" alt="" style="width: 100px;border-radius: 30px;"  @click="openImageDialog(item.images.image)">
                                </div>
                            </template>

                            <template v-slot:item.actions="{ item }">
                                <div style="min-width: 160px">
                                    <v-tooltip bottom v-if="user.can['Product edit']">
                                        <template v-slot:activator="{ on }">
                                            <v-btn v-on="on" icon class="mx-0" @click="openEdit(item)" slot="activator">
                                                <v-icon small color="blue darken-2">mdi-pencil</v-icon>
                                            </v-btn>
                                        </template>
                                        <span>Edit {{ item.product_name }}</span>
                                    </v-tooltip>
                                    <v-tooltip bottom v-if="user.can['Product quantity update']">
                                        <template v-slot:activator="{ on }">
                                            <v-btn icon v-on="on" class="mx-0" @click="updateQty(item)"
                                                slot="activator">
                                                <v-icon small color="blue darken-2">mdi-cart</v-icon>
                                            </v-btn>
                                        </template>
                                        <span>Update {{ item.product_name }} quantity</span>
                                    </v-tooltip>
                                    <v-tooltip bottom v-if="user.can['Product edit']">
                                        <template v-slot:activator="{ on }">
                                            <v-btn icon v-on="on" class="mx-0" @click="updateImg(item)"
                                                slot="activator">
                                                <v-icon small color="blue darken-2">mdi-image</v-icon>
                                            </v-btn>
                                        </template>
                                        <span>Update {{ item.product_name }} image</span>
                                    </v-tooltip>
                                    <v-tooltip bottom v-if="user.can['Product delete']">
                                        <template v-slot:activator="{ on }">
                                            <v-btn icon v-on="on" class="mx-0" @click="confirm_delete(item)"
                                                slot="activator">
                                                <v-icon small color="pink darken-2">mdi-delete</v-icon>
                                            </v-btn>
                                        </template>
                                        <span>Delete {{ item.product_name }}</span>
                                    </v-tooltip>

                                    <v-tooltip bottom>
                                        <template v-slot:activator="{ on }">
                                            <v-btn icon v-on="on" class="mx-0" @click="openProduct(item.id)"
                                                slot="activator">
                                                <v-icon small color="blue darken-2">mdi-stocking</v-icon>
                                            </v-btn>
                                        </template>
                                        <span>{{ item.product_name }} stock</span>
                                    </v-tooltip>

                                    <v-tooltip bottom>
                                        <template v-slot:activator="{ on }" v-if="user.can['Product edit']">
                                            <v-btn icon v-on="on" class="mx-0" @click="openTransfer(item)"
                                                slot="activator">
                                                <v-icon small color="blue darken-2">mdi-transfer</v-icon>
                                            </v-btn>
                                        </template>
                                        <span>Transfer {{ item.product_name }}</span>
                                    </v-tooltip>
                                    <v-tooltip bottom>
                                        <template v-slot:activator="{ on }">
                                            <v-btn icon v-on="on" class="mx-0" :to="'/products/' + item.id"
                                                slot="activator">
                                                <v-icon small color="blue darken-2">mdi-eye-circle-outline</v-icon>
                                            </v-btn>
                                        </template>
                                        <span>{{ item.product_name }} Details</span>
                                    </v-tooltip>

                                    <v-tooltip bottom v-if="user.can['Product edit']">
                                        <template v-slot:activator="{ on }">
                                            <v-btn icon v-on="on" class="mx-0" :href="`/product_stats/${item.id}`"
                                                target="_blank" slot="activator">
                                                <v-icon small color="blue darken-2">mdi-chart-areaspline</v-icon>
                                            </v-btn>
                                        </template>
                                        <span>Stock summary</span>
                                    </v-tooltip>
                                </div>
                            </template>
                        </v-data-table>
                    </v-flex>
                    <!-- </v-card> -->
                </v-layout>
            </v-container>
        </v-card>
        <myStock />
        <Create :user="user" />
        <myImage />
        <myShow />
        <myShopify v-if="api_connect.shopify" :user="user" />
        <myApiConnect v-else />
        <myWoocommerce :user="user" />
        <myExcel :user="user" />
        <!-- <myWoocommerce  v-if="api_connect.woocommerce"/> -->
        <myTransfer />

        <v-dialog v-model="imageDialog" max-width="600px">
            <v-card>
                <v-card-text>
                <v-img :src="selectedImage" class="white--text" height="400px"></v-img>
                </v-card-text>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
import Create from "./create";
import myImage from './image'
import myShow from './product_details'
import myShopify from './upload/shopify'
import myWoocommerce from './upload/woocommerce'
import myExcel from './upload/excel'
import myStock from "./stock";
import myApiConnect from '../settings/company/inc/api'
// import myWoocommerceConnect from '../settings/company/inc/api'
import myTransfer from '../../warehouse/products/transfer'
import {
    mapState
} from 'vuex';
export default {
    props: ['user'],
    components: {
        Create,
        myImage,
        myShow,
        myShopify,
        myStock,
        myApiConnect,
        myWoocommerce,
        myExcel,
        myTransfer
    },
    data() {
        return {
            singleSelect: false,
            imageDialog: false,
            selected: [],
            toggle_exclusive: 2,
            activeName: 'all',
            api_connect: {},
            loader: false,
            search: "",
            payload: {
                model: 'product_table',
                update: 'updateProductsList'
            },
            products_det: {
                data: []
            },
            form: {},
            products_search: [],
            temp: "",
            checkedRows: [],
            product_search: {
                search: '',
            },
            headers: [{
                text: 'name',
                value: 'product_name',
            }, {
                text: 'Image',
                value: 'images',
            }, {
                text: 'id',
                value: 'id',
            },
            {
                text: 'Sku no.',
                value: 'sku_no',
            },
            {
                text: 'Price',
                value: 'price',
            },
            {
                text: 'Available Stock',
                value: 'available_for_sale',
            },
            {
                text: 'Commited Stock',
                value: 'commited',
            },
            {
                text: 'Delivered',
                value: 'delivered',
            },
            {
                text: 'Onhand Stock',
                value: 'onhand',
            },
            {
                text: 'Vendor',
                value: 'seller.name',
            },
            {
                text: 'Active',
                value: 'active',
            },
            {
                text: 'Inventory tracking',
                value: 'virtual',
            },
            {
                text: "Created On",
                value: "created_at",
                // type: "date",
                // dateInputFormat: "YYYY-MM-DD",
                // dateOutputFormat: "Do MMMM YYYY"
            },
            {
                text: "Actions",
                value: "actions",
                sortable: false
            }
            ],
        };
    },
    methods: {

    openImageDialog(imageUrl) {
      this.selectedImage = imageUrl;
      this.imageDialog = true;
    },

        search_product() {
            this.search_filter = true
            this.default_filter = false

            var payload = {
                model: 'product_search',
                update: 'updateProductsList',
                search: this.product_search.search
            }
            this.$store.dispatch('searchItems', payload)
        },

        deleteItems() {

            this.$confirm('This will delete the products. Continue?', 'Warning', {
                confirmButtonText: 'OK',
                cancelButtonText: 'Cancel',
                type: 'warning'
            }).then(() => {
                // this.deleteItem(item)
                // this.get_orders();
                var payload = {
                    model: '/product_delete',
                    data: this.selected
                }
                this.$store.dispatch("postItems", payload).then(response => {
                    this.selected = []
                    this.filter_products();
                });
            }).catch(() => {
                this.$message({
                    type: 'error',
                    message: 'Delete canceled'
                });
            });

        },
        inventoryUpdate() {
            this.$confirm('This will activate inventory for the products. Continue?', 'Warning', {
                confirmButtonText: 'OK',
                cancelButtonText: 'Cancel',
                type: 'warning'
            }).then(() => {
                // this.deleteItem(item)
                // this.get_orders();
                var payload = {
                    model: '/product_inventory',
                    data: this.selected
                }
                this.$store.dispatch("postItems", payload).then(response => {
                    this.selected = []
                    this.filter_products();
                });
            }).catch(() => {


            });

        },
        status_update(status) {

            this.$confirm('This will ' + status + ' the products. Continue?', 'Warning', {
                confirmButtonText: 'OK',
                cancelButtonText: 'Cancel',
                type: 'warning'
            }).then(() => {
                // this.deleteItem(item)
                // this.get_orders();
                var payload = {
                    model: '/product_status/' + status,
                    data: this.selected
                }
                this.$store.dispatch("postItems", payload).then(response => {
                    this.selected = []
                    this.filter_products();
                });
            }).catch(() => {
                this.$message({
                    type: 'error',
                    message: 'Delete canceled'
                });
            });

        },
        handleClick(data) {
            // console.log(data);

            if (data.name == 'lowstock') {
                this.low_stock()
            } else {
                this.filter_products()
            }

        },
        openProduct(data) {
            // console.log(data);

            this.$router.push({
                name: "product_details",
                params: {
                    id: data
                }
            });

        },
        openTransfer(item) {
            eventBus.$emit('productTransferEvent', item)
        },
        openApi(api) {
            if (api == 'Shopify') {
                var api_val = {
                    site: 'Shopify',
                    value: 'shopify'
                }
            } else if (api == 'Woocommerce') {
                var api_val = {
                    site: 'Woocommerce',
                    value: 'woocommerce'
                }
            }
            eventBus.$emit('ApiConnectEvent', api_val)
        },
        openShopify() {
            eventBus.$emit("ShopifyProductEvent");
        },
        openWoocommerce() {
            eventBus.$emit("WoocommerceProductEvent");
        },
        openCreate() {
            eventBus.$emit("openCreateProduct");
        },
        openExcel() {
            eventBus.$emit("openExcelUploadEvent");
        },
        openEdit(data) {
            // eventBus.$emit("openEditProduct", data.row);

            this.$router.push({
                name: "editProduct",
                params: {
                    id: data.id
                }
            });
        },
        updateQty(data) {
            eventBus.$emit("updateQtyEvent", data);
        },
        updateImg(data) {
            console.log(data);
            eventBus.$emit("openImageEvent", data);
        },

        confirm_delete(item) {
            this.$confirm('This will permanently delete the file. Continue?', 'Warning', {
                confirmButtonText: 'OK',
                cancelButtonText: 'Cancel',
                type: 'warning'
            }).then(() => {
                this.deleteItem(item)
            }).catch(() => {
                this.$message({
                    type: 'error',
                    message: 'Delete canceled'
                });
            });
        },

        deleteItem(item) {
            this.$store.dispatch("deleteItem", "products/" + item.id).then(response => {
                this.$message({
                    type: 'success',
                    message: 'Delete completed'
                });
                this.filter_products();
            });
        },
        openShow(data) {
            // eventBus.$emit("openShowProduct", data);

            this.$router.push({
                name: "productDetails",
                params: {
                    id: data.id
                }
            });
        },
        getItems() {
            // this.form = {}

            var payload = {
                model: 'product_table?type=' + this.activeName,
                update: 'updateProductsList'
            }
            this.$store.dispatch("getItems", payload);
        },
        updateSelected(url) {
            // alert('test')
            if (this.checkedRows.length < 1) {
                eventBus.$emit("errorEvent", "Please select atleast one row");
                return;
            }

            this.$store
                .dispatch("updateSelected", {
                    url,
                    checked: this.checkedRows
                })
                .then(response => {
                    eventBus.$emit("alertRequest", "Updated");
                    this.checkedRows = [];
                    this.filter_products();
                });
        },
        selectionChanged(params) {
            this.checkedRows = params.selectedRows;
        },

        next_page(path, page) {
            console.log(this.form.client)
            if (this.form.client) {
                var payload = {
                    path: path,
                    page: page,
                    data: this.form,
                    update: 'updateProductsList'
                }
                this.$store.dispatch("nextPagePost", payload);

            } else {
                var payload = {
                    path: path,
                    page: page,
                    filter: this.form.client,
                    update: 'updateProductsList'
                }
                this.$store.dispatch("nextPage", payload);

            }
        },
        low_stock() {
            var payload = {
                model: 'low_stock',
                update: 'updateProductsList'
            }
            this.$store.dispatch("getItems", payload);
        },
        getApi() {
            var payload = {
                model: 'api_check',
                // id: 'shopify_key',
                update: 'updatApi'
            }
            this.$store.dispatch('getItems', payload)
                .then(response => {
                    console.log(response);

                    if (response.data) {
                        this.api_connect = response.data
                        // this.form.file = JSON.stringify(response.data.file, null, 2)
                        // this.form = response.data
                    }

                });
        },
        getSellers() {
            var payload = {
                model: "/seller/sellers",
                update: "updateSellerList"
            };
            this.$store.dispatch("getItems", payload);
        },
        filter_products() {

            var payload = {
                model: 'product_filter',
                data: this.form,
                update: 'updateProductsList'
            };
            this.$store.dispatch("filterItems", payload);

        }
    },
    computed: {
        ...mapState(['products', 'loading', 'sellers'])
    },
    mounted() {
        // this.$store.dispatch('getItems');
        eventBus.$emit("LoadingEvent");
        this.getItems();
        this.getApi(),
            this.getSellers()
    },
    created() {
        eventBus.$on("productEvent", data => {
            this.getItems();
        });

        eventBus.$on("responseChooseEvent", data => {
            console.log(data);
            if (data == "Excel") {
                eventBus.$emit("openEditProduct");
            } else {
                eventBus.$emit("openCreateProduct");
            }
        });
    },

    beforeRouteEnter(to, from, next) {
        next(vm => {
            if (vm.user.can["Products view"]) {
                next();
            } else {
                next("/");
            }
        });
    }
};
</script>

<style scoped>
.el-input--prefix .el-input__inner {
    border-radius: 50px !important;
}

.v-toolbar__content,
.v-toolbar__extension {
    height: auto !important;
}

#address_tab span {
    font-style: inherit;
    font-weight: inherit;
    white-space: nowrap;
    text-overflow: ellipsis;
    max-width: 200px;
    overflow: hidden;
    display: block;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
}

input[type=checkbox] {
    position: relative !important;
    cursor: pointer !important;
}

/* input[type=checkbox]:before {
    content: "" !important;
    display: block !important;
    position: absolute !important;
    width: 16px !important;
    height: 16px !important;
    top: 0 !important;
    left: 0 !important;
    border: 2px solid #555555 !important;
    border-radius: 3px !important;
    background-color: white !important;
}

input[type=checkbox]:checked:after {
    content: "" !important;
    display: block !important;
    width: 5px !important;
    height: 10px !important;
    border: solid black !important;
    border-width: 0 2px 2px 0 !important;
    -webkit-transform: rotate(45deg) !important;
    -ms-transform: rotate(45deg) !important;
    transform: rotate(45deg) !important;
    position: absolute !important;
    top: 2px !important;
    left: 6px !important;
} */
</style>

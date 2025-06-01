<template>
<div>
    <div id="product-edit">
        <!-- <v-container fluid fill-height> -->
        <v-layout justify-center align-center>
            <v-layout wrap style="margin-bottom: 50px">
                <v-flex sm9>
                    <v-flex sm12>
                        <VCard style="padding: 30px;">
                            <el-breadcrumb separator-class="el-icon-arrow-right">
                                <el-breadcrumb-item :to="{ path: '/' }">Dashboard</el-breadcrumb-item>
                                <el-breadcrumb-item :to="{ path: '/products' }">Products</el-breadcrumb-item>
                                <el-breadcrumb-item>{{ product.product_name }}</el-breadcrumb-item>
                            </el-breadcrumb>
                            <v-toolbar-title>
                                <v-tooltip right>
                                    <v-btn icon slot="activator" class="mx-0" @click="getProduct">
                                        <v-icon color="blue darken-2" small>mdi-refresh</v-icon>
                                    </v-btn>
                                    <span>Refresh</span>
                                </v-tooltip>
                            </v-toolbar-title>
                        </VCard>
                    </v-flex>
                    <v-flex sm12>
                        <v-hover>
                            <v-card slot-scope="{ hover }" :class="`elevation-${hover ? 12 : 2}`" class="mx-auto" width="100%">
                                <v-container fill-height>
                                    <v-layout align-center row wrap>

                                        <v-flex sm8>
                                            <div data-v-1bd51565="" data-v-5b54b316="" style="display: flex; flex-wrap: wrap;">
                                                <div data-v-1bd51565="" class="image-flex-wrapper">
                                                    <div data-v-1bd51565="" class="image-wrapper">
                                                        <img :src="avatar" v-if="avatar" style="width: 130px;height: 125px;">
                                                        <div data-v-1bd51565="" class="icon-image" v-else></div>
                                                        <!---->
                                                    </div>
                                                </div>
                                                <div data-v-1bd51565="" class="details-flex-wrapper">
                                                    <div data-v-1bd51565="" class="details-wrapper">
                                                        <div data-v-1bd51565="" class="title">
                                                            <h3 data-v-1bd51565="">{{ product.product_name }}</h3>
                                                        </div>
                                                        <p data-v-1bd51565="">On-hand:
                                                            <b data-v-1bd51565="">{{ product.onhand }}</b>
                                                        </p>
                                                        <p data-v-1bd51565="" v-if="product.active">Status: <b data-v-1bd51565="">Active</b></p>
                                                        <p data-v-1bd51565="" v-else>Status: <b data-v-1bd51565="">Inactive</b></p>
                                                        <div data-v-861aa9f6="" data-v-1bd51565="" class="file-upload-link">
                                                            <label data-v-861aa9f6="" for="imageUpload">
                                                                <span data-v-861aa9f6="" class="btn-link underline pointer">Upload Image </span>
                                                            </label>
                                                            <input data-v-861aa9f6="" id="imageUpload" type="file" style="display: none;" @change="Getimage">
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </v-flex>
                                        <!-- <v-flex sm3>
                                            <h6>Product Variants</h6>
                                            <v-divider></v-divider>

                                            <v-radio-group v-model="hasvariant" :mandatory="true">
                                                <v-radio label="No variant" value="no_variant"></v-radio>
                                                <v-radio label="Has variant" value="has_variant"></v-radio>
                                            </v-radio-group>
                                        </v-flex> -->
                                    </v-layout>
                                </v-container>
                            </v-card>
                        </v-hover>
                    </v-flex>
                    <v-flex sm12>
                        <v-hover>
                            <v-card slot-scope="{ hover }" :class="`elevation-${hover ? 12 : 2}`" class="mx-auto" width="100%">
                                <v-container fill-height>
                                    <Basicinfo :product="product" />
                                </v-container>
                            </v-card>
                        </v-hover>
                    </v-flex>
                    <v-flex sm12>
                        <v-hover>
                            <v-card slot-scope="{ hover }" :class="`elevation-${hover ? 12 : 2}`" class="mx-auto" width="100%">
                                <myVariants :product="product" :sku_details="sku_details" :attributes_b="attributes_b" v-if="hasvariant == 'has_variant'"></myVariants>
                                <myPricing v-else :sku_values="sku_values" :product="product" :warehouse_arr="warehouse_arr" />
                            </v-card>
                        </v-hover>
                    </v-flex>
                    <v-flex sm12>
                        <v-hover>
                            <v-card slot-scope="{ hover }" :class="`elevation-${hover ? 12 : 2}`" class="mx-auto" width="100%">
                                <Status :product="product" />
                            </v-card>
                        </v-hover>
                    </v-flex>
                    <v-flex sm12 v-if="!product.virtual">
                        <v-hover>
                            <v-card slot-scope="{ hover }" :class="`elevation-${hover ? 12 : 2}`" class="mx-auto" width="100%">
                                <myWarehouse :product="product" :warehouse_arr="warehouse_arr" />
                            </v-card>
                        </v-hover>
                    </v-flex>
                </v-flex>

                <v-flex sm3>
                    <v-hover>
                        <v-card slot-scope="{ hover }" :class="`elevation-${hover ? 12 : 2}`" class="mx-auto" width="100%">
                            <mySidebar :product="product" />
                        </v-card>
                    </v-hover>
                </v-flex>
            </v-layout>
            <v-footer style="background: #e2e0e0 !important;" app>
                <span style="color: #000; margin: auto;">
                    <v-spacer></v-spacer>
                    <v-btn text color="primary" :loading="loading" :disabled="loading" style="border: 1px solid; border-radius: 20px;" @click="update">Save</v-btn>
                </span>
            </v-footer>
        </v-layout>
        <!-- </v-container> -->
    </div>
</div>
</template>

<script>
import Status from './edit_product/Status'
import Basicinfo from "./edit_product/Basicinfo";
import mySidebar from './edit_product/sidebar'
import myVariants from './variants'
import myPricing from './edit_product/pricing'
import myWarehouse from './edit_product/warehouse'
export default {
    props: ['tenant', 'user'],
    name: 'editProduct',
    components: {
        Status,
        Basicinfo,
        mySidebar,
        myVariants,
        myPricing,
        myWarehouse
    },
    data: () => ({
        hasvariant: 'no_variant',
        loading: false,
        dialog: false,
        product: {
            stock_management: 0
        },
        sku_values: {},
        variants: [],
        form: {
            has_varients: false,
            variants_d: [{
                variants: {},
                subvariants: {},
                model: []
            }]
        },

        warehouse_arr: [{
            warehouse_id: null,
            onhand: 0,
            price: 0,
        }],
        avatar: '',
        attributes_b: {},
        sku_details: {},
    }),
    computed: {
        products() {
            return this.$store.getters.products;
        },

        quantity() {
            if (this.product.virtual) {

                var qty = 0
                for (let index = 0; index < this.warehouse_arr.length; index++) {
                    const element = this.warehouse_arr[index];
                    qty += parseInt(element.onhand);
                }
                return qty
            } else {
                return this.product.onhand
            }
        }
    },
    methods: {
        update() {

            if (this.product.virtual) {
                this.product.onhand = this.quantity
                this.sku_values.onhand = this.quantity
            }

            this.product.warehouse_arr = this.warehouse_arr
            if (this.hasvariant == 'has_variant') {
                var data = {
                    variants: this.variants,
                    product: this.product
                }
                var payload = {
                    model: 'variants',
                    data: data,
                    id: this.$route.params.id
                }
            } else {
                var data = {
                    sku_values: this.sku_values,
                    product: this.product
                }
                var payload = {
                    model: 'products',
                    data: data,
                    id: this.$route.params.id
                }
            }

            this.$store.dispatch('patchItems', payload)
            // this.loading = true
            // if (this.product.has_varients) {
            //     this.product.push(this.form)
            // }
            // axios.patch(`/products/${this.product.id}`, {
            //     product: this.$data.product,
            //     form: this.$data.form,
            // }).then((response) => {
            //     this.loading = false
            //     // console.log(response);
            //     this.$store.dispatch('alertEvent', 'Product updated')
            //     // alert('success')
            //     // context.commit('getProducts', response.data)
            //     // eventBus.$emit('productEvent', data)
            // }).catch((error) => {
            //     // alert('error')
            //     // console.log(error.response);
            //     this.loading = false
            //     if (error.response.status === 500) {
            //         eventBus.$emit('errorEvent', error.response.statusText)
            //         return
            //     } else if (error.response.status === 401 || error.response.status === 409) {
            //         eventBus.$emit('reloadRequest', error.response.statusText)
            //     } else {
            //         this.loading = false
            //     }
            //     this.errors = error.response.data.errors

            // })
        },
        Getimage(e) {
            let image = e.target.files[0];
            // this.read(image);
            let reader = new FileReader();
            reader.readAsDataURL(image);
            reader.onload = e => {
                this.avatar = e.target.result;
            };
            this.imagePlaced = true;
            let form = new FormData();
            form.append("image", image);
            this.file = form;
            // console.log(e.target.files);
            this.upload()
        },

        upload() {
            // this.loading = true;
            axios
                .post(`/images/${this.product.id}`, this.file)
                .then(response => {
                    // this.loading = false;
                    this.$store.dispatch('alertEvent', 'Image uploaded')
                    console.log(response);
                    this.imagePlaced = false;
                })
                .catch(error => {
                    // this.loading = false;
                    if (error.response.status === 500) {
                        eventBus.$emit("errorEvent", error.response.statusText);
                        return;
                    }
                    this.errors = error.response.data.errors;
                    this.errors = error.response.data.errors;
                });
        },
        // close() {
        //     this.dialog = false
        // },
        getSellers() {
            var payload = {
                model: "/seller/sellers",
                update: "updateSellerList"
            };
            this.$store.dispatch("getItems", payload);
        },
        getProduct() {
            if (this.products.length > 0) {
                this.product = this.products.find(
                    product => product.id == this.$route.params.id
                );
                this.avatar = this.product.image
            } else {
                axios
                    .get(`products/${this.$route.params.id}`)
                    .then(response => {
                        this.loading = false;
                        this.product = response.data;

                        if(response.data.stock_management == 1) {
                            if (Object.keys(response.data.inventory).length > 0) {
                                this.warehouse_arr = []
                                // this.warehouse_arr = response.data.warehouses
                                response.data.inventory.forEach(element => {
                                    if(element.pivot) {
                                        this.product.inventory.push(element.pivot)
                                        this.product.seller_id = element.pivot.seller_id
                                    }
                                });
 
                            }
                        } else {
                            if (Object.keys(response.data.warehouses).length > 0) {
                                this.warehouse_arr = [];
                                // this.warehouse_arr = response.data.warehouses
                                response.data.warehouses.forEach(element => {
                                    this.warehouse_arr.push(element.pivot)
                                    this.product.seller_id = element.pivot.seller_id
                                });

                            }
                            if (response.data.product_variants.length > 0) {
                                this.hasvariant = 'has_variant'
                            }
                            // console.log(response);
                            this.avatar = response.data.image
                        }
                        // this.close()
                        // context.commit('getProducts', response.data)
                    })
                    .catch(error => {
                        // console.log(error.response);
                        this.loading = false;
                    });
            }
        },
        getWarehouses() {
            var payload = {
                model: '/warehouses',
                update: 'updateWarehouseList'
            }
            this.$store.dispatch("getItems", payload);
        },

        getProductVariants() {
            if (this.products.length > 0) {
                this.product = this.products.find(
                    product => product.id == this.$route.params.id
                );
                this.avatar = this.product.image
            } else {
                axios
                    .get(`product_variant/${this.$route.params.id}`)
                    .then(response => {
                        this.loading = false;
                        this.attributes_b = response.data;
                        // console.log(response);
                        // this.avatar = response.data.image
                        // this.close()
                        // context.commit('getProducts', response.data)
                    })
                    .catch(error => {
                        // console.log(error.response);
                        this.loading = false;
                    });
            }
        },

        getProduct_sku() {
            axios
                .get(`sku/${this.$route.params.id}`)
                .then(response => {
                    if (response.data.status) {
                        this.sku_values = response.data.sku;
                    } else {
                        this.sku_values = {
                            price: "0",
                            sku_no: "",
                            quantity: "0",
                            reorder_point: "0",
                            buying_price: "0"
                        };
                    }
                    // console.log(response);
                    // this.avatar = response.data.image
                    // this.close()
                    // context.commit('getProducts', response.data)
                })
                .catch(error => {
                    // console.log(error.response);
                    this.loading = false;
                });
        },
        getProductSku() {
            if (this.products.length > 0) {
                this.product = this.products.find(
                    product => product.id == this.$route.params.id
                );
                this.avatar = this.product.image
            } else {
                axios
                    .get(`variant_sku/${this.$route.params.id}`)
                    .then(response => {
                        this.loading = false;
                        this.sku_details = response.data;
                        // console.log(response);
                        // this.avatar = response.data.image
                        // this.close()
                        // context.commit('getProducts', response.data)
                    })
                    .catch(error => {
                        // console.log(error.response);
                        this.loading = false;
                    });
            }
        },
        // getTypes() {
        //     this.$store.dispatch("getTypes");
        // },
        // getBoxes() {
        //     this.$store.dispatch('getBoxes');
        // },
        // getClassifications() {
        //     this.$store.dispatch("getClassifications");
        // },
        getCategory(id) {
            this.$store.dispatch('getCategory', id)
        },
        load_attributes() {
            this.getProductVariants();
            this.getProductSku();
            this.getProduct_sku()
            // eventBus.$emit("load_attributes")
        },
    },
    created() {
        eventBus.$on("openEditProduct", data => {
            this.dialog = true;
            this.form = data;
            // this.load_attributes()
            // console.log(data);
        });

        eventBus.$on("variantEvent", data => {
            this.variants = data;
            // this.load_attributes()
            // console.log(data);
        });
    },
    mounted() {
        this.getProduct();
        this.getProductVariants();
        this.getProductSku();
        this.getProduct_sku()
        this.getWarehouses();
        this.getSellers();

        // this.getCategory()
        // this.getTypes()
        // this.getBoxes()
        // this.getClassifications()
        // this.load_attributes()
    },


    beforeRouteEnter(to, from, next) {
        next(vm => {
          if (vm.user.can["Product edit"]) {
            next();
          } else {
            next("/");
          }
        });
      }

};
</script>

<style>
 .el-select-dropdown {
        zoom: 90% !important;
    }
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

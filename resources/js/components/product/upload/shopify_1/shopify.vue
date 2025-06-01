<template>
<v-card>
    <v-card-title>
        Upload Products from Shopify
    </v-card-title>
    <v-container grid-list-md>
        <v-card-text>
            <label for="">Vendor</label>
            <el-select name="client" filterable placeholder="type at least 3 characters" :loading="loading" style="width: 100%;" v-model="form.vendor_id">
                <el-option v-for="item in sellers.data" :key="item.id" :label="item.name" :value="item.id">
                </el-option>
            </el-select>

            <div style="height: 10px;"></div>
            <label for="">Warehouse</label>
            <el-select name="warehouse" filterable placeholder="type at least 3 characters" :loading="loading" style="width: 100%;" v-model="form.warehouse_id">
                <el-option v-for="item in warehouses" :key="item.id" :label="item.name" :value="item.id">
                </el-option>
            </el-select>

        </v-card-text>

    </v-container>
</v-card>
</template>

<script>
import {
    mapState
} from "vuex";
export default {
    props: ['form'],
    data() {
        return {
            dialog: false,
            loading: false,
            products: {},
        };
    },
    methods: {
        disable_btn() {
            this.loading = true
            this.$refs.google_form.submit()
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
        getSellers() {
            var payload = {
                model: "/seller/sellers",
                update: "updateSellerList"
            };
            this.$store.dispatch("getItems", payload);
        },

        get_products() {
            var payload = {
                model: 'shopify_products',
                data: this.form
            }
            this.$store.dispatch('postItems', payload)
                .then(response => {
                    console.log(response.data);
                    this.getProducts()
                    this.products = response.data
                    // if (response.data.length > 0) {
                        // eventBus.$emit('ShopifyResponseEvent', response.data)
                    // } else {
                        // this.$message({
                        //     type: 'error',
                        //     message: 'No Data found'
                        // });
                    // }

                    // eventBus.$emit("MenuEvent")
                });
        },
        getProducts() {
            var payload = {
                model: 'client_products',
                update: 'updateProductsList',
                id: this.form.vendor_id
            }
            this.$store.dispatch("showItem", payload);
        },
    },
    computed: {
        ...mapState(['sellers', 'warehouses'])
    },
    created() {
        this.getSellers()
        this.getWarehouses()
        eventBus.$on('ShopifyProductEvent', data => {
            // this.get_products()
        })
    }
};
</script>

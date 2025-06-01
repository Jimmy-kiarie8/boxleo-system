<template>
<v-layout row justify-center>
    <v-dialog v-model="dialog" persistent max-width="500px">
        <v-card>
            <v-card-title>
                <span class="headline text-center" style="margin: auto;">Create Product</span>
            </v-card-title>
            <v-divider></v-divider>
            <v-card-text>
                <v-container grid-list-md>
                    <v-layout row wrap>
                        <v-flex sm12>
                            <div>
                                <label for="">Product name</label>
                                <el-input placeholder="Product name" v-model="form.product_name"></el-input>
                            </div>
                        </v-flex>
                        <v-flex sm12>
                            <div>
                                <label for="">Price</label>
                                <el-input placeholder="200" v-model="form.price"></el-input>
                            </div>
                        </v-flex>
                        <v-flex sm12 v-if="!user.is_vendor">
                            <label for="" style="color: #52627d;">Vendor Name*</label>
                            <el-select v-model="form.vendor_id" filterable remote reserve-keyword placeholder="type at least 3 characters" :remote-method="searchSellers" :loading="loading" style="width: 100%;">
                                <el-option v-for="item in sellers.data" :key="item.id" :label="item.name" :value="item.id">
                                </el-option>

                                <div slot="empty">
                                    <el-button type="text" style="margin-left: 10px;" @click="add_new">Add new</el-button>
                                </div>
                            </el-select>
                        </v-flex>
                    </v-layout>
                </v-container>
            </v-card-text>
            <v-card-actions>
                <v-btn color="blue darken-1" text @click="close">Close</v-btn>
                <v-spacer></v-spacer>
                <v-btn color="blue darken-1" text @click="save" :loading="loading" :disabled="loading">Save</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
    <myVendor />
</v-layout>
</template>

<script>
import {
    mapState
} from 'vuex';
import myVendor from "../users/sellers/create";
export default {
    props: ['user'],
    components: {
        myVendor,
    },
    data: () => ({
        dialog: false,
        form: {
            description: '',
            name: '',
            pos_status: '',
            price: '',
            product_barcode: '',
            quantity: '',
            sku: '',
            tax_category_id: '',
            tax_percent: '',
            type: '',
            weight: '',
        },
    }),
    created() {
        eventBus.$on("openCreateProduct", data => {
            this.dialog = true;
            this.getSellers()
        });
        eventBus.$on("sellerEvent", data => {
            this.getSellers();
        });
    },

    methods: {
        save() {
            var payload = {
                model: 'products_store',
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
        getSellers() {
            var payload = {
                model: "/seller/sellers",
                update: "updateSellerList"
            };
            this.$store.dispatch("getItems", payload);
        },
        searchSellers(search) {
            if (search.length > 2) {
                var payload = {
                    model: "/seller/seller_search",
                    update: "updateSellerList",
                    search: search
                };
                this.$store.dispatch("searchItems", payload);
            }
        },
        add_new(item) {
            eventBus.$emit('openCreateSeller')
        },
    },
    computed: {
        ...mapState(['sellers', 'loading'])
    },
};
</script>
